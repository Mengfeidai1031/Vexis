<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\CatalogoPrecio;
use App\Models\Centro;
use App\Models\CitaTaller;
use App\Models\Cliente;
use App\Models\CocheSustitucion;
use App\Models\Empresa;
use App\Models\Incidencia;
use App\Models\IncidenciaArchivo;
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
use App\Models\VentaConcepto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatosEjemploSeeder extends Seeder
{
    /** Rango de fechas para datos históricos (Dataxis). */
    private Carbon $fechaInicio;

    private Carbon $fechaFin;

    public function __construct()
    {
        $this->fechaInicio = Carbon::create(2024, 1, 1);
        $this->fechaFin = Carbon::create(2026, 4, 20);
    }

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
        $this->seedIncidencias();
    }

    private function fechaAleatoria(): Carbon
    {
        $ts = rand($this->fechaInicio->timestamp, $this->fechaFin->timestamp);

        return Carbon::createFromTimestamp($ts);
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
            ['nombre' => 'Iván', 'apellidos' => 'Quevedo Sosa', 'especialidad' => 'Mecánica general'],
            ['nombre' => 'Manuel', 'apellidos' => 'Cruz Castellano', 'especialidad' => 'Diagnosis electrónica'],
            ['nombre' => 'Rubén', 'apellidos' => 'Afonso Padrón', 'especialidad' => 'Climatización'],
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
            ['ref' => 'NEU-215-016', 'nombre' => 'Neumático 215/55 R17', 'marca' => 'Michelin', 'precio' => 110.00, 'cant' => 40, 'min' => 12],
            ['ref' => 'NEU-205-017', 'nombre' => 'Neumático 205/55 R16', 'marca' => 'Continental', 'precio' => 85.00, 'cant' => 36, 'min' => 12],
            ['ref' => 'RAD-ENF-018', 'nombre' => 'Radiador refrigeración', 'marca' => 'Valeo', 'precio' => 145.00, 'cant' => 5, 'min' => 2],
            ['ref' => 'TER-FRE-019', 'nombre' => 'Terminal freno hidráulico', 'marca' => 'ATE', 'precio' => 18.00, 'cant' => 28, 'min' => 10],
            ['ref' => 'ALT-100A-020', 'nombre' => 'Alternador 100A', 'marca' => 'Bosch', 'precio' => 220.00, 'cant' => 7, 'min' => 3],
            ['ref' => 'INY-DIE-021', 'nombre' => 'Inyector diésel Renault dCi', 'marca' => 'Delphi', 'precio' => 320.00, 'cant' => 6, 'min' => 2],
            ['ref' => 'CAR-OLE-022', 'nombre' => 'Cárter de aceite', 'marca' => 'OEM', 'precio' => 95.00, 'cant' => 9, 'min' => 3],
        ];

        foreach ($piezas as $i => $p) {
            Stock::firstOrCreate(
                ['referencia' => $p['ref']],
                [
                    'referencia' => $p['ref'], 'nombre_pieza' => $p['nombre'], 'marca_pieza' => $p['marca'],
                    'precio_unitario' => $p['precio'], 'cantidad' => $p['cant'], 'stock_minimo' => $p['min'],
                    'almacen_id' => $almacenes[$i % $almacenes->count()]->id,
                    'empresa_id' => $empresa->id, 'centro_id' => $centro->id, 'activo' => true,
                ]
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

        for ($i = 1; $i <= 80; $i++) {
            $fecha = $this->fechaAleatoria();
            $estado = $estados[array_rand($estados)];
            Reparto::firstOrCreate(
                ['codigo_reparto' => 'REP-'.$fecha->format('Ym').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'codigo_reparto' => 'REP-'.$fecha->format('Ym').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'stock_id' => $stocks->random()->id,
                    'almacen_origen_id' => $almacenes[0]->id,
                    'almacen_destino_id' => $almacenes[rand(1, $almacenes->count() - 1)]->id,
                    'empresa_id' => $empresa->id, 'centro_id' => $centro->id,
                    'cantidad' => rand(1, 20),
                    'estado' => $estado,
                    'fecha_solicitud' => $fecha->toDateString(),
                    'fecha_entrega' => $estado === 'entregado' ? $fecha->copy()->addDays(rand(1, 7))->toDateString() : null,
                    'solicitado_por' => ['Admin', 'Mecánico Jefe', 'Recepción Taller'][rand(0, 2)],
                ]
            );
        }
    }

    private function seedCitas(): void
    {
        $mecanicos = Mecanico::with('taller')->get();
        if ($mecanicos->isEmpty()) {
            return;
        }
        $empresas = Empresa::pluck('id')->toArray();

        $clientes = ['Ana López', 'Pedro Suárez', 'María García', 'Carlos Díaz', 'Laura Medina', 'Roberto Fernández', 'Elena Cruz', 'Pablo Martín', 'Lucía Pérez', 'Javier Torres', 'Alba Hernández', 'Daniel Ruiz', 'Marta Gómez', 'Sergio Quintana', 'Rocío Pérez'];
        $vehiculos = ['Nissan Qashqai 2023', 'Renault Clio 2022', 'Dacia Duster 2024', 'Nissan Juke 2023', 'Renault Captur 2022', 'Dacia Sandero 2024', 'Renault Megane 2023', 'Nissan X-Trail 2024', 'Dacia Jogger 2024', 'Renault Austral 2024'];
        $descripciones = ['Revisión de los 30.000 km', 'Cambio de aceite y filtros', 'Ruido en la dirección', 'Revisión ITV', 'Cambio de neumáticos', 'Diagnóstico motor', 'Reparación aire acondicionado', 'Cambio pastillas de freno', 'Alineación y equilibrado', 'Sustitución de correa de distribución', 'Reparación embrague'];
        $estados = ['pendiente', 'confirmada', 'en_curso', 'completada', 'cancelada'];

        for ($i = 0; $i < 150; $i++) {
            $mec = $mecanicos->random();
            $fecha = $this->fechaAleatoria();
            $hora = rand(8, 16);
            CitaTaller::create([
                'mecanico_id' => $mec->id,
                'taller_id' => $mec->taller_id,
                'marca_id' => $mec->taller?->marca_id,
                'empresa_id' => $empresas[array_rand($empresas)],
                'cliente_nombre' => $clientes[array_rand($clientes)],
                'vehiculo_info' => $vehiculos[array_rand($vehiculos)],
                'fecha' => $fecha->toDateString(),
                'hora_inicio' => sprintf('%02d:00', $hora),
                'hora_fin' => sprintf('%02d:00', $hora + 1),
                'descripcion' => $descripciones[array_rand($descripciones)],
                'estado' => $estados[array_rand($estados)],
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
        $vehiculos = Vehiculo::with('marca')->inRandomOrder()->take(10)->get();
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
                    'anio' => rand(2022, 2025),
                    'taller_id' => $talleres[$i % $talleres->count()]->id,
                    'empresa_id' => $empresa->id,
                    'disponible' => true,
                ]
            );

            // Reservas históricas
            for ($r = 0; $r < rand(1, 4); $r++) {
                $fi = $this->fechaAleatoria();
                ReservaSustitucion::firstOrCreate(
                    ['coche_id' => $coche->id, 'fecha_inicio' => $fi->toDateString(), 'cliente_nombre' => 'Cliente Histórico '.($i + 1).'-'.$r],
                    [
                        'coche_id' => $coche->id,
                        'cliente_nombre' => 'Cliente Histórico '.($i + 1).'-'.$r,
                        'fecha_inicio' => $fi->toDateString(),
                        'fecha_fin' => $fi->copy()->addDays(rand(2, 10))->toDateString(),
                        'estado' => ['reservado', 'entregado', 'devuelto', 'cancelado'][rand(0, 3)],
                    ]
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
                [
                    'nombre_equipo' => $pc['nombre'], 'tipo' => $pc['tipo'], 'ubicacion' => $pc['ubi'],
                    'sistema_operativo' => $pc['so'], 'version_so' => $pc['ver'], 'direccion_ip' => $pc['ip'],
                    'direccion_mac' => sprintf('%02X:%02X:%02X:%02X:%02X:%02X', rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255)),
                    'empresa_id' => $empresa->id, 'centro_id' => $centro->id, 'activo' => true,
                ]
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
        $empresas = Empresa::all()->keyBy('id');
        $vendedores = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['Vendedor', 'Gerente']))->pluck('id')->toArray();
        if (empty($vendedores)) {
            $vendedores = [User::first()->id];
        }

        $formas = ['contado', 'financiado', 'leasing', 'renting'];
        $estados = ['reservada', 'pendiente_entrega', 'entregada', 'entregada', 'entregada', 'cancelada'];
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

        // 120 ventas distribuidas a lo largo del rango de fechas
        $seq = 1;
        for ($i = 0; $i < 120; $i++) {
            $vehiculo = $vehiculos->random();
            $empresa = $empresas[$vehiculo->empresa_id] ?? $empresas->first();
            $centroId = $empresa->centros()->inRandomOrder()->value('id') ?? 1;

            $cp = $empresa->codigo_postal ?? '';
            $esCanarias = str_starts_with($cp, '35') || str_starts_with($cp, '38');
            $impNombre = $esCanarias ? 'IGIC' : 'IVA';
            $impPct = $esCanarias ? 7 : 21;

            $catalogo = CatalogoPrecio::where('marca_id', $vehiculo->marca_id)
                ->where('modelo', $vehiculo->modelo)
                ->where('version', $vehiculo->version)
                ->first();
            $precio = $catalogo ? (float) $catalogo->precio_base : rand(15000, 55000);
            $descuento = rand(0, 2500);

            $ventaExtras = [];
            $ventaDescuentos = [];
            $sumExtras = 0;
            $sumDescuentos = 0;

            if (rand(1, 10) <= 6) {
                $numExtras = rand(1, 3);
                $keys = array_rand($extrasPool, min($numExtras, count($extrasPool)));
                foreach ((array) $keys as $k) {
                    $ventaExtras[] = $extrasPool[$k];
                    $sumExtras += $extrasPool[$k]['importe'];
                }
            }

            if (rand(1, 10) <= 4) {
                $k = array_rand($descuentosPool);
                $ventaDescuentos[] = $descuentosPool[$k];
                $sumDescuentos += $descuentosPool[$k]['importe'];
            }

            $precioFinal = $precio - $descuento + $sumExtras - $sumDescuentos;
            $subtotal = $precioFinal;
            $impImporte = round($subtotal * $impPct / 100, 2);
            $total = round($subtotal + $impImporte, 2);

            $fechaVenta = $this->fechaAleatoria();
            $estado = $estados[array_rand($estados)];

            $codigo = 'VTA-'.$fechaVenta->format('Ym').'-'.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            $venta = Venta::firstOrCreate(
                ['codigo_venta' => $codigo],
                [
                    'codigo_venta' => $codigo,
                    'vehiculo_id' => $vehiculo->id,
                    'cliente_id' => $clientes->random()->id,
                    'empresa_id' => $empresa->id,
                    'centro_id' => $centroId,
                    'marca_id' => $vehiculo->marca_id,
                    'vendedor_id' => $vendedores[array_rand($vendedores)],
                    'precio_venta' => $precio,
                    'descuento' => $descuento,
                    'precio_final' => $precioFinal,
                    'subtotal' => $subtotal,
                    'impuesto_nombre' => $impNombre,
                    'impuesto_porcentaje' => $impPct,
                    'impuesto_importe' => $impImporte,
                    'total' => $total,
                    'forma_pago' => $formas[array_rand($formas)],
                    'estado' => $estado,
                    'fecha_venta' => $fechaVenta->toDateString(),
                    'fecha_entrega' => in_array($estado, ['entregada', 'pendiente_entrega']) ? $fechaVenta->copy()->addDays(rand(5, 30))->toDateString() : null,
                ]
            );

            if ($venta->wasRecentlyCreated) {
                foreach ($ventaExtras as $extra) {
                    VentaConcepto::create(['venta_id' => $venta->id, 'tipo' => 'extra', 'descripcion' => $extra['descripcion'], 'importe' => $extra['importe']]);
                }
                foreach ($ventaDescuentos as $desc) {
                    VentaConcepto::create(['venta_id' => $venta->id, 'tipo' => 'descuento', 'descripcion' => $desc['descripcion'], 'importe' => $desc['importe']]);
                }
                $seq++;
            }
        }
    }

    private function seedTasaciones(): void
    {
        $clientes = Cliente::all();
        $empresas = Empresa::pluck('id')->toArray();
        $tasadores = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['Vendedor', 'Gerente', 'Administrador']))->pluck('id')->toArray();
        if (empty($tasadores)) {
            $tasadores = [User::first()->id];
        }

        $coches = [
            ['marca' => 'Nissan', 'modelo' => 'Qashqai'], ['marca' => 'Nissan', 'modelo' => 'Juke'], ['marca' => 'Nissan', 'modelo' => 'X-Trail'],
            ['marca' => 'Renault', 'modelo' => 'Clio'], ['marca' => 'Renault', 'modelo' => 'Captur'], ['marca' => 'Renault', 'modelo' => 'Megane'],
            ['marca' => 'Dacia', 'modelo' => 'Duster'], ['marca' => 'Dacia', 'modelo' => 'Sandero'], ['marca' => 'Dacia', 'modelo' => 'Jogger'],
            ['marca' => 'Seat', 'modelo' => 'León'], ['marca' => 'Volkswagen', 'modelo' => 'Golf'], ['marca' => 'Toyota', 'modelo' => 'Corolla'],
            ['marca' => 'Peugeot', 'modelo' => '208'], ['marca' => 'Citroën', 'modelo' => 'C3'], ['marca' => 'Ford', 'modelo' => 'Fiesta'],
        ];
        $estados = ['pendiente', 'valorada', 'aceptada', 'rechazada'];
        $estVeh = ['excelente', 'bueno', 'regular', 'malo'];
        $combustibles = ['Gasolina', 'Diésel', 'Híbrido', 'Eléctrico'];

        for ($i = 0; $i < 50; $i++) {
            $c = $coches[array_rand($coches)];
            $valor = rand(6000, 32000);
            $estado = $estados[array_rand($estados)];
            $fecha = $this->fechaAleatoria();
            $codigo = 'TAS-'.$fecha->format('Ym').'-'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT);

            Tasacion::firstOrCreate(
                ['codigo_tasacion' => $codigo],
                [
                    'codigo_tasacion' => $codigo,
                    'cliente_id' => $clientes->isNotEmpty() ? $clientes->random()->id : null,
                    'empresa_id' => $empresas[array_rand($empresas)],
                    'tasador_id' => $tasadores[array_rand($tasadores)],
                    'vehiculo_marca' => $c['marca'],
                    'vehiculo_modelo' => $c['modelo'],
                    'vehiculo_anio' => rand(2015, 2024),
                    'kilometraje' => rand(15000, 180000),
                    'matricula' => sprintf('%04d %s', rand(1000, 9999), chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90))),
                    'combustible' => $combustibles[array_rand($combustibles)],
                    'estado_vehiculo' => $estVeh[array_rand($estVeh)],
                    'valor_estimado' => $valor,
                    'valor_final' => in_array($estado, ['aceptada', 'valorada']) ? $valor - rand(500, 2500) : null,
                    'estado' => $estado,
                    'fecha_tasacion' => $fecha->toDateString(),
                ]
            );
        }
    }

    private function seedVacaciones(): void
    {
        $users = User::all();
        if ($users->count() < 2) {
            return;
        }
        $estados = ['pendiente', 'aprobada', 'aprobada', 'rechazada'];
        $motivos = ['Vacaciones de verano', 'Asuntos personales', 'Viaje familiar', 'Descanso', 'Boda familiar', 'Navidades', 'Puente'];
        $aprobador = $users->first()->id;

        foreach ($users as $user) {
            for ($k = 0; $k < rand(1, 4); $k++) {
                $fi = $this->fechaAleatoria();
                $dias = rand(2, 14);
                $estado = $estados[array_rand($estados)];
                Vacacion::firstOrCreate(
                    ['user_id' => $user->id, 'fecha_inicio' => $fi->toDateString()],
                    [
                        'user_id' => $user->id,
                        'fecha_inicio' => $fi->toDateString(),
                        'fecha_fin' => $fi->copy()->addDays($dias)->toDateString(),
                        'dias_solicitados' => $dias,
                        'estado' => $estado,
                        'motivo' => $motivos[array_rand($motivos)],
                        'aprobado_por' => $estado !== 'pendiente' ? $aprobador : null,
                    ]
                );
            }
        }
    }

    private function seedIncidencias(): void
    {
        $usuarios = User::pluck('id')->toArray();
        $tecnicos = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['Super Admin', 'Administrador']))->pluck('id')->toArray();
        if (empty($usuarios) || empty($tecnicos)) {
            return;
        }

        $titulos = [
            'Error al generar PDF de factura', 'Login bloqueado tras cambio de contraseña',
            'Lentitud al cargar listados', 'Impresora no responde en recepción',
            'Dataxis no muestra datos del mes', 'Fallo al subir PDF de oferta',
            'Correo electrónico no envía notificaciones', 'Pantalla táctil del panel recambios',
            'Error 500 al abrir tasación', 'Problema de permisos tras alta de usuario',
        ];
        $prioridades = ['baja', 'media', 'alta', 'critica'];
        $estados = ['abierta', 'en_progreso', 'resuelta', 'cerrada'];

        for ($i = 1; $i <= 25; $i++) {
            $fa = $this->fechaAleatoria();
            $estado = $estados[array_rand($estados)];
            Incidencia::firstOrCreate(
                ['codigo_incidencia' => 'INC-'.$fa->format('Ym').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'codigo_incidencia' => 'INC-'.$fa->format('Ym').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'titulo' => $titulos[array_rand($titulos)],
                    'descripcion' => 'Descripción detallada de la incidencia registrada por el usuario. Se observa el problema al intentar realizar la operación descrita en el título.',
                    'usuario_id' => $usuarios[array_rand($usuarios)],
                    'tecnico_id' => in_array($estado, ['en_progreso', 'resuelta', 'cerrada']) ? $tecnicos[array_rand($tecnicos)] : null,
                    'prioridad' => $prioridades[array_rand($prioridades)],
                    'estado' => $estado,
                    'comentario_tecnico' => in_array($estado, ['resuelta', 'cerrada']) ? 'Se ha solucionado el problema aplicando la configuración correcta.' : null,
                    'fecha_apertura' => $fa,
                    'fecha_cierre' => in_array($estado, ['resuelta', 'cerrada']) ? $fa->copy()->addDays(rand(1, 15)) : null,
                ]
            );
        }
    }
}
