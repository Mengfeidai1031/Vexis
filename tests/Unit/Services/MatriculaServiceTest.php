<?php

namespace Tests\Unit\Services;

use App\Services\MatriculaService;
use Tests\TestCase;

class MatriculaServiceTest extends TestCase
{
    public function test_generar_siguiente_returns_string(): void
    {
        $svc = new MatriculaService;
        $matricula = $svc->generarSiguiente();
        $this->assertIsString($matricula);
    }

    public function test_generar_siguiente_matches_spanish_plate_format(): void
    {
        $matricula = (new MatriculaService)->generarSiguiente();
        // 4 dígitos + espacio + 3 consonantes (excluye vocales y Q/Ñ)
        $this->assertMatchesRegularExpression('/^\d{4} [BCDFGHJKLMNPRSTVWXYZ]{3}$/', $matricula);
    }

    public function test_validar_formato_accepts_valid_plate(): void
    {
        $this->assertTrue(MatriculaService::validarFormato('1234 BCD'));
        $this->assertTrue(MatriculaService::validarFormato('0001 ZZZ'));
        $this->assertTrue(MatriculaService::validarFormato('9999BCD')); // sin espacio
    }

    public function test_validar_formato_rejects_invalid(): void
    {
        $this->assertFalse(MatriculaService::validarFormato('1234 AEI')); // vocales prohibidas
        $this->assertFalse(MatriculaService::validarFormato('123 BCD'));  // pocos dígitos
        $this->assertFalse(MatriculaService::validarFormato('ABCD 123')); // orden inverso
    }
}
