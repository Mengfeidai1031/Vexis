<?php

namespace App\Services;

use App\Models\OfertaCabecera;
use App\Models\OfertaLinea;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OfertaPdfService
{
    /**
     * Procesar un archivo PDF de oferta
     */
    public function procesarPdf($pdfFile, $clienteId, $vehiculoId)
    {
        // 1. Guardar el PDF
        $pdfPath = $pdfFile->store('ofertas/pdfs', 'public');
        
        // 2. Extraer texto del PDF
        $pathCompleto = storage_path('app/public/' . $pdfPath);
        $texto = Pdf::getText($pathCompleto);
        
        // 3. Procesar el texto y extraer información
        $datosOferta = $this->extraerDatosDeTexto($texto);
        
        // 4. Crear la oferta cabecera
        $ofertaCabecera = OfertaCabecera::create([
            'cliente_id' => $clienteId,
            'vehiculo_id' => $vehiculoId,
            'fecha' => $datosOferta['fecha'] ?? now(),
            'pdf_path' => $pdfPath,
        ]);
        
        // 5. Crear las líneas de la oferta
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
     * Personalizado para el formato de Grupo ARI
     */
    private function extraerDatosDeTexto($texto)
    {
        $lineas = [];
        $fecha = now();
        
        // Buscar fecha en formato "Fecha Pedido 20/06/2025" o "Fecha Pedido DD/MM/YYYY"
        if (preg_match('/Fecha\s+Pedido\s+(\d{2})\/(\d{2})\/(\d{4})/i', $texto, $matches)) {
            try {
                $fecha = Carbon::createFromFormat('d/m/Y', "{$matches[1]}/{$matches[2]}/{$matches[3]}");
            } catch (\Exception $e) {
                $fecha = now();
            }
        }
        
        // Dividir el texto en líneas
        $lineasTexto = explode("\n", $texto);
        
        foreach ($lineasTexto as $lineaTexto) {
            $lineaTexto = trim($lineaTexto);
            
            // Saltar líneas vacías o muy cortas
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
            
            // PATRÓN 2: "Nissan Assistance: 30,00 €" o similar
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
                    'precio' => abs($this->convertirPrecio($matches[2])), // Guardamos como positivo
                ];
                continue;
            }
            
            // PATRÓN 6: "Promociones: DTO ... -331,00 €" (Renault/Dacia)
            if (preg_match('/^Promociones:\s+(.+?)\s+(-?[\d.,]+)\s*€/', $lineaTexto, $matches)) {
                $lineas[] = [
                    'tipo' => 'descuento',
                    'descripcion' => trim($matches[1]),
                    'precio' => abs($this->convertirPrecio($matches[2])),
                ];
                continue;
            }
            
            // PATRÓN 7: "Transporte 353,00 €" o "Transporte: 270,00 €"
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
            
            // PATRÓN 10: "pack look ... 347,11 €" o "cámara de visión trasera ... 181,82 €"
            if (preg_match('/^([a-záéíóúñ\s]+?)\s+([\d.,]+)\s*€/i', $lineaTexto, $matches)) {
                $descripcion = trim($matches[1]);
                
                // Saltar si es una línea de total o subtotal
                if (preg_match('/^(base|total|subtotal|igic|impuesto)/i', $descripcion)) {
                    continue;
                }
                
                // Solo procesar si la descripción tiene al menos 5 caracteres
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
            'lineas' => $lineas,
        ];
    }

    /**
     * Convertir precio del formato español (1.234,56) al formato decimal (1234.56)
     */
    private function convertirPrecio($precioTexto)
    {
        // Eliminar el signo negativo si existe (lo manejaremos después)
        $esNegativo = strpos($precioTexto, '-') !== false;
        $precioTexto = str_replace('-', '', $precioTexto);
        
        // Eliminar espacios
        $precioTexto = str_replace(' ', '', $precioTexto);
        
        // Convertir formato español a decimal
        // 1.234,56 → 1234.56
        // 234,56 → 234.56
        // 1234.56 → 1234.56 (ya está en formato correcto)
        
        if (strpos($precioTexto, ',') !== false) {
            // Formato español: eliminar puntos y cambiar coma por punto
            $precioTexto = str_replace('.', '', $precioTexto);
            $precioTexto = str_replace(',', '.', $precioTexto);
        }
        
        $precio = floatval($precioTexto);
        
        return $esNegativo ? -$precio : $precio;
    }

    /**
     * Determinar el tipo de línea basado en la descripción
     */
    private function determinarTipo($descripcion)
    {
        $descripcionLower = strtolower($descripcion);
        
        // Descuentos
        if (strpos($descripcionLower, 'descuento') !== false || 
            strpos($descripcionLower, 'rebaja') !== false ||
            strpos($descripcionLower, 'dto') !== false ||
            strpos($descripcionLower, 'oferta') !== false ||
            strpos($descripcionLower, 'promocion') !== false ||
            strpos($descripcionLower, 'descto') !== false) {
            return 'descuento';
        }
        
        // Accesorios y extras
        if (strpos($descripcionLower, 'accesorio') !== false || 
            strpos($descripcionLower, 'extra') !== false ||
            strpos($descripcionLower, 'adicional') !== false ||
            strpos($descripcionLower, 'pack') !== false ||
            strpos($descripcionLower, 'gastos') !== false ||
            strpos($descripcionLower, 'matricula') !== false ||
            strpos($descripcionLower, 'pre-entrega') !== false) {
            return 'accesorios';
        }
        
        // Por defecto: opciones
        return 'opciones';
    }

    /**
     * Eliminar una oferta y su PDF
     */
    public function eliminarOferta(OfertaCabecera $oferta)
    {
        // Eliminar el PDF si existe
        if ($oferta->pdf_path && Storage::disk('public')->exists($oferta->pdf_path)) {
            Storage::disk('public')->delete($oferta->pdf_path);
        }
        
        // Eliminar la oferta (las líneas se eliminan automáticamente por cascade)
        $oferta->delete();
    }
}