<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Centro;
use App\Models\CitaTaller;
use App\Models\Cliente;
use App\Models\CocheSustitucion;
use App\Models\Empresa;
use App\Models\Mecanico;
use App\Models\NamingPc;
use App\Models\Reparto;
use App\Models\ReservaSustitucion;
use App\Models\Stock;
use App\Models\Taller;
use App\Models\Tasacion;
use App\Models\User;
use App\Models\Vacacion;
use App\Models\Vehiculo;
use App\Models\Venta;
use Illuminate\Database\Seeder;

class DatosEjemploSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMecanicos();
        $this->seedStocks();
        $this->seedRepartos();
        $this->seedCitas();
        $this->seedCochesSustitucion();
        $this->seedNamingPcs();
        $this->seedVentas();
        $this->seedTasaciones();
        $this->seedVacaciones();
    }

    private function seedMecanicos(): void
    {
        $talleres = Taller::all();
        if ($talleres->isEmpty()) {
            return;
        }

        $mecanicos = [
            ['nombre' => 'Carlos', 'apellidos' => 'García Rodríguez', 'especialidad' => 'Mecánica general'],
            ['nombre' => 'Miguel', 'apellidos' => 'Fernández López', 'especialidad' => 'Electricidad'],
            ['nombre' => 'Antonio', 'apellidos' => 'Hernández Pérez', 'especialidad' => 'Chapa y pintura'],
            ['nombre' => 'José', 'apellidos' => 'Díaz Santana', 'especialidad' => 'Diagnosis electrónica'],
            ['nombre' => 'Pedro', 'apellidos' => 'Martín Suárez', 'especialidad' => 'Mecánica general'],
            ['nombre' => 'Juan', 'apellidos' => 'Cabrera Medina', 'especialidad' => 'Climatización'],
            ['nombre' => 'Francisco', 'apellidos' => 'Alonso Vega', 'especialidad' => 'Neumáticos y frenos'],
            ['nombre' => 'David', 'apellidos' => 'Moreno Cruz', 'especialidad' => 'Transmisiones'],
            ['nombre' => 'Raúl', 'apellidos' => 'Santos Rivero', 'especialidad' => 'Mecánica general'],
            ['nombre' => 'Sergio', 'apellidos' => 'González Tejera', 'especialidad' => 'Diagnosis electrónica'],
            ['nombre' => 'Alejandro', 'apellidos' => 'Navarro Gil', 'especialidad' => 'Electricidad'],
            ['nombre' => 'Fernando', 'apellidos' => 'Ruiz Betancort', 'especialidad' => 'Chapa y pintura'],
        ];

        foreach ($mecanicos as $i => $m) {
            Mecanico::firstOrCreate(
                ['nombre' => $m['nombre'], 'apellidos' => $m['apellidos']],
                [...$m, 'taller_id' => $talleres[$i % $talleres->count()]->id, 'activo' => true]
            );
        }
    }

    private function seedStocks(): void
    {
        $almacenes = Almacen::all();
        if ($almacenes->isEmpty()) {
            return;
        }
        $empresa = Empresa::first();
        $centro = Centro::first();

        $piezas = [
            ['ref' => 'FLT-ACE-001', 'nombre' => 'Filtro de aceite Nissan', 'marca' => 'Nissan', 'precio' => 12.50, 'cant' => 45, 'min' => 10],
            ['ref' => 'FLT-AIR-002', 'nombre' => 'Filtro de aire Nissan Qashqai', 'marca' => 'Nissan', 'precio' => 18.90, 'cant' => 32, 'min' => 8],
            ['ref' => 'PAS-DEL-003', 'nombre' => 'Pastillas freno delanteras', 'marca' => 'Brembo', 'precio' => 45.00, 'cant' => 20, 'min' => 5],
            ['ref' => 'PAS-TRA-004', 'nombre' => 'Pastillas freno traseras', 'marca' => 'Brembo', 'precio' => 38.50, 'cant' => 15, 'min' => 5],
            ['ref' => 'ACE-5W30-005', 'nombre' => 'Aceite motor 5W30 5L', 'marca' => 'Castrol', 'precio' => 32.00, 'cant' => 60, 'min' => 15],
            ['ref' => 'BUJ-NGK-006', 'nombre' => 'Bujía NGK Iridium', 'marca' => 'NGK', 'precio' => 8.75, 'cant' => 80, 'min' => 20],
            ['ref' => 'COR-DIS-007', 'nombre' => 'Correa distribución Renault', 'marca' => 'Renault', 'precio' => 65.00, 'cant' => 8, 'min' => 3],
            ['ref' => 'AMO-DEL-008', 'nombre' => 'Amortiguador delantero', 'marca' => 'Monroe', 'precio' => 78.00, 'cant' => 12, 'min' => 4],
            ['ref' => 'BAT-70A-009', 'nombre' => 'Batería 70Ah', 'marca' => 'Varta', 'precio' => 95.00, 'cant' => 6, 'min' => 3],
            ['ref' => 'LAM-H7-010', 'nombre' => 'Lámpara H7 LED', 'marca' => 'Philips', 'precio' => 22.00, 'cant' => 25, 'min' => 10],
            ['ref' => 'LIQ-REF-011', 'nombre' => 'Líquido refrigerante 5L', 'marca' => 'Repsol', 'precio' => 15.50, 'cant' => 18, 'min' => 8],
            ['ref' => 'ESC-LIM-012', 'nombre' => 'Escobilla limpiaparabrisas', 'marca' => 'Bosch', 'precio' => 14.00, 'cant' => 3, 'min' => 6],
            ['ref' => 'FLT-HAB-013', 'nombre' => 'Filtro habitáculo Dacia', 'marca' => 'Dacia', 'precio' => 11.00, 'cant' => 22, 'min' => 8],
            ['ref' => 'DIS-FRE-014', 'nombre' => 'Disco freno delantero', 'marca' => 'Brembo', 'precio' => 55.00, 'cant' => 10, 'min' => 4],
            ['ref' => 'KIT-EMB-015', 'nombre' => 'Kit embrague completo', 'marca' => 'Valeo', 'precio' => 185.00, 'cant' => 4, 'min' => 2],
        ];

        foreach ($piezas as $i => $p) {
            Stock::firstOrCreate(
                ['referencia' => $p['ref']],
                ['referencia' => $p['ref'], 'nombre_pieza' => $p['nombre'], 'marca_pieza' => $p['marca'], 'precio_unitario' => $p['precio'], 'cantidad' => $p['cant'], 'stock_minimo' => $p['min'], 'almacen_id' => $almacenes[$i % $almacenes->count()]->id, 'empresa_id' => $empresa->id, 'centro_id' => $centro->id, 'activo' => true]
            );
        }
    }

    private function seedRepartos(): void
    {
        $stocks = Stock::all();
        $almacenes = Almacen::all();
        if ($stocks->count() < 2 || $almacenes->count() < 2) {
            return;
        }
        $empresa = Empresa::first();
        $centro = Centro::first();

        $estados = ['pendiente', 'en_transito', 'entregado', 'cancelado'];
        for ($i = 1; $i <= 6; $i++) {
            Reparto::firstOrCreate(
                ['codigo_reparto' => 'REP-'.date('Ym').'-'.str_pad($i, 4, '0', STR_PAD_LEFT)],
                ['codigo_reparto' => 'REP-'.date('Ym').'-'.str_pad($i, 4, '0', STR_PAD_LEFT), 'stock_id' => $stocks->random()->id, 'almacen_origen_id' => $almacenes[0]->id, 'almacen_destino_id' => $almacenes[min(1, $almacenes->count() - 1)]->id, 'empresa_id' => $empresa->id, 'centro_id' => $centro->id, 'cantidad' => rand(2, 15), 'estado' => $estados[$i % 4], 'fecha_solicitud' => now()->subDays(rand(1, 20)), 'solicitado_por' => 'Admin']
            );
        }
    }

    private function seedCitas(): void
    {
        $mecanicos = Mecanico::with('taller')->get();
        if ($mecanicos->isEmpty()) {
            return;
        }
        $empresa = Empresa::first();

        $clientes = ['Ana López', 'Pedro Suárez', 'María García', 'Carlos Díaz', 'Laura Medina', 'Roberto Fernández', 'Elena Cruz', 'Pablo Martín'];
        $vehiculos = ['Nissan Qashqai 2023', 'Renault Clio 2022', 'Dacia Duster 2024', 'Nissan Juke 2023', 'Renault Captur 2022', 'Dacia Sandero 2024'];
        $descripciones = ['Revisión de los 30.000 km', 'Cambio de aceite y filtros', 'Ruido en la dirección', 'Revisión ITV', 'Cambio de neumáticos', 'Diagnóstico motor', 'Reparación aire acondicionado', 'Cambio pastillas de freno'];
        $estados = ['pendiente', 'confirmada', 'en_curso', 'completada', 'cancelada'];

        for ($i = 0; $i < 15; $i++) {
            $mec = $mecanicos->random();
            $fecha = now()->addDays(rand(-10, 14));
            $hora = rand(8, 16);
            CitaTaller::create([
                'mecanico_id' => $mec->id, 'taller_id' => $mec->taller_id, 'empresa_id' => $empresa->id,
                'cliente_nombre' => $clientes[array_rand($clientes)], 'vehiculo_info' => $vehiculos[array_rand($vehiculos)],
                'fecha' => $fecha->format('Y-m-d'), 'hora_inicio' => sprintf('%02d:00', $hora), 'hora_fin' => sprintf('%02d:00', $hora + 1),
                'descripcion' => $descripciones[array_rand($descripciones)], 'estado' => $estados[array_rand($estados)],
            ]);
        }
    }

    private function seedCochesSustitucion(): void
    {
        $talleres = Taller::all();
        if ($talleres->isEmpty()) {
            return;
        }
        $empresa = Empresa::first();

        // Pick real vehicles from the vehiculos table so matriculas/modelos are coherent
        $vehiculos = Vehiculo::with('marca')->inRandomOrder()->take(6)->get();
        if ($vehiculos->isEmpty()) {
            return;
        }

        $colores = ['Blanco', 'Gris', 'Rojo', 'Azul', 'Negro', 'Plata'];

        foreach ($vehiculos->values() as $i => $v) {
            $coche = CocheSustitucion::firstOrCreate(
                ['matricula' => $v->matricula],
                [
                    'matricula' => $v->matricula,
                    'modelo' => ($v->marca?->nombre ? $v->marca->nombre.' ' : '').$v->modelo,
                    'marca_id' => $v->marca_id,
                    'color' => $colores[$i % count($colores)],
                    'anio' => 2023,
                    'taller_id' => $talleres[$i % $talleres->count()]->id,
                    'empresa_id' => $empresa->id,
                    'disponible' => true,
                ]
            );
            if ($i < 3) {
                ReservaSustitucion::firstOrCreate(
                    ['coche_id' => $coche->id, 'cliente_nombre' => 'Cliente Ejemplo '.($i + 1)],
                    ['coche_id' => $coche->id, 'cliente_nombre' => 'Cliente Ejemplo '.($i + 1), 'fecha_inicio' => now()->subDays(3), 'fecha_fin' => now()->addDays(4), 'estado' => 'reservado']
                );
            }
        }
    }

    private function seedNamingPcs(): void
    {
        $empresa = Empresa::first();
        $centro = Centro::first();

        $pcs = [
            ['nombre' => 'DAI-LP-PC001', 'tipo' => 'Sobremesa', 'ubi' => 'Recepción LP', 'so' => 'Windows 11', 'ver' => 'PRO', 'ip' => '192.168.1.10'],
            ['nombre' => 'DAI-LP-PC002', 'tipo' => 'Sobremesa', 'ubi' => 'Administración LP', 'so' => 'Windows 11', 'ver' => 'PRO', 'ip' => '192.168.1.11'],
            ['nombre' => 'DAI-LP-PT001', 'tipo' => 'Portátil', 'ubi' => 'Comercial LP', 'so' => 'Windows 11', 'ver' => 'PRO', 'ip' => '192.168.1.20'],
            ['nombre' => 'DAI-LP-PT002', 'tipo' => 'Portátil', 'ubi' => 'Gerencia LP', 'so' => 'macOS Sonoma', 'ver' => 'PRO', 'ip' => '192.168.1.21'],
            ['nombre' => 'DAI-TF-PC001', 'tipo' => 'Sobremesa', 'ubi' => 'Recepción TF', 'so' => 'Windows 10', 'ver' => 'HOME', 'ip' => '192.168.2.10'],
            ['nombre' => 'DAI-TF-PT001', 'tipo' => 'Portátil', 'ubi' => 'Comercial TF', 'so' => 'Windows 11', 'ver' => 'PRO', 'ip' => '192.168.2.20'],
            ['nombre' => 'DAI-LZ-PC001', 'tipo' => 'Sobremesa', 'ubi' => 'Recepción LZ', 'so' => 'Windows 11', 'ver' => 'HOME', 'ip' => '192.168.3.10'],
            ['nombre' => 'DAI-FV-PC001', 'tipo' => 'Sobremesa', 'ubi' => 'Recepción FV', 'so' => 'Windows 10', 'ver' => 'PRO', 'ip' => '192.168.4.10'],
            ['nombre' => 'DAI-LP-PT003', 'tipo' => 'Portátil', 'ubi' => 'Taller LP', 'so' => 'Ubuntu 24.04 LTS', 'ver' => 'PRO', 'ip' => '192.168.1.30'],
            ['nombre' => 'DAI-LP-PC003', 'tipo' => 'Sobremesa', 'ubi' => 'Recambios LP', 'so' => 'Windows 11', 'ver' => 'PRO', 'ip' => '192.168.1.12'],
        ];

        foreach ($pcs as $pc) {
            NamingPc::firstOrCreate(
                ['nombre_equipo' => $pc['nombre']],
                ['nombre_equipo' => $pc['nombre'], 'tipo' => $pc['tipo'], 'ubicacion' => $pc['ubi'], 'sistema_operativo' => $pc['so'], 'version_so' => $pc['ver'], 'direccion_ip' => $pc['ip'], 'direccion_mac' => sprintf('%02X:%02X:%02X:%02X:%02X:%02X', rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255)), 'empresa_id' => $empresa->id, 'centro_id' => $centro->id, 'activo' => true]
            );
        }
    }

    private function seedVentas(): void
    {
        $vehiculos = Vehiculo::with('marca')->get();
        $clientes = Cliente::all();
        if ($vehiculos->isEmpty() || $clientes->isEmpty()) {
            return;
        }
        $empresa = Empresa::first();
        $centro = Centro::first();
        $user = User::first();

        // Determine tax based on empresa CP
        $cp = $empresa->codigo_postal ?? '';
        $esCanarias = str_starts_with($cp, '35') || str_starts_with($cp, '38');
        $impNombre = $esCanarias ? 'IGIC' : 'IVA';
        $impPct = $esCanarias ? 7 : 21;

        $formas = ['contado', 'financiado', 'leasing', 'renting'];
        $estados = ['reservada', 'pendiente_entrega', 'entregada', 'cancelada'];

        $extrasPool = [
            ['descripcion' => 'Pintura metalizada', 'importe' => 750],
            ['descripcion' => 'Techo panorámico', 'importe' => 1200],
            ['descripcion' => 'Pack navegación', 'importe' => 500],
            ['descripcion' => 'Llantas aleación 18"', 'importe' => 900],
            ['descripcion' => 'Tapicería cuero', 'importe' => 1500],
            ['descripcion' => 'Cámara 360°', 'importe' => 600],
            ['descripcion' => 'Asientos calefactados', 'importe' => 400],
            ['descripcion' => 'Portón eléctrico', 'importe' => 350],
        ];

        $descuentosPool = [
            ['descripcion' => 'Descuento campaña', 'importe' => 500],
            ['descripcion' => 'Descuento fidelización', 'importe' => 300],
            ['descripcion' => 'Promoción financiación', 'importe' => 800],
            ['descripcion' => 'Dto. flota empresa', 'importe' => 1200],
        ];

        $vehiculosVenta = $vehiculos->count() >= 8 ? $vehiculos->random(8) : $vehiculos;

        foreach ($vehiculosVenta->values() as $i => $vehiculo) {
            $catalogo = \App\Models\CatalogoPrecio::where('marca_id', $vehiculo->marca_id)
                ->where('modelo', $vehiculo->modelo)
                ->where('version', $vehiculo->version)
                ->first();
            $precio = $catalogo ? (float) $catalogo->precio_base : rand(15000, 55000);
            $descuento = rand(0, 2000);

            // Calculate extras and descuentos for this venta
            $ventaExtras = [];
            $ventaDescuentos = [];
            $sumExtras = 0;
            $sumDescuentos = 0;

            // 60% chance of having 1-2 extras
            if (rand(1, 10) <= 6) {
                $numExtras = rand(1, 2);
                $selectedExtras = array_rand($extrasPool, min($numExtras, count($extrasPool)));
                foreach ((array) $selectedExtras as $eIdx) {
                    $ventaExtras[] = $extrasPool[$eIdx];
                    $sumExtras += $extrasPool[$eIdx]['importe'];
                }
            }

            // 40% chance of having 1 additional descuento
            if (rand(1, 10) <= 4) {
                $dIdx = array_rand($descuentosPool);
                $ventaDescuentos[] = $descuentosPool[$dIdx];
                $sumDescuentos += $descuentosPool[$dIdx]['importe'];
            }

            $precioFinal = $precio - $descuento + $sumExtras - $sumDescuentos;
            $subtotal = $precioFinal;
            $impImporte = round($subtotal * $impPct / 100, 2);
            $total = round($subtotal + $impImporte, 2);

            $venta = Venta::firstOrCreate(
                ['codigo_venta' => 'VTA-'.date('Ym').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'codigo_venta' => 'VTA-'.date('Ym').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'vehiculo_id' => $vehiculo->id,
                    'cliente_id' => $clientes->random()->id,
                    'empresa_id' => $empresa->id,
                    'centro_id' => $centro->id,
                    'marca_id' => $vehiculo->marca_id,
                    'vendedor_id' => $user->id,
                    'precio_venta' => $precio,
                    'descuento' => $descuento,
                    'precio_final' => $precioFinal,
                    'subtotal' => $subtotal,
                    'impuesto_nombre' => $impNombre,
                    'impuesto_porcentaje' => $impPct,
                    'impuesto_importe' => $impImporte,
                    'total' => $total,
                    'forma_pago' => $formas[array_rand($formas)],
                    'estado' => $estados[array_rand($estados)],
                    'fecha_venta' => now()->subDays(rand(1, 60)),
                ]
            );

            // Create conceptos
            if ($venta->wasRecentlyCreated) {
                foreach ($ventaExtras as $extra) {
                    \App\Models\VentaConcepto::create([
                        'venta_id' => $venta->id,
                        'tipo' => 'extra',
                        'descripcion' => $extra['descripcion'],
                        'importe' => $extra['importe'],
                    ]);
                }
                foreach ($ventaDescuentos as $desc) {
                    \App\Models\VentaConcepto::create([
                        'venta_id' => $venta->id,
                        'tipo' => 'descuento',
                        'descripcion' => $desc['descripcion'],
                        'importe' => $desc['importe'],
                    ]);
                }
            }
        }
    }

    private function seedTasaciones(): void
    {
        $clientes = Cliente::all();
        $empresa = Empresa::first();
        $user = User::first();

        $coches = [
            ['marca' => 'Nissan', 'modelo' => 'Qashqai', 'anio' => 2019, 'km' => 65000],
            ['marca' => 'Renault', 'modelo' => 'Clio', 'anio' => 2020, 'km' => 42000],
            ['marca' => 'Dacia', 'modelo' => 'Duster', 'anio' => 2018, 'km' => 95000],
            ['marca' => 'Seat', 'modelo' => 'León', 'anio' => 2017, 'km' => 120000],
            ['marca' => 'Volkswagen', 'modelo' => 'Golf', 'anio' => 2021, 'km' => 35000],
            ['marca' => 'Toyota', 'modelo' => 'Corolla', 'anio' => 2020, 'km' => 50000],
        ];

        $estados = ['pendiente', 'valorada', 'aceptada', 'rechazada'];
        $estVeh = ['excelente', 'bueno', 'regular', 'malo'];

        foreach ($coches as $i => $c) {
            $valor = rand(8000, 28000);
            Tasacion::firstOrCreate(
                ['codigo_tasacion' => 'TAS-'.date('Ym').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                ['codigo_tasacion' => 'TAS-'.date('Ym').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT), 'cliente_id' => $clientes->count() > 0 ? $clientes->random()->id : null, 'empresa_id' => $empresa->id, 'tasador_id' => $user->id, 'vehiculo_marca' => $c['marca'], 'vehiculo_modelo' => $c['modelo'], 'vehiculo_anio' => $c['anio'], 'kilometraje' => $c['km'], 'matricula' => sprintf('%04d %s', rand(1000, 9999), chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90))), 'combustible' => ['Gasolina', 'Diésel', 'Híbrido'][rand(0, 2)], 'estado_vehiculo' => $estVeh[array_rand($estVeh)], 'valor_estimado' => $valor, 'valor_final' => $i < 3 ? $valor - rand(500, 1500) : null, 'estado' => $estados[array_rand($estados)], 'fecha_tasacion' => now()->subDays(rand(1, 45))]
            );
        }
    }

    private function seedVacaciones(): void
    {
        $users = User::all();
        if ($users->count() < 2) {
            return;
        }

        $estados = ['pendiente', 'aprobada', 'rechazada'];
        foreach ($users->take(4) as $i => $user) {
            Vacacion::firstOrCreate(
                ['user_id' => $user->id, 'fecha_inicio' => now()->addDays(30 + ($i * 15))->format('Y-m-d')],
                ['user_id' => $user->id, 'fecha_inicio' => now()->addDays(30 + ($i * 15)), 'fecha_fin' => now()->addDays(35 + ($i * 15)), 'dias_solicitados' => 5, 'estado' => $estados[$i % 3], 'motivo' => ['Vacaciones de verano', 'Asuntos personales', 'Viaje familiar', 'Descanso'][$i % 4], 'aprobado_por' => $i > 0 ? $users->first()->id : null]
            );
        }
    }
}
