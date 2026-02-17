<?php

namespace App\Services;

use App\Models\OfertaCabecera;
use App\Models\OfertaLinea;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OfertaPdfService
{
    /**
     * Procesar un archivo PDF de oferta
     */
    public function procesarPdf($pdfFile)
    {
        // 1. Guardar el PDF
        $pdfPath = $pdfFile->store('ofertas/pdfs', 'public');
        
        // 2. Extraer texto del PDF
        $pathCompleto = storage_path('app/public/' . $pdfPath);
        $texto = Pdf::getText($pathCompleto);
        
        // 3. Procesar el texto y extraer información
        $datosOferta = $this->extraerDatosDeTexto($texto);
        
        // 4. Buscar o crear cliente
        $clienteId = $this->buscarOCrearCliente($datosOferta['cliente']);
        
        // 5. Buscar o crear vehículo
        $vehiculoId = $this->buscarOCrearVehiculo($datosOferta['vehiculo']);
        
        // 6. Calcular totales
        $calculos = $this->calcularTotales($datosOferta['lineas']);
        
        // 7. Crear la oferta cabecera
        $ofertaCabecera = OfertaCabecera::create([
            'cliente_id' => $clienteId,
            'vehiculo_id' => $vehiculoId,
            'fecha' => $datosOferta['fecha'] ?? now(),
            'pdf_path' => $pdfPath,
            'cliente_nombre_pdf' => $datosOferta['cliente']['nombre'] ?? null,
            'cliente_dni_pdf' => $datosOferta['cliente']['dni'] ?? null,
            'vehiculo_modelo_pdf' => $datosOferta['vehiculo']['modelo'] ?? null,
            'vehiculo_chasis_pdf' => $datosOferta['vehiculo']['chasis'] ?? null,
            'base_imponible' => $calculos['base_imponible'],
            'impuestos' => $calculos['impuestos'],
            'total_sin_impuestos' => $calculos['total_sin_impuestos'],
            'total_con_impuestos' => $calculos['total_con_impuestos'],
        ]);
        
        // 8. Crear las líneas de la oferta
        foreach ($datosOferta['lineas'] as $lineaData) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabecera->id,
                'tipo' => $lineaData['tipo'],
                'descripcion' => $lineaData['descripcion'],
                'precio' => $lineaData['precio'],
            ]);
        }
        
        return $ofertaCabecera;
    }

    /**
     * Extraer datos del texto del PDF
     */
    private function extraerDatosDeTexto($texto)
    {
        $lineas = [];
        $fecha = now();
        $cliente = [];
        $vehiculo = [];
        
        // Extraer fecha
        if (preg_match('/Fecha\s+Pedido\s+(\d{2})\/(\d{2})\/(\d{4})/i', $texto, $matches)) {
            try {
                $fecha = Carbon::createFromFormat('d/m/Y', "{$matches[1]}/{$matches[2]}/{$matches[3]}");
            } catch (\Exception $e) {
                $fecha = now();
            }
        }
        
        // Extraer datos del cliente
        // Patrón: "Sr./Sra. Don/Doña NOMBRE APELLIDOS" seguido de DNI/NIF
        if (preg_match('/(?:Sr\.|Sra\.)(?:\s+Do[ñn]a?)?\s+([A-Za-zÁÉÍÓÚáéíóúñÑ\s]+)/i', $texto, $matches)) {
            $cliente['nombre'] = trim($matches[1]);
        }
        
        // Buscar DNI/NIF
        if (preg_match('/(?:DNI|NIF|N\.?I\.?F\.?):\s*([0-9]{8}[A-Z])/i', $texto, $matches)) {
            $cliente['dni'] = trim($matches[1]);
        }
        
        // Extraer datos del vehículo
        // Buscar modelo (diferentes patrones según la marca)
        if (preg_match('/Modelo\s+de\s+inter[eé]s\s+(.+?)(?=\n|\d{1,3}\.\d{3},|\d{1,3},)/is', $texto, $matches)) {
            $vehiculo['modelo'] = trim(preg_replace('/\s+/', ' ', $matches[1]));
        } elseif (preg_match('/Modelo:\s+(.+?)(?=\n|\d)/is', $texto, $matches)) {
            $vehiculo['modelo'] = trim(preg_replace('/\s+/', ' ', $matches[1]));
        }
        
        // Buscar chasis/bastidor
        if (preg_match('/(?:Bastidor|Chasis|VIN)[\s:]+([A-Z0-9]{17})/i', $texto, $matches)) {
            $vehiculo['chasis'] = trim($matches[1]);
        }
        
        // Dividir el texto en líneas para procesar precios
        $lineasTexto = explode("\n", $texto);
        
        foreach ($lineasTexto as $lineaTexto) {
            $lineaTexto = trim($lineaTexto);
            
            if (strlen($lineaTexto) < 5) {
                continue;
            }
            
            // PATRÓN 1: "Modelo de interés ... 27.271,21 €"
            if (preg_match('/^Modelo\s+de\s+interés\s+(.+?)\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'opciones',
                    'descripcion' => 'Modelo: ' . trim($matches[1]),
                    'precio' => $this->convertirPrecio($matches[2]),
                ];
                continue;
            }
            
            // PATRÓN 2: "Nissan Assistance: 30,00 €"
            if (preg_match('/^([^:]+):\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $descripcion = trim($matches[1]);
                $precio = $this->convertirPrecio($matches[2]);
                
                $lineas[] = [
                    'tipo' => $this->determinarTipo($descripcion),
                    'descripcion' => $descripcion,
                    'precio' => $precio,
                ];
                continue;
            }
            
            // PATRÓN 3: "Opciones Pack Diseño 560,11 €"
            if (preg_match('/^Opciones\s+(.+?)\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'opciones',
                    'descripcion' => trim($matches[1]),
                    'precio' => $this->convertirPrecio($matches[2]),
                ];
                continue;
            }
            
            // PATRÓN 4: "Pintura / Interior ... 305,61 €"
            if (preg_match('/^Pintura\s*\/\s*Interior\s+(.+?)\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'opciones',
                    'descripcion' => 'Pintura/Interior: ' . trim($matches[1]),
                    'precio' => $this->convertirPrecio($matches[2]),
                ];
                continue;
            }
            
            // PATRÓN 5: "Oferta Promocional [099008] ... -3.007,00 €"
            if (preg_match('/^Oferta\s+Promocional\s+\[[\w\d]+\]\s+(.+?)\s+(-?[\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'descuento',
                    'descripcion' => trim($matches[1]),
                    'precio' => abs($this->convertirPrecio($matches[2])),
                ];
                continue;
            }
            
            // PATRÓN 6: "Promociones: DTO ... -331,00 €"
            if (preg_match('/^Promociones:\s+(.+?)\s+(-?[\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'descuento',
                    'descripcion' => trim($matches[1]),
                    'precio' => abs($this->convertirPrecio($matches[2])),
                ];
                continue;
            }
            
            // PATRÓN 7: "Transporte 353,00 €"
            if (preg_match('/^Transporte:?\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'opciones',
                    'descripcion' => 'Transporte',
                    'precio' => $this->convertirPrecio($matches[1]),
                ];
                continue;
            }
            
            // PATRÓN 8: "Gastos Matriculación ... 890,00 €"
            if (preg_match('/^Gastos\s+(.+?)\s+([\d.,]+)\s*€/', $lineaTexto, $matches) ||
                preg_match('/^Gastos:\s+(.+?)\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'accesorios',
                    'descripcion' => trim($matches[1]),
                    'precio' => $this->convertirPrecio($matches[2]),
                ];
                continue;
            }
            
            // PATRÓN 9: "Color: ... 247,93 €"
            if (preg_match('/^Color:\s+(.+?)\s+([\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'opciones',
                    'descripcion' => 'Color: ' . trim($matches[1]),
                    'precio' => $this->convertirPrecio($matches[2]),
                ];
                continue;
            }
            
            // PATRÓN 10: Genérico "descripción precio €"
            if (preg_match('/^([a-záéíóúñ\s]+?)\s+([\d.,]+)\s*€/i', $lineaTexto, $matches)) {
                $descripcion = trim($matches[1]);
                
                if (preg_match('/^(base|total|subtotal|igic|impuesto|iva)/i', $descripcion)) {
                    continue;
                }
                
                if (strlen($descripcion) >= 5) {
                    $lineas[] = [
                        'tipo' => $this->determinarTipo($descripcion),
                        'descripcion' => ucfirst($descripcion),
                        'precio' => $this->convertirPrecio($matches[2]),
                    ];
                }
            }
        }
        
        return [
            'fecha' => $fecha,
            'cliente' => $cliente,
            'vehiculo' => $vehiculo,
            'lineas' => $lineas,
        ];
    }

    /**
     * Buscar o crear cliente basado en DNI
     */
    private function buscarOCrearCliente($datosCliente)
    {
        if (empty($datosCliente['dni'])) {
            return null; // Permitir null si no se encuentra DNI
        }

        // Buscar cliente existente por DNI
        $cliente = Cliente::where('dni', $datosCliente['dni'])->first();

        if ($cliente) {
            return $cliente->id;
        }

        // Si no existe, crear uno nuevo
        $nombreCompleto = $datosCliente['nombre'] ?? 'Cliente Extraído PDF';
        $partes = explode(' ', $nombreCompleto, 2);

        $cliente = Cliente::create([
            'nombre' => $partes[0] ?? 'Nombre',
            'apellidos' => $partes[1] ?? 'Apellidos',
            'empresa_id' => 1, // Empresa por defecto
            'dni' => $datosCliente['dni'],
            'domicilio' => 'Extraído de PDF',
            'codigo_postal' => '00000',
        ]);

        return $cliente->id;
    }

    /**
     * Buscar o crear vehículo basado en chasis
     */
    private function buscarOCrearVehiculo($datosVehiculo)
    {
        if (empty($datosVehiculo['chasis'])) {
            return null; // Permitir null si no se encuentra chasis
        }

        // Buscar vehículo existente por chasis
        $vehiculo = Vehiculo::where('chasis', $datosVehiculo['chasis'])->first();

        if ($vehiculo) {
            return $vehiculo->id;
        }

        // Si no existe, crear uno nuevo
        $vehiculo = Vehiculo::create([
            'chasis' => $datosVehiculo['chasis'],
            'modelo' => $datosVehiculo['modelo'] ?? 'Modelo Extraído PDF',
            'version' => 'Extraído de PDF',
            'color_externo' => 'No especificado',
            'color_interno' => 'No especificado',
            'empresa_id' => 1, // Empresa por defecto
        ]);

        return $vehiculo->id;
    }

    /**
     * Calcular totales de la oferta
     */
    private function calcularTotales($lineas)
    {
        $subtotalOpciones = 0;
        $subtotalDescuentos = 0;
        $subtotalAccesorios = 0;

        foreach ($lineas as $linea) {
            switch ($linea['tipo']) {
                case 'opciones':
                    $subtotalOpciones += $linea['precio'];
                    break;
                case 'descuento':
                    $subtotalDescuentos += $linea['precio'];
                    break;
                case 'accesorios':
                    $subtotalAccesorios += $linea['precio'];
                    break;
            }
        }

        $totalSinImpuestos = $subtotalOpciones - $subtotalDescuentos + $subtotalAccesorios;
        $impuestos = $totalSinImpuestos * 0.095; // IGIC 9.5%
        $totalConImpuestos = $totalSinImpuestos + $impuestos;

        return [
            'base_imponible' => $subtotalOpciones + $subtotalAccesorios,
            'impuestos' => $impuestos,
            'total_sin_impuestos' => $totalSinImpuestos,
            'total_con_impuestos' => $totalConImpuestos,
        ];
    }

    /**
     * Convertir precio del formato español al decimal
     */
    private function convertirPrecio($precioTexto)
    {
        $esNegativo = strpos($precioTexto, '-') !== false;
        $precioTexto = str_replace('-', '', $precioTexto);
        $precioTexto = str_replace(' ', '', $precioTexto);
        
        if (strpos($precioTexto, ',') !== false) {
            $precioTexto = str_replace('.', '', $precioTexto);
            $precioTexto = str_replace(',', '.', $precioTexto);
        }
        
        $precio = floatval($precioTexto);
        
        return $esNegativo ? -$precio : $precio;
    }

    /**
     * Determinar el tipo de línea
     */
    private function determinarTipo($descripcion)
    {
        $descripcionLower = strtolower($descripcion);
        
        if (strpos($descripcionLower, 'descuento') !== false || 
            strpos($descripcionLower, 'rebaja') !== false ||
            strpos($descripcionLower, 'dto') !== false ||
            strpos($descripcionLower, 'oferta') !== false ||
            strpos($descripcionLower, 'promocion') !== false) {
            return 'descuento';
        }
        
        if (strpos($descripcionLower, 'accesorio') !== false || 
            strpos($descripcionLower, 'extra') !== false ||
            strpos($descripcionLower, 'pack') !== false ||
            strpos($descripcionLower, 'gastos') !== false ||
            strpos($descripcionLower, 'matricula') !== false) {
            return 'accesorios';
        }
        
        return 'opciones';
    }

    /**
     * Eliminar una oferta y su PDF
     */
    public function eliminarOferta(OfertaCabecera $oferta)
    {
        if ($oferta->pdf_path && Storage::disk('public')->exists($oferta->pdf_path)) {
            Storage::disk('public')->delete($oferta->pdf_path);
        }
        
        $oferta->delete();
    }
}