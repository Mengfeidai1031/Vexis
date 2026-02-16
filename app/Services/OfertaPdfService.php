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
     * NOTA: Esta función debe adaptarse al formato específico de tus PDFs
     */
    private function extraerDatosDeTexto($texto)
    {
        $lineas = [];
        $fecha = now();
        
        // IMPORTANTE: Aquí debes personalizar según el formato de tus PDFs
        // Este es un ejemplo básico que busca patrones comunes
        
        // Buscar fecha (ejemplo: 22/01/2025 o 22-01-2025)
        if (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $texto, $matches)) {
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
            
            // Buscar líneas con precios (ejemplo: "Descripción 1,234.56 €" o "Descripción 1234.56")
            if (preg_match('/(.+?)\s+([\d,\.]+)\s*€?/', $lineaTexto, $matches)) {
                $descripcion = trim($matches[1]);
                $precio = str_replace(',', '.', str_replace('.', '', $matches[2])); // 1.234,56 -> 1234.56
                $precio = floatval($precio);
                
                // Determinar el tipo basado en palabras clave
                $tipo = $this->determinarTipo($descripcion);
                
                // Solo agregar si tiene un precio válido
                if ($precio > 0) {
                    $lineas[] = [
                        'tipo' => $tipo,
                        'descripcion' => $descripcion,
                        'precio' => $precio,
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
     * Determinar el tipo de línea basado en la descripción
     */
    private function determinarTipo($descripcion)
    {
        $descripcionLower = strtolower($descripcion);
        
        if (strpos($descripcionLower, 'descuento') !== false || 
            strpos($descripcionLower, 'rebaja') !== false ||
            strpos($descripcionLower, 'dto') !== false) {
            return 'descuento';
        }
        
        if (strpos($descripcionLower, 'accesorio') !== false || 
            strpos($descripcionLower, 'extra') !== false ||
            strpos($descripcionLower, 'adicional') !== false) {
            return 'accesorios';
        }
        
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