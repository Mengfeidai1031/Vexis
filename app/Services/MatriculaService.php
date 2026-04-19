<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vehiculo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MatriculaService
{
    private const LETRAS = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'];

    /**
     * Genera la siguiente matrícula disponible.
     * Prioridad: la más alta entre la API DGT (tiempo real) y la BD local.
     */
    public function generarSiguiente(): string
    {
        $candidatas = [];

        // 1. Última matrícula desde la DGT (API en tiempo real, cacheada 1 hora)
        $ultimaDgt = $this->obtenerUltimaMatriculaDgt();
        if ($ultimaDgt) {
            $candidatas[] = $ultimaDgt;
        }

        // 2. Última matrícula en nuestra BD
        $ultimaBd = $this->obtenerUltimaMatriculaBd();
        if ($ultimaBd) {
            $candidatas[] = $ultimaBd;
        }

        if (empty($candidatas)) {
            return $this->estimarMatriculaActual();
        }

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
            ->orderByRaw('SUBSTRING(matricula, -3) DESC, CAST(SUBSTRING(matricula, 1, 4) AS UNSIGNED) DESC')
            ->first();

        return $vehiculo?->matricula;
    }

    /**
     * Consulta la API de la DGT en tiempo real para obtener la última matrícula emitida.
     * Cachea el resultado 1 hora para no saturar la API.
     */
    public function obtenerUltimaMatriculaDgt(): ?string
    {
        return Cache::remember('dgt_ultima_matricula', 3600, function () {
            try {
                $response = Http::timeout(5)->get('https://sedeapl.dgt.gob.es/WEB_IEST_CONSULTA/lastPlate.json');

                if ($response->successful()) {
                    $data = $response->json();
                    if (! empty($data['plate'])) {
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
     * Referencia: Enero 2024 ≈ 0000 MLL
     */
    private function estimarMatriculaActual(): string
    {
        $refYear = 2024;
        $refMonth = 1;
        $refLetterIndex = 10 * 400 + 8 * 20 + 8; // MLL = 4168

        $now = now();
        $mesesDesdeRef = (($now->year - $refYear) * 12) + ($now->month - $refMonth);

        $comboActual = $refLetterIndex + ($mesesDesdeRef * 10);

        $totalLetras = count(self::LETRAS);
        $l1 = min((int) floor($comboActual / ($totalLetras * $totalLetras)), $totalLetras - 1);
        $l2 = min((int) floor(($comboActual % ($totalLetras * $totalLetras)) / $totalLetras), $totalLetras - 1);
        $l3 = min($comboActual % $totalLetras, $totalLetras - 1);

        $letras = self::LETRAS[$l1].self::LETRAS[$l2].self::LETRAS[$l3];

        return "0000 {$letras}";
    }

    private function obtenerMayor(array $matriculas): string
    {
        usort($matriculas, function ($a, $b) {
            return $this->matriculaToIndex($a) <=> $this->matriculaToIndex($b);
        });

        return end($matriculas);
    }

    private function matriculaToIndex(string $matricula): int
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));
        if (strlen($clean) < 7) {
            return 0;
        }

        $numeros = (int) substr($clean, 0, 4);
        $letras = substr($clean, -3);

        $l1 = array_search($letras[0], self::LETRAS);
        $l2 = array_search($letras[1], self::LETRAS);
        $l3 = array_search($letras[2], self::LETRAS);

        if ($l1 === false || $l2 === false || $l3 === false) {
            return 0;
        }

        $totalLetras = count(self::LETRAS);

        return ($l1 * $totalLetras * $totalLetras + $l2 * $totalLetras + $l3) * 10000 + $numeros;
    }

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
        $let = self::LETRAS[$l1].self::LETRAS[$l2].self::LETRAS[$l3];

        return "{$num} {$let}";
    }

    public static function validarFormato(string $matricula): bool
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));

        return (bool) preg_match('/^\d{4}[BCDFGHJKLMNPRSTVWXYZ]{3}$/', $clean);
    }

    public static function formatear(string $matricula): string
    {
        $clean = str_replace(' ', '', strtoupper(trim($matricula)));
        if (strlen($clean) === 7) {
            return substr($clean, 0, 4).' '.substr($clean, 4, 3);
        }

        return strtoupper(trim($matricula));
    }
}
