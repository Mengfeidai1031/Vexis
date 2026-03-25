<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mecanico;
use App\Models\Stock;
use App\Models\Reparto;
use App\Models\CitaTaller;
use App\Models\CocheSustitucion;
use App\Models\ReservaSustitucion;
use App\Models\NamingPc;
use App\Models\Venta;
use App\Models\Tasacion;
use App\Models\Vacacion;
use App\Models\Taller;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;

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
        if ($talleres->isEmpty()) return;

        $mecanicos = [
            ['nombre'=>'Carlos','apellidos'=>'García Rodríguez','especialidad'=>'Mecánica general'],
            ['nombre'=>'Miguel','apellidos'=>'Fernández López','especialidad'=>'Electricidad'],
            ['nombre'=>'Antonio','apellidos'=>'Hernández Pérez','especialidad'=>'Chapa y pintura'],
            ['nombre'=>'José','apellidos'=>'Díaz Santana','especialidad'=>'Diagnosis electrónica'],
            ['nombre'=>'Pedro','apellidos'=>'Martín Suárez','especialidad'=>'Mecánica general'],
            ['nombre'=>'Juan','apellidos'=>'Cabrera Medina','especialidad'=>'Climatización'],
            ['nombre'=>'Francisco','apellidos'=>'Alonso Vega','especialidad'=>'Neumáticos y frenos'],
            ['nombre'=>'David','apellidos'=>'Moreno Cruz','especialidad'=>'Transmisiones'],
            ['nombre'=>'Raúl','apellidos'=>'Santos Rivero','especialidad'=>'Mecánica general'],
            ['nombre'=>'Sergio','apellidos'=>'González Tejera','especialidad'=>'Diagnosis electrónica'],
            ['nombre'=>'Alejandro','apellidos'=>'Navarro Gil','especialidad'=>'Electricidad'],
            ['nombre'=>'Fernando','apellidos'=>'Ruiz Betancort','especialidad'=>'Chapa y pintura'],
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
        if ($almacenes->isEmpty()) return;
        $empresa = Empresa::first();
        $centro = Centro::first();

        $piezas = [
            ['ref'=>'FLT-ACE-001','nombre'=>'Filtro de aceite Nissan','marca'=>'Nissan','precio'=>12.50,'cant'=>45,'min'=>10],
            ['ref'=>'FLT-AIR-002','nombre'=>'Filtro de aire Nissan Qashqai','marca'=>'Nissan','precio'=>18.90,'cant'=>32,'min'=>8],
            ['ref'=>'PAS-DEL-003','nombre'=>'Pastillas freno delanteras','marca'=>'Brembo','precio'=>45.00,'cant'=>20,'min'=>5],
            ['ref'=>'PAS-TRA-004','nombre'=>'Pastillas freno traseras','marca'=>'Brembo','precio'=>38.50,'cant'=>15,'min'=>5],
            ['ref'=>'ACE-5W30-005','nombre'=>'Aceite motor 5W30 5L','marca'=>'Castrol','precio'=>32.00,'cant'=>60,'min'=>15],
            ['ref'=>'BUJ-NGK-006','nombre'=>'Bujía NGK Iridium','marca'=>'NGK','precio'=>8.75,'cant'=>80,'min'=>20],
            ['ref'=>'COR-DIS-007','nombre'=>'Correa distribución Renault','marca'=>'Renault','precio'=>65.00,'cant'=>8,'min'=>3],
            ['ref'=>'AMO-DEL-008','nombre'=>'Amortiguador delantero','marca'=>'Monroe','precio'=>78.00,'cant'=>12,'min'=>4],
            ['ref'=>'BAT-70A-009','nombre'=>'Batería 70Ah','marca'=>'Varta','precio'=>95.00,'cant'=>6,'min'=>3],
            ['ref'=>'LAM-H7-010','nombre'=>'Lámpara H7 LED','marca'=>'Philips','precio'=>22.00,'cant'=>25,'min'=>10],
            ['ref'=>'LIQ-REF-011','nombre'=>'Líquido refrigerante 5L','marca'=>'Repsol','precio'=>15.50,'cant'=>18,'min'=>8],
            ['ref'=>'ESC-LIM-012','nombre'=>'Escobilla limpiaparabrisas','marca'=>'Bosch','precio'=>14.00,'cant'=>3,'min'=>6],
            ['ref'=>'FLT-HAB-013','nombre'=>'Filtro habitáculo Dacia','marca'=>'Dacia','precio'=>11.00,'cant'=>22,'min'=>8],
            ['ref'=>'DIS-FRE-014','nombre'=>'Disco freno delantero','marca'=>'Brembo','precio'=>55.00,'cant'=>10,'min'=>4],
            ['ref'=>'KIT-EMB-015','nombre'=>'Kit embrague completo','marca'=>'Valeo','precio'=>185.00,'cant'=>4,'min'=>2],
        ];

        foreach ($piezas as $i => $p) {
            Stock::firstOrCreate(
                ['referencia' => $p['ref']],
                ['referencia'=>$p['ref'],'nombre_pieza'=>$p['nombre'],'marca_pieza'=>$p['marca'],'precio_unitario'=>$p['precio'],'cantidad'=>$p['cant'],'stock_minimo'=>$p['min'],'almacen_id'=>$almacenes[$i % $almacenes->count()]->id,'empresa_id'=>$empresa->id,'centro_id'=>$centro->id,'activo'=>true]
            );
        }
    }

    private function seedRepartos(): void
    {
        $stocks = Stock::all();
        $almacenes = Almacen::all();
        if ($stocks->count() < 2 || $almacenes->count() < 2) return;
        $empresa = Empresa::first();
        $centro = Centro::first();

        $estados = ['pendiente','en_transito','entregado','cancelado'];
        for ($i = 1; $i <= 6; $i++) {
            Reparto::firstOrCreate(
                ['codigo_reparto' => 'REP-' . date('Ym') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT)],
                ['codigo_reparto'=>'REP-'.date('Ym').'-'.str_pad($i,4,'0',STR_PAD_LEFT),'stock_id'=>$stocks->random()->id,'almacen_origen_id'=>$almacenes[0]->id,'almacen_destino_id'=>$almacenes[min(1,$almacenes->count()-1)]->id,'empresa_id'=>$empresa->id,'centro_id'=>$centro->id,'cantidad'=>rand(2,15),'estado'=>$estados[$i % 4],'fecha_solicitud'=>now()->subDays(rand(1,20)),'solicitado_por'=>'Admin']
            );
        }
    }

    private function seedCitas(): void
    {
        $mecanicos = Mecanico::with('taller')->get();
        if ($mecanicos->isEmpty()) return;
        $empresa = Empresa::first();

        $clientes = ['Ana López','Pedro Suárez','María García','Carlos Díaz','Laura Medina','Roberto Fernández','Elena Cruz','Pablo Martín'];
        $vehiculos = ['Nissan Qashqai 2023','Renault Clio 2022','Dacia Duster 2024','Nissan Juke 2023','Renault Captur 2022','Dacia Sandero 2024'];
        $descripciones = ['Revisión de los 30.000 km','Cambio de aceite y filtros','Ruido en la dirección','Revisión ITV','Cambio de neumáticos','Diagnóstico motor','Reparación aire acondicionado','Cambio pastillas de freno'];
        $estados = ['pendiente','confirmada','en_curso','completada','cancelada'];

        for ($i = 0; $i < 15; $i++) {
            $mec = $mecanicos->random();
            $fecha = now()->addDays(rand(-10, 14));
            $hora = rand(8, 16);
            CitaTaller::create([
                'mecanico_id'=>$mec->id,'taller_id'=>$mec->taller_id,'empresa_id'=>$empresa->id,
                'cliente_nombre'=>$clientes[array_rand($clientes)],'vehiculo_info'=>$vehiculos[array_rand($vehiculos)],
                'fecha'=>$fecha->format('Y-m-d'),'hora_inicio'=>sprintf('%02d:00',$hora),'hora_fin'=>sprintf('%02d:00',$hora+1),
                'descripcion'=>$descripciones[array_rand($descripciones)],'estado'=>$estados[array_rand($estados)],
            ]);
        }
    }

    private function seedCochesSustitucion(): void
    {
        $talleres = Taller::all();
        if ($talleres->isEmpty()) return;
        $empresa = Empresa::first();

        $coches = [
            ['matricula'=>'1234 GKL','modelo'=>'Dacia Sandero','marca_id'=>3,'color'=>'Blanco','anio'=>2023],
            ['matricula'=>'5678 HMN','modelo'=>'Renault Clio','marca_id'=>2,'color'=>'Gris','anio'=>2022],
            ['matricula'=>'9012 JPQ','modelo'=>'Nissan Micra','marca_id'=>1,'color'=>'Rojo','anio'=>2023],
            ['matricula'=>'3456 KRS','modelo'=>'Dacia Sandero Stepway','marca_id'=>3,'color'=>'Azul','anio'=>2024],
            ['matricula'=>'7890 LTV','modelo'=>'Renault Clio','marca_id'=>2,'color'=>'Negro','anio'=>2023],
            ['matricula'=>'2345 MWX','modelo'=>'Nissan Juke','marca_id'=>1,'color'=>'Blanco','anio'=>2022],
        ];

        foreach ($coches as $i => $c) {
            $coche = CocheSustitucion::firstOrCreate(
                ['matricula' => $c['matricula']],
                [...$c,'taller_id'=>$talleres[$i % $talleres->count()]->id,'empresa_id'=>$empresa->id,'disponible'=>true]
            );
            if ($i < 3) {
                ReservaSustitucion::firstOrCreate(
                    ['coche_id'=>$coche->id,'cliente_nombre'=>'Cliente Ejemplo '.($i+1)],
                    ['coche_id'=>$coche->id,'cliente_nombre'=>'Cliente Ejemplo '.($i+1),'fecha_inicio'=>now()->subDays(3),'fecha_fin'=>now()->addDays(4),'estado'=>'reservado']
                );
            }
        }
    }

    private function seedNamingPcs(): void
    {
        $empresa = Empresa::first();
        $centro = Centro::first();

        $pcs = [
            ['nombre'=>'ARI-LP-PC001','tipo'=>'Sobremesa','ubi'=>'Recepción LP','so'=>'Windows 11','ver'=>'PRO','ip'=>'192.168.1.10'],
            ['nombre'=>'ARI-LP-PC002','tipo'=>'Sobremesa','ubi'=>'Administración LP','so'=>'Windows 11','ver'=>'PRO','ip'=>'192.168.1.11'],
            ['nombre'=>'ARI-LP-PT001','tipo'=>'Portátil','ubi'=>'Comercial LP','so'=>'Windows 11','ver'=>'PRO','ip'=>'192.168.1.20'],
            ['nombre'=>'ARI-LP-PT002','tipo'=>'Portátil','ubi'=>'Gerencia LP','so'=>'macOS Sonoma','ver'=>'PRO','ip'=>'192.168.1.21'],
            ['nombre'=>'ARI-TF-PC001','tipo'=>'Sobremesa','ubi'=>'Recepción TF','so'=>'Windows 10','ver'=>'HOME','ip'=>'192.168.2.10'],
            ['nombre'=>'ARI-TF-PT001','tipo'=>'Portátil','ubi'=>'Comercial TF','so'=>'Windows 11','ver'=>'PRO','ip'=>'192.168.2.20'],
            ['nombre'=>'ARI-LZ-PC001','tipo'=>'Sobremesa','ubi'=>'Recepción LZ','so'=>'Windows 11','ver'=>'HOME','ip'=>'192.168.3.10'],
            ['nombre'=>'ARI-FV-PC001','tipo'=>'Sobremesa','ubi'=>'Recepción FV','so'=>'Windows 10','ver'=>'PRO','ip'=>'192.168.4.10'],
            ['nombre'=>'ARI-LP-PT003','tipo'=>'Portátil','ubi'=>'Taller LP','so'=>'Ubuntu 24.04 LTS','ver'=>'PRO','ip'=>'192.168.1.30'],
            ['nombre'=>'ARI-LP-PC003','tipo'=>'Sobremesa','ubi'=>'Recambios LP','so'=>'Windows 11','ver'=>'PRO','ip'=>'192.168.1.12'],
        ];

        foreach ($pcs as $pc) {
            NamingPc::firstOrCreate(
                ['nombre_equipo' => $pc['nombre']],
                ['nombre_equipo'=>$pc['nombre'],'tipo'=>$pc['tipo'],'ubicacion'=>$pc['ubi'],'sistema_operativo'=>$pc['so'],'version_so'=>$pc['ver'],'direccion_ip'=>$pc['ip'],'direccion_mac'=>sprintf('%02X:%02X:%02X:%02X:%02X:%02X',rand(0,255),rand(0,255),rand(0,255),rand(0,255),rand(0,255),rand(0,255)),'empresa_id'=>$empresa->id,'centro_id'=>$centro->id,'activo'=>true]
            );
        }
    }

    private function seedVentas(): void
    {
        $vehiculos = Vehiculo::all();
        $clientes = Cliente::all();
        if ($vehiculos->isEmpty() || $clientes->isEmpty()) return;
        $empresa = Empresa::first();
        $centro = Centro::first();
        $user = User::first();

        $formas = ['contado','financiado','leasing','renting'];
        $estados = ['reservada','pendiente_entrega','entregada','cancelada'];

        for ($i = 1; $i <= 8; $i++) {
            $precio = rand(15000, 55000);
            $descuento = rand(0, 3000);
            Venta::firstOrCreate(
                ['codigo_venta' => 'VTA-'.date('Ym').'-'.str_pad($i,4,'0',STR_PAD_LEFT)],
                ['codigo_venta'=>'VTA-'.date('Ym').'-'.str_pad($i,4,'0',STR_PAD_LEFT),'vehiculo_id'=>$vehiculos->random()->id,'cliente_id'=>$clientes->random()->id,'empresa_id'=>$empresa->id,'centro_id'=>$centro->id,'marca_id'=>rand(1,3),'vendedor_id'=>$user->id,'precio_venta'=>$precio,'descuento'=>$descuento,'precio_final'=>$precio-$descuento,'forma_pago'=>$formas[array_rand($formas)],'estado'=>$estados[array_rand($estados)],'fecha_venta'=>now()->subDays(rand(1,60))]
            );
        }
    }

    private function seedTasaciones(): void
    {
        $clientes = Cliente::all();
        $empresa = Empresa::first();
        $user = User::first();

        $coches = [
            ['marca'=>'Nissan','modelo'=>'Qashqai','anio'=>2019,'km'=>65000],
            ['marca'=>'Renault','modelo'=>'Clio','anio'=>2020,'km'=>42000],
            ['marca'=>'Dacia','modelo'=>'Duster','anio'=>2018,'km'=>95000],
            ['marca'=>'Seat','modelo'=>'León','anio'=>2017,'km'=>120000],
            ['marca'=>'Volkswagen','modelo'=>'Golf','anio'=>2021,'km'=>35000],
            ['marca'=>'Toyota','modelo'=>'Corolla','anio'=>2020,'km'=>50000],
        ];

        $estados = ['pendiente','valorada','aceptada','rechazada'];
        $estVeh = ['excelente','bueno','regular','malo'];

        foreach ($coches as $i => $c) {
            $valor = rand(8000, 28000);
            Tasacion::firstOrCreate(
                ['codigo_tasacion' => 'TAS-'.date('Ym').'-'.str_pad($i+1,4,'0',STR_PAD_LEFT)],
                ['codigo_tasacion'=>'TAS-'.date('Ym').'-'.str_pad($i+1,4,'0',STR_PAD_LEFT),'cliente_id'=>$clientes->count() > 0 ? $clientes->random()->id : null,'empresa_id'=>$empresa->id,'tasador_id'=>$user->id,'vehiculo_marca'=>$c['marca'],'vehiculo_modelo'=>$c['modelo'],'vehiculo_anio'=>$c['anio'],'kilometraje'=>$c['km'],'matricula'=>sprintf('%04d %s', rand(1000,9999), chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90))),'combustible'=>['Gasolina','Diésel','Híbrido'][rand(0,2)],'estado_vehiculo'=>$estVeh[array_rand($estVeh)],'valor_estimado'=>$valor,'valor_final'=>$i < 3 ? $valor - rand(500,1500) : null,'estado'=>$estados[array_rand($estados)],'fecha_tasacion'=>now()->subDays(rand(1,45))]
            );
        }
    }

    private function seedVacaciones(): void
    {
        $users = User::all();
        if ($users->count() < 2) return;

        $estados = ['pendiente','aprobada','rechazada'];
        foreach ($users->take(4) as $i => $user) {
            Vacacion::firstOrCreate(
                ['user_id' => $user->id, 'fecha_inicio' => now()->addDays(30 + ($i * 15))->format('Y-m-d')],
                ['user_id'=>$user->id,'fecha_inicio'=>now()->addDays(30+($i*15)),'fecha_fin'=>now()->addDays(35+($i*15)),'dias_solicitados'=>5,'estado'=>$estados[$i % 3],'motivo'=>['Vacaciones de verano','Asuntos personales','Viaje familiar','Descanso'][$i % 4],'aprobado_por'=>$i > 0 ? $users->first()->id : null]
            );
        }
    }

}
