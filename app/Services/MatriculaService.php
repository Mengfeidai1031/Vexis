<?php

namespace App\Services;

use App\Models\Vehiculo;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MatriculaService
{
    /**
     * Letras válidas en matrículas españolas (sin vocales, Ñ ni Q)
     */
    private const LETRAS = ['B','C','D','F','G','H','J','K','L','M','N','P','R','S','T','V','W','X','Y','Z'];

    /**
     * Genera la siguiente matrícula disponible.
     * Usa la más alta entre: BD local, dato DGT (API/cache) y setting manual.
     */
    public function generarSiguiente(): string
    {
        $candidatas = [];

        // 1. Última matrícula en nuestra BD
        $ultimaBd = $this->obtenerUltimaMatriculaBd();
        if ($ultimaBd) {
            $candidatas[] = $ultimaBd;
        }

        // 2. Última matrícula desde la DGT (cacheada)
        $ultimaDgt = $this->obtenerUltimaMatriculaDgt();
        if ($ultimaDgt) {
            $candidatas[] = $ultimaDgt;
        }

        // 3. Setting manual configurado por admin
        $ultimaSetting = Setting::get('ultima_matricula_dgt', null);
        if ($ultimaSetting && self::validarFormato($ultimaSetting)) {
            $candidatas[] = self::formatear($ultimaSetting);
        }

        if (empty($candidatas)) {
            // Fallback: estimación basada en la fecha actual
            // En 2024 se iba por ~MLx, se emiten ~150k matrículas/mes
            // Cada combinación de letras = 10.000 placas
            return $this->estimarMatriculaActual();
        }

        // Usar la más alta de todas las candidatas
        $mayor = $this->obtenerMayor($candidatas);
        return $this->calcularSiguiente($mayor);
    }

    /**
     * Obtiene la última matrícula registrada en la BD (la más alta).
     */
    public function obtenerUltimaMatriculaBd(): ?string
    {
        $vehiculo = Vehiculo::whereNotNull('matricula')
            ->where('matricula', '!=', '')
            ->orderByRaw("SUBSTRING(matricula, -3) DESC, CAST(SUBSTRING(matricula, 1, 4) AS UNSIGNED) DESC")
            ->first();

        return $vehiculo?->matricula;
    }

    /**
     * Intenta obtener la última matrícula desde datos de la DGT.
     * Consulta datos.gob.es para estadísticas de matriculaciones.
     * Cachea el resultado 24 horas.
     */
    public function obtenerUltimaMatriculaDgt(): ?string
    {
        return Cache::remember('dgt_ultima_matricula', 86400, function () {
            try {
                // API de datos abiertos - matriculaciones de vehículos
                $response = Http::timeout(5)->get('https://sedeapl.dgt.gob.es/WEB_IEST_CONSULTA/lastPlate.json');

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['plate'])) {
                        $plate = strtoupper(trim($data['plate']));
                        if (self::validarFormato($plate)) {
                            return self::formatear($plate);
                        }
                    }
                }
            } catch (\Exception $e) {
                // API no disponible - fallback silencioso
            }

            return null;
        });
    }

    /**
     * Estimación de la matrícula actual basada en la fecha.
     * El sistema español se inició en sept. 2000 con 0000 BBB.
     * Ritmo promedio: ~1.2M matrículas/año = ~100k/mes = 10 combinaciones de letras/mes
     */
    private function estimarMatriculaActual(): string
    {
        // Referencia conocida: Enero 2024 ≈ 0000 MLL
        // MLL en índice: M=10, L=8, L=8 → posición = 10*400 + 8*20 + 8 = 4168 combinaciones
        // Cada mes avanza ~10 combinaciones de letras (100k matrículas)
        $refYear = 2024;
        $refMonth = 1;
        $refLetterIndex = 10 * 400 + 8 * 20 + 8; // MLL = 4168

        $now = now();
        $mesesDesdeRef = (($now->year - $refYear) * 12) + ($now->month - $refMonth);

        // ~10 combinaciones de letras por mes
        $comboActual = $refLetterIndex + ($mesesDesdeRef * 10);

        // Convertir índice de combinación a letras
        $totalLetras = count(self::LETRAS); // 20
        $l1 = (int) floor($comboActual / ($totalLetras * $totalLetras));
        $l2 = (int) floor(($comboActual % ($totalLetras * $totalLetras)) / $totalLetras);
        $l3 = $comboActual % $totalLetras;

        // Clamp
        $l1 = min($l1, $totalLetras - 1);
        $l2 = min($l2, $totalLetras - 1);
        $l3 = min($l3, $totalLetras - 1);

        $letras = self::LETRAS[$l1] . self::LETRAS[$l2] . self::LETRAS[$l3];

        return "0000 {$letras}";
    }

    /**
     * De una lista de matrículas, devuelve la más alta.
     */
    private function obtenerMayor(array $matriculas): string
    {
        usort($matriculas, function ($a, $b) {
            return $this->matriculaToIndex($a) <=> $this->matriculaToIndex($b);
        });

        return end($matriculas);
    }

    /**
     * Convierte una matrícula a un índice numérico para comparar.
     */
    private function matriculaToIndex(string $matricula): int
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));
        if (strlen($clean) < 7) return 0;

        $numeros = (int) substr($clean, 0, 4);
        $letras = substr($clean, -3);

        $l1 = array_search($letras[0], self::LETRAS);
        $l2 = array_search($letras[1], self::LETRAS);
        $l3 = array_search($letras[2], self::LETRAS);

        if ($l1 === false || $l2 === false || $l3 === false) return 0;

        $totalLetras = count(self::LETRAS);
        return ($l1 * $totalLetras * $totalLetras + $l2 * $totalLetras + $l3) * 10000 + $numeros;
    }

    /**
     * Calcula la siguiente matrícula a partir de una dada.
     * Formato: 1234 BCD (4 números + espacio + 3 letras)
     */
    public function calcularSiguiente(string $matricula): string
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));

        if (strlen($clean) < 7) {
            return $this->estimarMatriculaActual();
        }

        $numeros = (int) substr($clean, 0, 4);
        $letras = substr($clean, -3);

        $l1 = array_search($letras[0], self::LETRAS);
        $l2 = array_search($letras[1], self::LETRAS);
        $l3 = array_search($letras[2], self::LETRAS);

        if ($l1 === false || $l2 === false || $l3 === false) {
            return $this->estimarMatriculaActual();
        }

        $numeros++;

        if ($numeros > 9999) {
            $numeros = 0;
            $l3++;
            if ($l3 >= count(self::LETRAS)) {
                $l3 = 0;
                $l2++;
                if ($l2 >= count(self::LETRAS)) {
                    $l2 = 0;
                    $l1++;
                    if ($l1 >= count(self::LETRAS)) {
                        $l1 = 0;
                    }
                }
            }
        }

        $num = str_pad($numeros, 4, '0', STR_PAD_LEFT);
        $let = self::LETRAS[$l1] . self::LETRAS[$l2] . self::LETRAS[$l3];

        return "{$num} {$let}";
    }

    /**
     * Valida que una matrícula tenga formato español válido.
     */
    public static function validarFormato(string $matricula): bool
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));
        return (bool) preg_match('/^\d{4}[BCDFGHJKLMNPRSTVWXYZ]{3}$/', $clean);
    }

    /**
     * Formatea una matrícula al formato estándar: 1234 BCD
     */
    public static function formatear(string $matricula): string
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));
        if (strlen($clean) === 7) {
            return substr($clean, 0, 4) . ' ' . substr($clean, 4, 3);
        }
        return strtoupper(trim($matricula));
    }
}
