<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OfertaCabecera;
use App\Models\OfertaLinea;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Empresa;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class OfertaPdfService
{
    /**
     * Procesar un archivo PDF de oferta según la marca
     */
    public function procesarPdf($pdfFile, string $marca): OfertaCabecera
    {
        // 1. Guardar el PDF
        $pdfPath = $pdfFile->store('ofertas/pdfs', 'public');
        
        // 2. Extraer texto del PDF
        $pathCompleto = storage_path('app/public/' . $pdfPath);
        
        // Para Renault/Dacia usar -layout para mantener la estructura de la tabla (lectura línea por línea)
        if ($marca === 'renault_dacia') {
            $pdf = new Pdf();
            $pdf->setPdf($pathCompleto);
            $pdf->setOptions(['-layout']); // Mantiene el layout original, mejor para tablas
            $texto = $pdf->text();
        } else {
            $texto = Pdf::getText($pathCompleto);
        }
        
        // 3. Procesar según la marca
        return DB::transaction(function () use ($texto, $pdfPath, $marca) {
            if ($marca === 'nissan') {
                return $this->procesarNissan($texto, $pdfPath);
            } else {
                return $this->procesarRenaultDacia($texto, $pdfPath);
            }
        });
    }

    /**
     * Procesar PDF de NISSAN
     */
    private function procesarNissan(string $texto, string $pdfPath): OfertaCabecera
    {
        // DEBUG: Guardar texto extraído para análisis
        \Illuminate\Support\Facades\Log::info('=== TEXTO PDF NISSAN ===');
        \Illuminate\Support\Facades\Log::info($texto);
        \Illuminate\Support\Facades\Log::info('=== FIN TEXTO PDF ===');
        
        // Extraer datos de empresa (derecha del PDF)
        $datosEmpresa = $this->extraerEmpresaNissan($texto);
        $empresa = $this->crearEmpresa($datosEmpresa);
        
        // Extraer datos de cliente (izquierda del PDF)
        $datosCliente = $this->extraerClienteNissan($texto);
        $cliente = $this->crearCliente($datosCliente, $empresa->id);
        
        // Extraer líneas de oferta
        $lineas = $this->extraerLineasNissan($texto);
        
        // Buscar chasis/bastidor (17 caracteres alfanuméricos)
        $chasis = $this->buscarChasis($texto);
        
        // Crear vehículo si existe chasis
        $vehiculo = null;
        $datosVehiculo = [];
        if ($chasis) {
            $datosVehiculo = $this->extraerVehiculoNissan($lineas, $chasis, $empresa->id, $texto);
            $vehiculo = $this->crearVehiculo($datosVehiculo);
        }
        
        // Extraer fecha
        $fecha = $this->extraerFechaNissan($texto);
        
        // Crear oferta cabecera
        $ofertaCabecera = OfertaCabecera::create([
            'cliente_id' => $cliente->id,
            'vehiculo_id' => $vehiculo?->id,
            'fecha' => $fecha,
            'pdf_path' => $pdfPath,
            'cliente_nombre_pdf' => $datosCliente['nombre_completo'] ?? null,
            'cliente_dni_pdf' => $datosCliente['dni'] ?? null,
            'vehiculo_modelo_pdf' => $datosVehiculo['modelo'] ?? null,
            'vehiculo_chasis_pdf' => $chasis,
        ]);
        
        // Crear líneas de oferta
        $this->crearLineasOferta($ofertaCabecera->id, $lineas);
        
        // Calcular totales
        $this->calcularYActualizarTotales($ofertaCabecera);
        
        return $ofertaCabecera->fresh(['lineas', 'cliente', 'vehiculo', 'cliente.empresa']);
    }

    /**
     * Procesar PDF de RENAULT/DACIA
     */
    private function procesarRenaultDacia(string $texto, string $pdfPath): OfertaCabecera
    {
        // Extraer datos de cliente primero (necesario para extraer empresa)
        $datosCliente = $this->extraerClienteRenault($texto);
        
        // Extraer datos de empresa (derecha del PDF) - pasar nombre del cliente
        $datosEmpresa = $this->extraerEmpresaRenault($texto, $datosCliente['nombre_completo'] ?? '');
        $empresa = $this->crearEmpresa($datosEmpresa);
        
        // Crear cliente
        $cliente = $this->crearCliente($datosCliente, $empresa->id);
        
        // Extraer líneas de oferta
        $lineas = $this->extraerLineasRenault($texto);
        
        // Buscar chasis/bastidor (17 caracteres alfanuméricos)
        $chasis = $this->buscarChasis($texto);
        
        // Crear vehículo si existe chasis
        $vehiculo = null;
        $datosVehiculo = [];
        if ($chasis) {
            $datosVehiculo = $this->extraerVehiculoRenault($lineas, $chasis, $empresa->id);
            $vehiculo = $this->crearVehiculo($datosVehiculo);
        }
        
        // Extraer fecha
        $fecha = $this->extraerFechaRenault($texto);
        
        // Crear oferta cabecera
        $ofertaCabecera = OfertaCabecera::create([
            'cliente_id' => $cliente->id,
            'vehiculo_id' => $vehiculo?->id,
            'fecha' => $fecha,
            'pdf_path' => $pdfPath,
            'cliente_nombre_pdf' => $datosCliente['nombre_completo'] ?? null,
            'cliente_dni_pdf' => $datosCliente['dni'] ?? null,
            'vehiculo_modelo_pdf' => $datosVehiculo['modelo'] ?? null,
            'vehiculo_chasis_pdf' => $chasis,
        ]);
        
        // Crear líneas de oferta
        $this->crearLineasOferta($ofertaCabecera->id, $lineas);
        
        // Calcular totales
        $this->calcularYActualizarTotales($ofertaCabecera);
        
        return $ofertaCabecera->fresh(['lineas', 'cliente', 'vehiculo', 'cliente.empresa']);
    }

    // ==================== EXTRACTORES NISSAN ====================

    private function extraerEmpresaNissan(string $texto): array
    {
        $datos = [
            'nombre' => 'Empresa Nissan',
            'abreviatura' => 'NIS',
            'cif' => '',
            'domicilio' => '',
            'codigo_postal' => '',
            'telefono' => '',
        ];

        // Buscar nombre de empresa: BRISA MOTOR, S.L. o similar (nombre + S.L./S.A.)
        if (preg_match('/([A-ZÁÉÍÓÚÑ][A-Za-záéíóúñ\s]+,\s*S\.?[LA]\.?)/u', $texto, $matches)) {
            $datos['nombre'] = trim($matches[1]);
            // Abreviatura: primera palabra del nombre
            $palabras = explode(' ', $datos['nombre']);
            $datos['abreviatura'] = strtoupper(substr($palabras[0], 0, 5));
        }

        // Buscar CIF: letra + 8 números (ej: B35590926)
        if (preg_match('/CIF[:\s]*([A-Z]\d{8})/i', $texto, $matches)) {
            $datos['cif'] = $matches[1];
        } elseif (preg_match('/\b([B-Z]\d{8})\b/', $texto, $matches)) {
            $datos['cif'] = $matches[1];
        }

        // Buscar teléfono fijo (9 dígitos empezando por 9)
        if (preg_match('/\b(9\d{8})\b/', $texto, $matches)) {
            $datos['telefono'] = $matches[1];
        }

        // Buscar código postal de EMPRESA (el PRIMERO que aparece entre paréntesis)
        // En Nissan: primero aparece el CP de empresa, luego el del cliente
        if (preg_match_all('/\((\d{5})\)/', $texto, $matches)) {
            if (!empty($matches[1][0])) {
                $datos['codigo_postal'] = $matches[1][0];
            }
        }

        // Buscar domicilio de empresa (Carlos V, 55. Carrizal de Ingenio...)
        if (preg_match('/Carlos\s+V[,\s]+(\d+)[.\s]+([^(]+?)\s*\(\d{5}\)/iu', $texto, $matches)) {
            $datos['domicilio'] = 'Carlos V, ' . trim($matches[1]) . '. ' . trim($matches[2]);
        } elseif (preg_match('/(Carlos\s+V[^(]+)/i', $texto, $matches)) {
            $datos['domicilio'] = trim($matches[1]);
        }

        return $datos;
    }

    private function extraerClienteNissan(string $texto): array
    {
        $datos = [
            'nombre' => 'Cliente',
            'apellidos' => 'PDF',
            'nombre_completo' => '',
            'dni' => null,
            'email' => '',
            'telefono' => '',
            'domicilio' => '',
            'codigo_postal' => '00000',
        ];

        // Buscar nombre completo: Sr. Juan Andres Negrin Melian
        if (preg_match('/Sr\.?\s+([A-Za-záéíóúñÁÉÍÓÚÑ\s]+?)(?=\n|Calle|C\/|Santa|\d{9})/iu', $texto, $matches)) {
            $nombreCompleto = trim($matches[1]);
            $datos['nombre_completo'] = $nombreCompleto;
            
            // Separar en nombre y apellidos (primeras 2 palabras = nombre, resto = apellidos)
            $palabras = preg_split('/\s+/', $nombreCompleto);
            $palabras = array_filter($palabras);
            $palabras = array_values($palabras);
            
            if (count($palabras) >= 4) {
                $datos['nombre'] = $palabras[0] . ' ' . $palabras[1];
                $datos['apellidos'] = implode(' ', array_slice($palabras, 2));
            } elseif (count($palabras) >= 2) {
                $datos['nombre'] = $palabras[0];
                $datos['apellidos'] = implode(' ', array_slice($palabras, 1));
            } else {
                $datos['nombre'] = $nombreCompleto;
                $datos['apellidos'] = '';
            }
        }

        // Buscar DNI/NIF: 8 dígitos + 1 letra (ej: 78465326K)
        if (preg_match('/NIF[:\s]*(\d{8}[A-Z])/i', $texto, $matches)) {
            $datos['dni'] = $matches[1];
        } elseif (preg_match('/\b(\d{8}[A-Z])\b/', $texto, $matches)) {
            $datos['dni'] = $matches[1];
        }

        // Buscar email del CLIENTE (gmail, hotmail, yahoo, etc. - correo personal)
        // El email del cliente suele ser personal, no empresarial
        if (preg_match_all('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i', $texto, $matches)) {
            foreach ($matches[1] as $email) {
                $emailLower = strtolower($email);
                // Priorizar correos personales (gmail, hotmail, yahoo, etc.)
                if (preg_match('/@(gmail|hotmail|yahoo|outlook|live|icloud)\./i', $emailLower)) {
                    $datos['email'] = $emailLower;
                    break;
                }
            }
            // Si no se encontró correo personal, tomar el primero
            if (empty($datos['email']) && !empty($matches[1])) {
                $datos['email'] = strtolower($matches[1][0]);
            }
        }

        // Buscar teléfono móvil del cliente (6 o 7 al inicio = móvil particular)
        if (preg_match('/\b([67]\d{8})\b/', $texto, $matches)) {
            $datos['telefono'] = $matches[1];
        }

        // Buscar código postal del CLIENTE (el SEGUNDO que aparece entre paréntesis)
        // En Nissan: primero aparece el CP de empresa, luego el del cliente
        if (preg_match_all('/\((\d{5})\)/', $texto, $matches)) {
            if (count($matches[1]) >= 2) {
                $datos['codigo_postal'] = $matches[1][1];
            } elseif (!empty($matches[1][0])) {
                $datos['codigo_postal'] = $matches[1][0];
            }
        }

        // Buscar domicilio del cliente (Calle Seneca, 11 Telde...)
        if (preg_match('/Calle\s+([^(]+?)\s*\(\d{5}\)/iu', $texto, $matches)) {
            $datos['domicilio'] = 'Calle ' . trim($matches[1]);
        } elseif (preg_match('/(Calle\s+[A-Za-záéíóúñÁÉÍÓÚÑ,\s\d]+)/iu', $texto, $matches)) {
            $datos['domicilio'] = trim($matches[1]);
        }

        return $datos;
    }

    private function extraerLineasNissan(string $texto): array
    {
        $lineas = [];
        
        // Normalizar texto - quitar saltos de línea múltiples y espacios extra
        $textoNorm = preg_replace('/\r\n/', "\n", $texto);
        $textoNorm = preg_replace('/[ \t]+/', ' ', $textoNorm);
        
        // Patrón de precio: 27.271,21 € o -3.007,00 €
        $precioPattern = '(-?[\d.]+,\d{2})\s*€';
        
        // === 1. MODELO DE INTERÉS (descripción principal del vehículo) ===
        // Buscar: "Townstar Combi 5 L1... [código]" seguido de precio (puede haber líneas vacías)
        if (preg_match('/((?:Townstar|Qashqai|Juke|Leaf|X-Trail|Micra|Ariya|Navara)[^€\[\]]*\[[A-Z0-9\-]+\])\s*\n\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Modelo de interés',
                'descripcion' => trim($match[1]),
                'precio' => $this->convertirPrecio($match[2]),
            ];
        }
        
        // === 2. NISSAN ASSISTANCE ===
        // Buscar: "Nissan Assistance:" seguido de precio
        if (preg_match('/Nissan\s+Assistance[:\s]*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Modelo de interés',
                'descripcion' => 'Nissan Assistance',
                'precio' => $this->convertirPrecio($match[1]),
            ];
        }
        
        // === 3. OPCIONES (Pack Diseño, etc.) ===
        // Buscar: "Opciones" ... "Pack Diseño" ... precio
        if (preg_match('/Opciones\s+Pack\s+([A-Za-záéíóúñÁÉÍÓÚÑ]+)\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Opciones',
                'descripcion' => 'Pack ' . trim($match[1]),
                'precio' => $this->convertirPrecio($match[2]),
            ];
        }
        
        // === 4. PINTURA / INTERIOR y 5. OFERTAS PROMOCIONALES ===
        // Estos tienen los precios AGRUPADOS al final del bloque:
        // Color / Tapicería ... [código1] desc1 ... [código2] desc2 ... [código3] desc3 ... precio1 precio2 precio3 precio4
        
        // Buscar descripción de Pintura/Interior: "Gris Ontario Metalizado / Textil C"
        $descripcionPintura = '';
        if (preg_match('/((?:Gris|Negro|Blanco|Azul|Rojo|Plata|Marrón|Naranja|Verde|Amarillo)[A-Za-záéíóúñÁÉÍÓÚÑ\s]+(?:Metalizado|Sólido|Nacarado|Perla)?)\s*\/\s*([A-Za-záéíóúñÁÉÍÓÚÑ\s]+?)(?=\n|Oferta)/iu', $textoNorm, $match)) {
            $descripcionPintura = trim($match[1]) . ' / ' . trim($match[2]);
        }
        
        // Buscar descripciones de ofertas promocionales: [codigo] descripción
        $ofertasDescripciones = [];
        if (preg_match_all('/\[(\d{6})\]\s*([^\[\n]+?)(?=\n|Oferta\s+Promocional|\d+,\d{2}\s*€)/iu', $textoNorm, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $ofertasDescripciones[] = [
                    'codigo' => $m[1],
                    'descripcion' => trim($m[2]),
                ];
            }
        }
        
        // Buscar el bloque de precios después de las ofertas: "305,61 € -3.007,00 € -398,00 € -2.584,00 €"
        // Este bloque contiene: precio_pintura, precio_oferta1, precio_oferta2, precio_oferta3
        $numPrecios = 1 + count($ofertasDescripciones); // 1 para pintura + ofertas
        $patternBloque = '';
        for ($i = 0; $i < $numPrecios; $i++) {
            $patternBloque .= '\s*' . $precioPattern;
        }
        
        // Buscar después del último código de oferta
        if (!empty($ofertasDescripciones) && preg_match('/\[\d{6}\][^\[]*?' . $patternBloque . '/iu', $textoNorm, $matchPrecios)) {
            $precios = [];
            preg_match_all('/' . $precioPattern . '/u', $matchPrecios[0], $preciosMatch);
            $precios = $preciosMatch[1] ?? [];
            
            // Asignar precios: primero pintura, luego ofertas
            $idx = 0;
            
            // Pintura / Interior
            if (!empty($descripcionPintura) && isset($precios[$idx])) {
                $lineas[] = [
                    'tipo' => 'Pintura / Interior',
                    'descripcion' => $descripcionPintura,
                    'precio' => $this->convertirPrecio($precios[$idx]),
                ];
                $idx++;
            }
            
            // Ofertas promocionales
            foreach ($ofertasDescripciones as $oferta) {
                if (isset($precios[$idx])) {
                    $lineas[] = [
                        'tipo' => 'Oferta Promocional',
                        'descripcion' => '[' . $oferta['codigo'] . '] ' . $oferta['descripcion'],
                        'precio' => $this->convertirPrecio($precios[$idx]),
                    ];
                    $idx++;
                }
            }
        }
        
        // === 6. TRANSPORTE ===
        if (preg_match('/Transporte\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Transporte',
                'descripcion' => 'sin detalles',
                'precio' => $this->convertirPrecio($match[1]),
            ];
        }
        
        // === 7. BASE ===
        if (preg_match('/\bBase\b\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Base',
                'descripcion' => 'sin detalles',
                'precio' => $this->convertirPrecio($match[1]),
            ];
        }
        
        // === 8. IGIC y 9. IMPUESTO ===
        // Estos también tienen precios agrupados: "IGIC desc... Impuesto desc... precio1 precio2"
        $descripcionIGIC = '';
        $descripcionImpuesto = '';
        
        if (preg_match('/IGIC\s+(IGIC[A-Za-záéíóúñÁÉÍÓÚÑ\s\(\)%0-9]+?)(?=Impuesto)/iu', $textoNorm, $match)) {
            $descripcionIGIC = trim($match[1]);
        }
        
        if (preg_match('/Impuesto\s+(Exento\s+\d+%|Normal[^€\n]+)/iu', $textoNorm, $match)) {
            $descripcionImpuesto = trim($match[1]);
        }
        
        // Buscar bloque de precios de IGIC e Impuesto
        if (!empty($descripcionIGIC) || !empty($descripcionImpuesto)) {
            if (preg_match('/(?:Exento\s+\d+%|Normal[^€\n]+)\s*\n?\s*' . $precioPattern . '\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $matchImp)) {
                if (!empty($descripcionIGIC)) {
                    $lineas[] = [
                        'tipo' => 'IGIC',
                        'descripcion' => $descripcionIGIC,
                        'precio' => $this->convertirPrecio($matchImp[1]),
                    ];
                }
                if (!empty($descripcionImpuesto)) {
                    $lineas[] = [
                        'tipo' => 'Impuesto',
                        'descripcion' => $descripcionImpuesto,
                        'precio' => $this->convertirPrecio($matchImp[2]),
                    ];
                }
            }
        }
        
        // === 10. SUBTOTAL ===
        // "Subtotal Gastos 23.206,85 €" - el precio de subtotal está después de "Gastos"
        if (preg_match('/Subtotal\s+Gastos\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Subtotal',
                'descripcion' => 'sin detalles',
                'precio' => $this->convertirPrecio($match[1]),
            ];
        }
        
        // === 11. GASTOS (Matriculación y Pre-entrega) ===
        if (preg_match('/Matriculaci[oó]n\s+y\s+Pre-?entrega\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'Gastos',
                'descripcion' => 'Matriculación y Pre-entrega',
                'precio' => $this->convertirPrecio($match[1]),
            ];
        }
        
        // === 12. TOTAL ===
        if (preg_match('/\bTOTAL\b\s*\n?\s*' . $precioPattern . '/iu', $textoNorm, $match)) {
            $lineas[] = [
                'tipo' => 'TOTAL',
                'descripcion' => 'sin detalles',
                'precio' => $this->convertirPrecio($match[1]),
            ];
        }
        
        return $lineas;
    }

    private function extraerVehiculoNissan(array $lineas, string $chasis, int $empresaId, string $texto): array
    {
        $modelo = 'Nissan';
        $version = 'Sin especificar';
        $colorExterno = 'No especificado';
        $colorInterno = 'No especificado';

        // BUSCAR DIRECTAMENTE EN EL TEXTO el modelo del vehículo
        // Buscar patrón: palabra que empieza con mayúscula seguida de descripción y código [XXX]
        // Ejemplo: "Townstar Combi 5 L1 1.3G EU6E 96 kW (130 CV) 6M/T N-Connecta [TWC53GM1N-D3KQA5]"
        if (preg_match('/\b(Townstar|Qashqai|Juke|Leaf|X-Trail|Micra|Ariya|Navara)[^€\[\]]*\[([A-Z0-9\-]+)\]/iu', $texto, $matchModelo)) {
            $modeloCompleto = trim($matchModelo[0]);
            // Quitar el código final [XXX]
            $modeloCompleto = preg_replace('/\s*\[[A-Z0-9\-]+\]$/', '', $modeloCompleto);
            
            $palabras = preg_split('/\s+/', $modeloCompleto);
            $palabras = array_filter($palabras);
            $palabras = array_values($palabras);
            
            if (!empty($palabras[0])) {
                $modelo = 'Nissan ' . $palabras[0];
                $version = implode(' ', array_slice($palabras, 1)) . ' [' . $matchModelo[2] . ']';
            }
        }

        // BUSCAR DIRECTAMENTE EN EL TEXTO los colores
        // Buscar patrón después de "Pintura / Interior": Color Metalizado / Tapicería
        // Importante: usar lookahead para NO capturar texto de más (hasta "Oferta" o línea nueva)
        if (preg_match('/((?:Gris|Negro|Blanco|Azul|Rojo|Plata|Marrón|Naranja|Verde|Amarillo)[A-Za-záéíóúñÁÉÍÓÚÑ\s]+(?:Metalizado|Sólido|Nacarado|Perla)?)\s*\/\s*([A-Za-záéíóúñÁÉÍÓÚÑ\s]+?)(?=\n|\r|Oferta|$)/iu', $texto, $matchColores)) {
            $colorExterno = trim($matchColores[1]);
            $colorInterno = trim($matchColores[2]);
        }

        return [
            'chasis' => $chasis,
            'modelo' => $modelo,
            'version' => $version ?: 'Sin especificar',
            'color_externo' => $colorExterno,
            'color_interno' => $colorInterno,
            'empresa_id' => $empresaId,
        ];
    }

    private function extraerFechaNissan(string $texto): Carbon
    {
        // Buscar "Fecha Pedido 20/06/2025"
        if (preg_match('/Fecha\s+Pedido\s+(\d{2})\/(\d{2})\/(\d{4})/i', $texto, $matches)) {
            try {
                return Carbon::createFromFormat('d/m/Y', "{$matches[1]}/{$matches[2]}/{$matches[3]}");
            } catch (\Exception $e) {
                // Continuar si falla
            }
        }
        return now();
    }

    // ==================== EXTRACTORES RENAULT/DACIA ====================

    private function extraerEmpresaRenault(string $texto, string $nombreCliente = ''): array
    {
        $datos = [
            'nombre' => 'Empresa Renault',
            'abreviatura' => 'REN',
            'cif' => '',
            'domicilio' => '',
            'codigo_postal' => '',
            'telefono' => '',
        ];

        // Si tenemos el nombre del cliente, buscar solo lo que está después
        if (!empty($nombreCliente)) {
            // Buscar la posición del nombre del cliente en el texto
            $posCliente = stripos($texto, $nombreCliente);
            if ($posCliente !== false) {
                // Buscar desde después del nombre del cliente
                $textoDespuesCliente = substr($texto, $posCliente + strlen($nombreCliente));
                
                // Buscar nombre de empresa después del nombre del cliente
                if (preg_match('/([A-ZÁÉÍÓÚÑ][A-Za-záéíóúñÁÉÍÓÚÑ\s]+,\s*S\.?[AL]\.?)\s*\(([^)]+)\)/iu', $textoDespuesCliente, $matches)) {
                    $nombreBase = trim($matches[1]); // "MOTOR ARI, S.A."
                    $contenidoParentesis = trim($matches[2]); // "JUAN DOMINGUEZ PEREZ, 21"
                    // Guardar nombre sin paréntesis
                    $datos['nombre'] = trim($nombreBase);
                    // Guardar contenido entre paréntesis como domicilio (sin paréntesis)
                    $datos['domicilio'] = $contenidoParentesis;
                    // Abreviatura: primera palabra del nombre
                    $palabras = explode(' ', $nombreBase);
                    $datos['abreviatura'] = strtoupper(substr($palabras[0], 0, 5)); // "MOTOR"
                }
            }
        }
        
        // Si no encontramos con el nombre del cliente, buscar con el patrón original
        if ($datos['nombre'] === 'Empresa Renault') {
            // RENAULT/DACIA: Buscar nombre de empresa después de "ESTABLECIMIENTO"
            // Formato: "MOTOR ARI, S.A.(JUAN DOMINGUEZ PEREZ, 21)"
            if (preg_match('/ESTABLECIMIENTO[^\n]*\n[^\n]*?([A-ZÁÉÍÓÚÑ][A-Za-záéíóúñÁÉÍÓÚÑ\s]+,\s*S\.?[AL]\.?)\s*\(([^)]+)\)/iu', $texto, $matches)) {
                $nombreBase = trim($matches[1]); // "MOTOR ARI, S.A."
                $contenidoParentesis = trim($matches[2]); // "JUAN DOMINGUEZ PEREZ, 21"
                // Guardar nombre sin paréntesis
                $datos['nombre'] = trim($nombreBase);
                // Guardar contenido entre paréntesis como domicilio (sin paréntesis)
                if (empty($datos['domicilio'])) {
                    $datos['domicilio'] = $contenidoParentesis;
                }
                // Abreviatura: primera palabra del nombre
                $palabras = explode(' ', $nombreBase);
                $datos['abreviatura'] = strtoupper(substr($palabras[0], 0, 5)); // "MOTOR"
            }
            // Patrón alternativo sin "ESTABLECIMIENTO"
            elseif (preg_match('/([A-ZÁÉÍÓÚÑ][A-Za-záéíóúñÁÉÍÓÚÑ\s]+,\s*S\.?[AL]\.?)\s*\(([^)]+)\)/iu', $texto, $matches)) {
                $nombreBase = trim($matches[1]);
                $contenidoParentesis = trim($matches[2]);
                // Guardar nombre sin paréntesis
                $datos['nombre'] = trim($nombreBase);
                // Guardar contenido entre paréntesis como domicilio (sin paréntesis)
                if (empty($datos['domicilio'])) {
                    $datos['domicilio'] = $contenidoParentesis;
                }
                $palabras = explode(' ', $nombreBase);
                $datos['abreviatura'] = strtoupper(substr($palabras[0], 0, 5));
            }
        }

        // Buscar CIF (A + 8 números) - después del nombre de empresa
        // Ejemplo: "A35036243"
        if (preg_match('/([A-ZÁÉÍÓÚÑ][A-Za-záéíóúñÁÉÍÓÚÑ\s]+,\s*S\.?[AL]\.?)\s*\([^)]+\)\s*\n\s*([A-Z]\d{8})/u', $texto, $matches)) {
            $datos['cif'] = $matches[2];
        } elseif (preg_match('/\b([A-Z]\d{8})\b/', $texto, $matches)) {
            $datos['cif'] = $matches[1];
        }

        // Buscar teléfono de empresa (después de Tel.:)
        // Ejemplo: "Tel.: 928488950"
        if (preg_match('/Tel\.?[:\s]*(\d{9})/i', $texto, $matches)) {
            $datos['telefono'] = $matches[1];
        }

        // Buscar código postal de empresa (segundo CP - el de 35008)
        if (preg_match_all('/\((\d{5})\)/', $texto, $matches)) {
            if (count($matches[1]) >= 2) {
                $datos['codigo_postal'] = $matches[1][1]; // Segundo código postal
            }
        }

        // Buscar domicilio completo de empresa
        // Formato: "JUAN DOMINGUEZ PEREZ, 21\nLas Palmas De Gran Canaria, Las Palmas (35008)"
        // O: "JUAN DOMINGUEZ PEREZ, 21 Las Palmas De Gran Canaria, Las Palmas"
        if (preg_match('/([A-ZÁÉÍÓÚÑ][A-ZÁÉÍÓÚÑ\s]+,\s*\d+)\s*\n?\s*([A-Za-záéíóúñÁÉÍÓÚÑ\s]+,\s*[A-Za-záéíóúñÁÉÍÓÚÑ\s]+)\s*\((\d{5})\)/u', $texto, $matches)) {
            if ($matches[3] === $datos['codigo_postal']) {
                $datos['domicilio'] = trim($matches[1]) . ' ' . trim($matches[2]);
            }
        } elseif (preg_match('/([A-ZÁÉÍÓÚÑ][A-ZÁÉÍÓÚÑ\s]+,\s*\d+)\s+([A-Za-záéíóúñÁÉÍÓÚÑ\s]+,\s*[A-Za-záéíóúñÁÉÍÓÚÑ\s]+)/u', $texto, $matches)) {
            $datos['domicilio'] = trim($matches[1]) . ' ' . trim($matches[2]);
        }

        return $datos;
    }

    private function extraerClienteRenault(string $texto): array
    {
        $datos = [
            'nombre' => 'Cliente',
            'apellidos' => 'PDF',
            'nombre_completo' => '',
            'dni' => null,
            'email' => '',
            'telefono' => '',
            'domicilio' => '',
            'codigo_postal' => '00000',
        ];

        $nombreCompleto = '';

        // PRIORIDAD 1: Buscar "Sr. Don" seguido de nombre y múltiples espacios antes de empresa
        // Este patrón tiene prioridad porque detecta el caso específico con múltiples espacios
        // Ejemplo: "Sr. Don Jose Verdugo Rodriguez                                                             MOTOR ARI, S.A."
        // Captura todas las palabras después de "Don" hasta encontrar 4 o más espacios seguidos de una palabra en mayúsculas
        if (preg_match('/Sr\.?\s+Don\s+((?:[A-Za-záéíóúñÁÉÍÓÚÑ]+\s+){1,}[A-Za-záéíóúñÁÉÍÓÚÑ]+)\s{4,}[A-Z]{2,}/iu', $texto, $matches)) {
            $nombreCapturado = trim($matches[1]);
            \Illuminate\Support\Facades\Log::info('Renault - Captura inicial (patrón múltiples espacios): ' . $nombreCapturado);
            
            // Verificar que lo capturado tenga al menos 2 palabras y no contenga palabras todas en mayúsculas (que serían empresas)
            $palabrasNombre = preg_split('/\s+/', $nombreCapturado);
            $esNombreValido = true;
            $palabrasLimpias = [];
            
            foreach ($palabrasNombre as $palabra) {
                $palabraTrim = trim($palabra);
                // Si una palabra es toda en mayúsculas y tiene más de 2 letras, probablemente es parte de una empresa
                if (strlen($palabraTrim) > 2 && ctype_upper($palabraTrim)) {
                    $esNombreValido = false;
                    \Illuminate\Support\Facades\Log::info('Renault - Palabra descartada (mayúsculas): ' . $palabraTrim);
                    break;
                }
                $palabrasLimpias[] = $palabraTrim;
            }
            
            if ($esNombreValido && count($palabrasLimpias) >= 2) {
                $nombreCompleto = implode(' ', $palabrasLimpias);
                \Illuminate\Support\Facades\Log::info('Renault - Nombre encontrado (patrón múltiples espacios + empresa): ' . $nombreCompleto);
            } else {
                \Illuminate\Support\Facades\Log::warning('Renault - Nombre descartado. Válido: ' . ($esNombreValido ? 'Sí' : 'No') . ', Palabras: ' . count($palabrasLimpias));
            }
        }
        // PRIORIDAD 2: RENAULT/DACIA: "Sra. Doña Asuncion Sosa"
        // Doña es tratamiento, NO parte del nombre. Nombre = Asuncion, Apellidos = Sosa
        elseif (preg_match('/Sra\.?\s+Do[ñn]a\s+([A-Za-záéíóúñÁÉÍÓÚÑ]+)\s+([A-Za-záéíóúñÁÉÍÓÚÑ]+?)(?=\s*\n|Santa|Calle|C\/|\d{9}|,)/iu', $texto, $matches)) {
            $nombreCompleto = trim($matches[1]) . ' ' . trim($matches[2]);
            \Illuminate\Support\Facades\Log::info('Renault - Nombre encontrado (patrón Sra. Doña): ' . $nombreCompleto);
        }
        // PRIORIDAD 3: "Sr. Don Nombre Apellido" (sin múltiples espacios)
        elseif (preg_match('/Sr\.?\s+Don\s+([A-Za-záéíóúñÁÉÍÓÚÑ]+)\s+([A-Za-záéíóúñÁÉÍÓÚÑ]+?)(?=\s*\n|Santa|Calle|C\/|\d{9}|,)/iu', $texto, $matches)) {
            $nombreCompleto = trim($matches[1]) . ' ' . trim($matches[2]);
            \Illuminate\Support\Facades\Log::info('Renault - Nombre encontrado (patrón Sr. Don): ' . $nombreCompleto);
        }
        // PRIORIDAD 4: Solo "Sr./Sra. Nombre Apellidos" (sin Don/Doña)
        elseif (preg_match('/(?:Sra?\.?|Sr\.?)\s+([A-Za-záéíóúñÁÉÍÓÚÑ]+)\s+([A-Za-záéíóúñÁÉÍÓÚÑ\s]+?)(?=\s*\n|Santa|Calle|C\/|\d{9}|,)/iu', $texto, $matches)) {
            $nombreCompleto = trim($matches[1]) . ' ' . trim($matches[2]);
            \Illuminate\Support\Facades\Log::info('Renault - Nombre encontrado (patrón Sr./Sra.): ' . $nombreCompleto);
        }
        // PRIORIDAD 5: Patrón genérico - buscar cualquier línea que empiece con Sr./Sra. seguido de nombre
        // Este patrón es más flexible y captura el nombre completo incluyendo tratamientos
        elseif (preg_match('/(?:Sra?\.?|Sr\.?)\s+([A-Za-záéíóúñÁÉÍÓÚÑ\s]+?)(?=\s*\n|Santa|Calle|C\/|\d{9}|,|MOTOR|ESTABLECIMIENTO)/iu', $texto, $matches)) {
            $nombreCompleto = trim($matches[1]);
            \Illuminate\Support\Facades\Log::info('Renault - Nombre encontrado (patrón genérico): ' . $nombreCompleto);
        }
        
        // Si no encontramos nada, log para debug
        if (empty($nombreCompleto)) {
            \Illuminate\Support\Facades\Log::warning('Renault - No se encontró nombre del cliente. Primeras 500 caracteres del texto: ' . substr($texto, 0, 500));
        }

        // Si encontramos un nombre, limpiarlo y procesarlo
        if (!empty($nombreCompleto)) {
            // Eliminar tratamientos (Sr, Sra, Don, Doña) antes de guardar
            $nombreCompleto = preg_replace('/\b(Sr|Sra|Don|Do[ñn]a)\.?\s*/iu', '', $nombreCompleto);
            $nombreCompleto = trim($nombreCompleto);
            
            // Si después de limpiar tratamientos el nombre está vacío, intentar buscar de nuevo sin tratamientos
            if (empty($nombreCompleto)) {
                // Buscar nombre sin tratamientos al inicio
                if (preg_match('/^[A-Za-záéíóúñÁÉÍÓÚÑ]+\s+[A-Za-záéíóúñÁÉÍÓÚÑ]+(?=\s*\n|Santa|Calle|C\/|\d{9}|,|MOTOR|ESTABLECIMIENTO)/iu', $texto, $matches)) {
                    $nombreCompleto = trim($matches[0]);
                }
            }
            
            // Si aún tenemos nombre, procesarlo
            if (!empty($nombreCompleto)) {
                // Separar en nombre y apellidos según el número de palabras
                $palabras = preg_split('/\s+/', $nombreCompleto);
                $palabras = array_filter($palabras);
                $palabras = array_values($palabras);
                
                $numPalabras = count($palabras);
                
                if ($numPalabras === 2) {
                    // 2 palabras: 1 nombre y 1 apellido
                    $datos['nombre'] = $palabras[0];
                    $datos['apellidos'] = $palabras[1];
                    $datos['nombre_completo'] = $nombreCompleto;
                } elseif ($numPalabras === 3) {
                    // 3 palabras: 1 nombre y 2 apellidos
                    $datos['nombre'] = $palabras[0];
                    $datos['apellidos'] = $palabras[1] . ' ' . $palabras[2];
                    $datos['nombre_completo'] = $nombreCompleto;
                } elseif ($numPalabras === 4) {
                    // 4 palabras: 2 nombres y 2 apellidos
                    $datos['nombre'] = $palabras[0] . ' ' . $palabras[1];
                    $datos['apellidos'] = $palabras[2] . ' ' . $palabras[3];
                    $datos['nombre_completo'] = $nombreCompleto;
                } elseif ($numPalabras >= 5) {
                    // 5 o más palabras: 2 nombres y el resto apellidos
                    $datos['nombre'] = $palabras[0] . ' ' . $palabras[1];
                    $datos['apellidos'] = implode(' ', array_slice($palabras, 2));
                    $datos['nombre_completo'] = $nombreCompleto;
                } elseif ($numPalabras === 1) {
                    $datos['nombre'] = $palabras[0];
                    $datos['apellidos'] = '';
                    $datos['nombre_completo'] = $nombreCompleto;
                }
            }
        }

        // Buscar email del cliente (gmail, hotmail, etc. - correo personal)
        if (preg_match('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i', $texto, $matches)) {
            $datos['email'] = strtolower(trim($matches[1]));
        }

        // Buscar teléfono móvil del cliente (6 o 7 al principio = móvil particular)
        if (preg_match('/\b([67]\d{8})\b/', $texto, $matches)) {
            $datos['telefono'] = $matches[1];
        }

        // Buscar código postal del cliente (primer código postal entre paréntesis - 35450)
        if (preg_match('/\((\d{5})\)/', $texto, $matches)) {
            $datos['codigo_postal'] = $matches[1];
        }

        // Buscar domicilio del cliente: "Santa Maria De Guia De Gran C., Las Palmas"
        if (preg_match('/(Santa\s+Maria[^(]+?),?\s*Las\s+Palmas\s*\((\d{5})\)/iu', $texto, $matches)) {
            $datos['domicilio'] = trim($matches[1]) . ', Las Palmas';
            $datos['codigo_postal'] = $matches[2];
        } elseif (preg_match('/(Santa\s+Maria[^(]+?)\s*\((\d{5})\)/iu', $texto, $matches)) {
            $datos['domicilio'] = trim(rtrim($matches[1], ','));
            $datos['codigo_postal'] = $matches[2];
        }

        return $datos;
    }

    private function extraerLineasRenault(string $texto): array
    {
        $lineas = [];
        
        // Normalizar texto
        $texto = str_replace("\r\n", "\n", $texto);
        
        // Extraer bloque RESUMEN - buscar todo después de "RESUMEN" hasta "CONDICIONES" o fin
        $bloqueResumen = '';
        if (preg_match('/RESUMEN\s*\n(.*?)(?=\n\s*CONDICIONES|$)/is', $texto, $match)) {
            $bloqueResumen = $match[1];
            \Illuminate\Support\Facades\Log::info('Bloque RESUMEN encontrado (patrón 1)');
        } else {
            // Si no encuentra "RESUMEN", buscar desde "RESUMEN" hasta "TOTAL A PAGAR" o "CONDICIONES"
            if (preg_match('/RESUMEN\s*\n(.*?)(?=\n\s*TOTAL\s+A\s+PAGAR|CONDICIONES|$)/is', $texto, $match)) {
                $bloqueResumen = $match[1];
                \Illuminate\Support\Facades\Log::info('Bloque RESUMEN encontrado (patrón 2)');
            } else {
                \Illuminate\Support\Facades\Log::warning('Bloque RESUMEN NO encontrado, usando todo el texto');
            }
        }
        
        // Si no encontramos bloque RESUMEN, usar todo el texto
        if (empty($bloqueResumen)) {
            $bloqueResumen = $texto;
        }
        
        \Illuminate\Support\Facades\Log::info('Tamaño del bloque RESUMEN: ' . strlen($bloqueResumen) . ' caracteres');
        \Illuminate\Support\Facades\Log::info('Primeros 500 caracteres del bloque: ' . substr($bloqueResumen, 0, 500));
        
        // Buscar todas las líneas que contengan precio con "€" después de RESUMEN
        $lineasTexto = explode("\n", $bloqueResumen);
        $tipoAnterior = '';
        
        // Log para debug
        \Illuminate\Support\Facades\Log::info('=== INICIO EXTRACCIÓN LÍNEAS RENAULT ===');
        \Illuminate\Support\Facades\Log::info('Total líneas en bloque RESUMEN: ' . count($lineasTexto));
        
        foreach ($lineasTexto as $indice => $lineaOriginal) {
            // Verificar si la línea contiene precio con €
            if (!preg_match('/€/iu', $lineaOriginal)) {
                continue;
            }
            
            // Contar espacios al principio de la línea
            $espaciosInicio = 0;
            if (preg_match('/^(\s+)/', $lineaOriginal, $match)) {
                $espaciosInicio = strlen($match[1]);
            }
            
            // Si tiene solo 1 espacio al principio → hay tipo
            // Si tiene más de 2 espacios al principio → no hay tipo (usar el anterior)
            $tieneTipo = ($espaciosInicio <= 1);
            
            $lineaTexto = trim($lineaOriginal);
            
            if (empty($lineaTexto)) {
                continue;
            }
            
            $tipo = '';
            $descripcion = '';
            $precio = '';
            
            // Extraer precio (siempre al final, antes de €)
            if (preg_match('/(-?\d{1,3}(?:\.\d{3})*,\d{2})\s*€/iu', $lineaTexto, $precioMatch)) {
                $precio = trim($precioMatch[1]);
            }
            
            if (empty($precio)) {
                continue;
            }
            
            // Si la línea tiene tipo (1 espacio o menos al principio)
            if ($tieneTipo) {
                // Eliminar el precio de la línea para procesar tipo y descripción
                $lineaSinPrecio = preg_replace('/\s+-?\d{1,3}(?:\.\d{3})*,\d{2}\s*€.*$/iu', '', $lineaTexto);
                $lineaSinPrecio = trim($lineaSinPrecio);
                
                // Buscar tipo y descripción
                // Patrón 1: Formato con pipes "Tipo | Descripción |"
                if (preg_match('/^([^|]+?)\s*\|\s*(.+?)\s*\|/iu', $lineaSinPrecio, $match)) {
                    $tipo = trim($match[1]);
                    $descripcion = trim($match[2]);
                }
                // Patrón 2: Formato sin pipes - buscar si hay más de 3 espacios consecutivos
                // Si hay más de 3 espacios, lo que está antes es tipo y lo que está después es descripción
                elseif (preg_match('/^(.+?)\s{4,}(.+)$/u', $lineaSinPrecio, $match)) {
                    $tipo = trim($match[1]);
                    $descripcion = trim($match[2]);
                }
                // Patrón 3: Formato con dos puntos "Tipo: Descripción"
                elseif (preg_match('/^([^:]+?):\s*(.+)$/iu', $lineaSinPrecio, $match)) {
                    $tipo = trim($match[1]);
                    $descripcion = trim($match[2]);
                }
                // Patrón 4: Solo tipo (sin descripción)
                else {
                    $tipo = $lineaSinPrecio;
                    $descripcion = '';
                }
            } else {
                // La línea tiene más de 2 espacios al principio, tipo está vacío (usar el anterior)
                $tipo = $tipoAnterior;
                
                // Buscar descripción (eliminar el precio y los espacios iniciales)
                $lineaSinPrecio = preg_replace('/\s+-?\d{1,3}(?:\.\d{3})*,\d{2}\s*€.*$/iu', '', $lineaTexto);
                $lineaSinPrecio = trim($lineaSinPrecio);
                
                // Patrón 1: Formato con pipes "| Descripción |"
                if (preg_match('/^\|\s*([^|]*?)\s*\|/iu', $lineaSinPrecio, $match)) {
                    $descripcion = trim($match[1]);
                }
                // Patrón 2: Solo descripción
                else {
                    $descripcion = $lineaSinPrecio;
                }
            }
            
            // Procesar tipo
            if (!empty($tipo)) {
                // Limpiar el tipo: quitar ":" al final
                $tipo = preg_replace('/:\s*$/', '', $tipo);
                
                // Eliminar todo desde el primer "(" hasta el final (incluso si no hay cierre)
                // Esto maneja casos donde el texto está cortado y no se ve el paréntesis de cierre
                $posApertura = strpos($tipo, '(');
                if ($posApertura !== false) {
                    // Eliminar desde el "(" hasta el final del string
                    $tipo = substr($tipo, 0, $posApertura);
                }
                
                // Limpiar espacios múltiples que puedan quedar
                $tipo = preg_replace('/\s+/', ' ', $tipo);
                // Cambiar "TOTAL A PAGAR" a "TOTAL"
                $tipo = preg_replace('/^TOTAL\s+A\s+PAGAR$/iu', 'TOTAL', $tipo);
                $tipo = trim($tipo);
                
                // Actualizar tipoAnterior solo si el tipo no está vacío
                if (!empty($tipo)) {
                    $tipoAnterior = $tipo;
                }
            } else {
                // Si no hay tipo, usar el anterior
                $tipo = $tipoAnterior;
            }
            
            // Si la descripción está vacía, poner "Sin descripción"
            if (empty($descripcion)) {
                $descripcion = 'Sin descripción';
            }
            
            // Limpiar descripción de Color (quitar código numérico al final)
            if (stripos($tipo, 'Color') !== false) {
                $descripcion = preg_replace('/\s+\d{3,4}\s*$/', '', $descripcion);
            }
            
            // Limpiar descripción de Tapicería (quitar prefijo duplicado)
            if (stripos($tipo, 'Tapicería') !== false) {
                $descripcion = preg_replace('/^Tapicería\s+/i', '', $descripcion);
            }
            
            // Log para debug
            \Illuminate\Support\Facades\Log::info("Línea $indice - Tipo: '$tipo', Descripción: '$descripcion', Precio: '$precio'");
            
            // Solo agregar si tenemos un tipo válido
            // Si no hay tipo pero hay precio, usar tipo por defecto "Sin tipo"
            if (empty($tipo) && !empty($precio)) {
                $tipo = 'Sin tipo';
                \Illuminate\Support\Facades\Log::warning("Línea $indice sin tipo, usando 'Sin tipo' por defecto. Línea original: " . substr($lineaOriginal, 0, 100));
            }
            
            if (!empty($tipo) && !empty($precio)) {
                $lineas[] = [
                    'tipo' => $tipo,
                    'descripcion' => $descripcion,
                    'precio' => $this->convertirPrecio($precio),
                ];
            } else {
                \Illuminate\Support\Facades\Log::warning("Línea $indice descartada: tipo o precio vacío. Tipo: '$tipo', Precio: '$precio'. Línea original: " . substr($lineaOriginal, 0, 100));
            }
        }
        
        \Illuminate\Support\Facades\Log::info('Total líneas extraídas: ' . count($lineas));
        \Illuminate\Support\Facades\Log::info('=== FIN EXTRACCIÓN LÍNEAS RENAULT ===');
        
        // Eliminar filas TOTAL duplicadas idénticas (mantener solo la primera)
        $lineasFinales = [];
        $totalAnterior = null;
        foreach ($lineas as $linea) {
            if ($linea['tipo'] === 'TOTAL') {
                // Si es la primera vez que encontramos TOTAL, guardarlo
                if ($totalAnterior === null) {
                    $lineasFinales[] = $linea;
                    $totalAnterior = $linea;
                } else {
                    // Si es idéntico al anterior (mismo tipo, descripción y precio), no agregarlo
                    if ($linea['tipo'] === $totalAnterior['tipo'] && 
                        $linea['descripcion'] === $totalAnterior['descripcion'] && 
                        $linea['precio'] === $totalAnterior['precio']) {
                        // Es un duplicado idéntico, no agregar
                        continue;
                    } else {
                        // Es un TOTAL diferente, agregarlo
                        $lineasFinales[] = $linea;
                        $totalAnterior = $linea;
                    }
                }
            } else {
                $lineasFinales[] = $linea;
            }
        }
        
        return $lineasFinales;
    }

    private function extraerVehiculoRenault(array $lineas, string $chasis, int $empresaId): array
    {
        $modelo = '';
        $version = '';
        $colorExterno = 'No especificado';
        $colorInterno = 'No especificado';

        // Buscar modelo en líneas
        foreach ($lineas as $linea) {
            if ($linea['tipo'] === 'Modelo') {
                $descripcion = $linea['descripcion'];
                $palabras = explode(' ', $descripcion);
                
                // Determinar cuántas palabras tomar para el modelo
                $modeloPalabras = [];
                $i = 0;
                foreach ($palabras as $palabra) {
                    $palabraUpper = strtoupper($palabra);
                    // Si es DACIA, RENAULT, Nuevo, etc., incluir en modelo
                    if (in_array($palabraUpper, ['DACIA', 'RENAULT', 'NUEVO', 'NUEVA']) || $i < 2) {
                        $modeloPalabras[] = $palabra;
                        $i++;
                    } else {
                        break;
                    }
                }
                
                $modelo = implode(' ', $modeloPalabras);
                $version = implode(' ', array_slice($palabras, count($modeloPalabras)));
                break;
            }
        }

        // Buscar color externo
        foreach ($lineas as $linea) {
            if ($linea['tipo'] === 'Color') {
                $colorExterno = $linea['descripcion'];
                break;
            }
        }

        // Buscar color interno (tapicería)
        foreach ($lineas as $linea) {
            if ($linea['tipo'] === 'Tapicería') {
                // Extraer solo las primeras palabras (antes de "Oferta Promocional", etc.)
                $descripcion = $linea['descripcion'];
                // Buscar hasta encontrar palabras clave que indican texto adicional
                if (preg_match('/^([^O]+?)(?=\s+Oferta|$)/u', $descripcion, $matches)) {
                    $colorInterno = trim($matches[1]);
                } else {
                    $colorInterno = $descripcion;
                }
                break;
            }
        }

        return [
            'chasis' => $chasis,
            'modelo' => $modelo ?: 'Renault/Dacia',
            'version' => $version ?: 'Sin especificar',
            'color_externo' => $colorExterno,
            'color_interno' => $colorInterno,
            'empresa_id' => $empresaId,
        ];
    }

    private function extraerFechaRenault(string $texto): Carbon
    {
        // Buscar "Martes, 10 Junio 2025" o similar
        $meses = [
            'enero' => '01', 'febrero' => '02', 'marzo' => '03', 'abril' => '04',
            'mayo' => '05', 'junio' => '06', 'julio' => '07', 'agosto' => '08',
            'septiembre' => '09', 'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12'
        ];

        if (preg_match('/(\d{1,2})\s+(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)\s+(\d{4})/iu', $texto, $matches)) {
            $dia = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $mes = $meses[strtolower($matches[2])] ?? '01';
            $anio = $matches[3];
            try {
                return Carbon::createFromFormat('d/m/Y', "{$dia}/{$mes}/{$anio}");
            } catch (\Exception $e) {
                // Continuar si falla
            }
        }

        return now();
    }

    // ==================== MÉTODOS COMUNES ====================

    private function buscarChasis(string $texto): ?string
    {
        // Buscar "Bastidor" seguido de 17 caracteres
        if (preg_match('/Bastidor[:\s]*([A-Z0-9]{17})/i', $texto, $matches)) {
            return strtoupper($matches[1]);
        }
        
        // Buscar cualquier secuencia de 17 caracteres alfanuméricos que parezca un VIN
        if (preg_match('/\b([A-HJ-NPR-Z0-9]{17})\b/', $texto, $matches)) {
            return strtoupper($matches[1]);
        }

        return null;
    }

    private function generarAbreviatura(string $nombre): string
    {
        $palabras = explode(' ', $nombre);
        if (count($palabras) >= 2) {
            // Si hay espacio, tomar 3 primeras letras
            return strtoupper(substr($palabras[0], 0, 3));
        }
        // Sin espacio, tomar 5 primeras letras
        return strtoupper(substr($nombre, 0, 5));
    }

    private function crearEmpresa(array $datos): Empresa
    {
        // Buscar empresa existente por CIF
        if (!empty($datos['cif'])) {
            $empresa = Empresa::where('cif', $datos['cif'])->first();
            if ($empresa) {
                return $empresa;
            }
        }

        // Crear nueva empresa
        return Empresa::create([
            'nombre' => $datos['nombre'],
            'abreviatura' => $datos['abreviatura'],
            'cif' => $datos['cif'] ?: 'CIF-' . uniqid(),
            'domicilio' => $datos['domicilio'] ?: 'Extraído de PDF',
            'codigo_postal' => $datos['codigo_postal'] ?: null,
            'telefono' => $datos['telefono'] ?: '000000000',
        ]);
    }

    private function crearCliente(array $datos, int $empresaId): Cliente
    {
        // Buscar cliente existente por email
        if (!empty($datos['email'])) {
            $cliente = Cliente::where('email', $datos['email'])->first();
            if ($cliente) {
                return $cliente;
            }
        }

        // Crear nuevo cliente
        return Cliente::create([
            'nombre' => $datos['nombre'],
            'apellidos' => $datos['apellidos'],
            'empresa_id' => $empresaId,
            'dni' => $datos['dni'] ?? null,
            'email' => $datos['email'] ?: 'pdf-' . uniqid() . '@extraido.local',
            'telefono' => $datos['telefono'] ?: '000000000',
            'domicilio' => $datos['domicilio'] ?: 'Extraído de PDF',
            'codigo_postal' => $datos['codigo_postal'] ?: '00000',
        ]);
    }

    private function crearVehiculo(array $datos): Vehiculo
    {
        // Buscar vehículo existente por chasis
        $vehiculo = Vehiculo::where('chasis', $datos['chasis'])->first();
        if ($vehiculo) {
            return $vehiculo;
        }

        // Crear nuevo vehículo
        return Vehiculo::create($datos);
    }

    private function crearLineasOferta(int $ofertaCabeceraId, array $lineas): void
    {
        \Illuminate\Support\Facades\Log::info('=== CREAR LÍNEAS OFERTA ===');
        \Illuminate\Support\Facades\Log::info('Oferta ID: ' . $ofertaCabeceraId);
        \Illuminate\Support\Facades\Log::info('Total líneas a guardar: ' . count($lineas));
        
        foreach ($lineas as $indice => $linea) {
            \Illuminate\Support\Facades\Log::info("Guardando línea $indice: " . json_encode($linea));
            try {
                OfertaLinea::create([
                    'oferta_cabecera_id' => $ofertaCabeceraId,
                    'tipo' => $linea['tipo'],
                    'descripcion' => $linea['descripcion'],
                    'precio' => $linea['precio'],
                ]);
                \Illuminate\Support\Facades\Log::info("Línea $indice guardada correctamente");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al guardar línea $indice: " . $e->getMessage());
                \Illuminate\Support\Facades\Log::error("Datos de la línea: " . json_encode($linea));
            }
        }
        
        \Illuminate\Support\Facades\Log::info('=== FIN CREAR LÍNEAS OFERTA ===');
    }

    private function calcularYActualizarTotales(OfertaCabecera $oferta): void
    {
        $lineas = $oferta->lineas;
        
        $baseImponible = 0;
        $impuestos = 0;
        $totalSinImpuestos = 0;

        foreach ($lineas as $linea) {
            $tipo = strtolower($linea->tipo);
            
            if (str_contains($tipo, 'igic') || str_contains($tipo, 'impuesto')) {
                $impuestos += $linea->precio;
            } else {
                $baseImponible += $linea->precio;
            }
        }

        $totalSinImpuestos = $baseImponible;
        $totalConImpuestos = $baseImponible + $impuestos;

        $oferta->update([
            'base_imponible' => $baseImponible,
            'impuestos' => $impuestos,
            'total_sin_impuestos' => $totalSinImpuestos,
            'total_con_impuestos' => $totalConImpuestos,
        ]);
    }

    private function convertirPrecio(string $precioTexto): float
    {
        $esNegativo = str_contains($precioTexto, '-');
        $precioTexto = str_replace(['-', ' ', '€'], '', $precioTexto);
        
        // Formato español: 1.234,56
        if (str_contains($precioTexto, ',')) {
            $precioTexto = str_replace('.', '', $precioTexto);
            $precioTexto = str_replace(',', '.', $precioTexto);
        }
        
        $precio = (float) $precioTexto;
        return $esNegativo ? -$precio : $precio;
    }

    /**
     * Eliminar una oferta y su PDF
     */
    public function eliminarOferta(OfertaCabecera $oferta): void
    {
        if ($oferta->pdf_path && Storage::disk('public')->exists($oferta->pdf_path)) {
            Storage::disk('public')->delete($oferta->pdf_path);
        }
        
        $oferta->delete();
    }
}
