@extends('layouts.app')
@section('title', 'Configurador - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-palette" style="color:var(--vx-success);"></i> Configurador de Vehículos</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="max-width:1000px;margin:0 auto;">
    {{-- PASO 1: Marca --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><span class="cfg-step">1</span> Selecciona marca</h4></div>
        <div class="vx-card-body" style="display:flex;gap:12px;flex-wrap:wrap;">
            @foreach($marcas as $m)
            <a href="{{ route('cliente.configurador', ['marca_id' => $m->id]) }}" class="cfg-brand {{ $marcaId == $m->id ? 'active' : '' }}" style="{{ $marcaId == $m->id ? '--brand-color:'.$m->color.';' : '' }}">
                <span style="font-size:18px;font-weight:800;">{{ $m->nombre }}</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- PASO 2: Modelo --}}
    @if($marcaId && count($modelos) > 0)
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><span class="cfg-step">2</span> Selecciona modelo</h4></div>
        <div class="vx-card-body" style="display:flex;gap:10px;flex-wrap:wrap;">
            @foreach($modelos as $mod)
            <a href="{{ route('cliente.configurador', ['marca_id' => $marcaId, 'modelo' => $mod]) }}" class="cfg-model {{ $modeloSeleccionado == $mod ? 'active' : '' }}">
                <i class="bi bi-car-front"></i> {{ $mod }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- PASO 3: Configurador visual --}}
    @if($modeloSeleccionado && count($versiones) > 0)
    @php $marca = $marcas->firstWhere('id', $marcaId); @endphp
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><span class="cfg-step">3</span> Configura tu {{ $marca->nombre ?? '' }} {{ $modeloSeleccionado }}</h4></div>
        <div class="vx-card-body">
            {{-- Selector de color --}}
            <div style="margin-bottom:20px;">
                <label class="vx-label" style="margin-bottom:8px;">Color exterior</label>
                <div style="display:flex;gap:10px;flex-wrap:wrap;" id="colorPicker">
                    @php $colores = [
                        ['name'=>'Blanco Perla','hex'=>'#F5F5F0','body'=>'#F5F5F0'],
                        ['name'=>'Negro Metalizado','hex'=>'#1A1A1A','body'=>'#1A1A1A'],
                        ['name'=>'Gris Plata','hex'=>'#A8A9AD','body'=>'#A8A9AD'],
                        ['name'=>'Rojo Pasión','hex'=>'#C0392B','body'=>'#C0392B'],
                        ['name'=>'Azul Marino','hex'=>'#2C3E50','body'=>'#2C3E50'],
                        ['name'=>'Azul Eléctrico','hex'=>'#2980B9','body'=>'#2980B9'],
                        ['name'=>'Verde Oliva','hex'=>'#556B2F','body'=>'#556B2F'],
                        ['name'=>'Naranja Atardecer','hex'=>'#E67E22','body'=>'#E67E22'],
                    ]; @endphp
                    @foreach($colores as $i => $color)
                    <button class="cfg-color {{ $i === 0 ? 'active' : '' }}" data-color="{{ $color['hex'] }}" data-name="{{ $color['name'] }}" title="{{ $color['name'] }}" style="background:{{ $color['hex'] }};{{ $color['hex'] === '#F5F5F0' ? 'border:2px solid var(--vx-border);' : '' }}"></button>
                    @endforeach
                </div>
                <p id="colorName" style="font-size:12px;color:var(--vx-text-muted);margin:6px 0 0;">Blanco Perla</p>
            </div>

            {{-- Vista del vehículo --}}
            <div style="margin-bottom:16px;">
                <div style="display:flex;gap:8px;margin-bottom:12px;">
                    <button class="cfg-view active" data-view="exterior-front">Frontal</button>
                    <button class="cfg-view" data-view="exterior-side">Lateral</button>
                    <button class="cfg-view" data-view="exterior-rear">Trasera</button>
                    <button class="cfg-view" data-view="exterior-34">3/4</button>
                    <button class="cfg-view" data-view="interior">Interior</button>
                </div>
                <div id="vehicleDisplay" style="background:var(--vx-bg);border-radius:12px;height:320px;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
                    <svg id="vehicleSVG" viewBox="0 0 600 350" style="width:100%;max-height:100%;"></svg>
                </div>
                <p id="viewLabel" style="text-align:center;font-size:12px;color:var(--vx-text-muted);margin-top:8px;">Vista frontal exterior</p>
            </div>

            {{-- Versiones disponibles --}}
            <h5 style="font-size:13px;font-weight:700;color:var(--vx-text-muted);margin-bottom:12px;">VERSIONES DISPONIBLES</h5>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
                @foreach($versiones as $v)
                <div style="padding:14px 16px;border:1px solid var(--vx-border);border-radius:8px;background:var(--vx-surface);">
                    <div style="font-weight:700;font-size:13px;margin-bottom:4px;">{{ $v->version ?? $v->modelo }}</div>
                    <div style="display:flex;gap:10px;font-size:11px;color:var(--vx-text-muted);margin-bottom:8px;">
                        @if($v->combustible)<span><i class="bi bi-fuel-pump"></i> {{ $v->combustible }}</span>@endif
                        @if($v->potencia_cv)<span><i class="bi bi-speedometer2"></i> {{ $v->potencia_cv }} CV</span>@endif
                    </div>
                    <div style="display:flex;align-items:baseline;gap:6px;">
                        @if($v->precio_oferta)
                        <span style="font-size:18px;font-weight:800;color:var(--vx-success);font-family:var(--vx-font-mono);">{{ number_format($v->precio_oferta, 0, ',', '.') }}€</span>
                        <span style="font-size:12px;text-decoration:line-through;color:var(--vx-text-muted);">{{ number_format($v->precio_base, 0, ',', '.') }}€</span>
                        @else
                        <span style="font-size:18px;font-weight:800;color:var(--vx-primary);font-family:var(--vx-font-mono);">{{ number_format($v->precio_base, 0, ',', '.') }}€</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.cfg-step{display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:var(--vx-primary);color:white;font-size:12px;font-weight:700;margin-right:6px;}
.cfg-brand{display:flex;align-items:center;justify-content:center;padding:16px 28px;border:2px solid var(--vx-border);border-radius:10px;text-decoration:none;color:var(--vx-text);transition:all 0.2s;cursor:pointer;}
.cfg-brand:hover,.cfg-brand.active{border-color:var(--brand-color,var(--vx-primary));box-shadow:0 2px 12px rgba(0,0,0,0.1);transform:translateY(-2px);}
.cfg-brand.active{background:var(--brand-color,var(--vx-primary));color:white;}
.cfg-model{padding:10px 20px;border:1px solid var(--vx-border);border-radius:8px;text-decoration:none;color:var(--vx-text);font-size:13px;font-weight:600;transition:all 0.2s;display:flex;align-items:center;gap:6px;}
.cfg-model:hover,.cfg-model.active{border-color:var(--vx-primary);background:var(--vx-primary);color:white;}
.cfg-color{width:36px;height:36px;border-radius:50%;border:3px solid transparent;cursor:pointer;transition:all 0.2s;flex-shrink:0;}
.cfg-color:hover{transform:scale(1.15);}
.cfg-color.active{border-color:var(--vx-primary);box-shadow:0 0 0 3px rgba(51,170,221,0.3);}
.cfg-view{padding:6px 14px;border:1px solid var(--vx-border);border-radius:6px;background:var(--vx-surface);color:var(--vx-text-muted);font-size:12px;cursor:pointer;transition:all 0.15s;}
.cfg-view:hover,.cfg-view.active{background:var(--vx-primary);color:white;border-color:var(--vx-primary);}
</style>
@endpush

@push('scripts')
<script>
let currentColor = '#F5F5F0';
let currentView = 'exterior-front';
const modelName = '{{ $modeloSeleccionado ?? "" }}';
const brandName = '{{ $marca->nombre ?? "" }}';

const viewLabels = {
    'exterior-front': 'Vista frontal exterior',
    'exterior-side': 'Vista lateral exterior',
    'exterior-rear': 'Vista trasera exterior',
    'exterior-34': 'Vista 3/4 exterior',
    'interior': 'Vista interior'
};

function darken(hex, amt) {
    let r = parseInt(hex.slice(1,3),16), g = parseInt(hex.slice(3,5),16), b = parseInt(hex.slice(5,7),16);
    r = Math.max(0, r - amt); g = Math.max(0, g - amt); b = Math.max(0, b - amt);
    return `#${r.toString(16).padStart(2,'0')}${g.toString(16).padStart(2,'0')}${b.toString(16).padStart(2,'0')}`;
}
function lighten(hex, amt) { return darken(hex, -amt); }

function renderVehicle() {
    const svg = document.getElementById('vehicleSVG');
    const c = currentColor;
    const d = darken(c, 30);
    const l = lighten(c, 30);
    const glass = '#87CEEB';
    const glassDark = '#5DADE2';
    const tire = '#2C2C2C';
    const rim = '#C0C0C0';

    let content = '';
    switch(currentView) {
        case 'exterior-front':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <!-- Body -->
                <path d="M150,240 Q150,160 200,130 L400,130 Q450,160 450,240 L450,260 L150,260 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <!-- Roof -->
                <path d="M200,130 Q210,80 240,70 L360,70 Q390,80 400,130" fill="${d}" stroke="${darken(c,50)}" stroke-width="1.5"/>
                <!-- Windshield -->
                <path d="M210,130 L245,78 L355,78 L390,130 Z" fill="${glass}" opacity="0.7" stroke="${glassDark}" stroke-width="1"/>
                <!-- Grille -->
                <rect x="200" y="200" width="200" height="35" rx="4" fill="#333" stroke="#555" stroke-width="1"/>
                <line x1="220" y1="210" x2="380" y2="210" stroke="#666" stroke-width="1"/>
                <line x1="220" y1="218" x2="380" y2="218" stroke="#666" stroke-width="1"/>
                <line x1="220" y1="226" x2="380" y2="226" stroke="#666" stroke-width="1"/>
                <!-- Headlights -->
                <ellipse cx="175" cy="210" rx="20" ry="18" fill="#FFF8DC" stroke="#DDD" stroke-width="1.5"/>
                <ellipse cx="175" cy="210" rx="10" ry="10" fill="#FFFACD"/>
                <ellipse cx="425" cy="210" rx="20" ry="18" fill="#FFF8DC" stroke="#DDD" stroke-width="1.5"/>
                <ellipse cx="425" cy="210" rx="10" ry="10" fill="#FFFACD"/>
                <!-- Bumper -->
                <path d="M155,260 L145,280 L455,280 L445,260" fill="${darken(c,15)}" stroke="${d}" stroke-width="1"/>
                <!-- Fog lights -->
                <circle cx="185" cy="270" r="8" fill="#FFE4B5" stroke="#DDD"/>
                <circle cx="415" cy="270" r="8" fill="#FFE4B5" stroke="#DDD"/>
                <!-- License plate -->
                <rect x="265" y="248" width="70" height="20" rx="2" fill="white" stroke="#999"/>
                <text x="300" y="262" text-anchor="middle" font-size="9" fill="#333" font-family="monospace">${brandName.substring(0,3).toUpperCase()}</text>
                <!-- Brand -->
                <circle cx="300" cy="180" r="15" fill="#CCC" stroke="#999" stroke-width="1"/>
                <text x="300" y="184" text-anchor="middle" font-size="8" fill="#555" font-weight="bold">${brandName.substring(0,1)}</text>
            `;
            break;
        case 'exterior-side':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <!-- Body lower -->
                <path d="M80,230 L80,200 Q80,180 100,170 L500,170 Q520,180 520,200 L520,230 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <!-- Body upper -->
                <path d="M140,170 L180,100 Q190,90 210,90 L370,90 Q400,90 420,110 L460,170" fill="${c}" stroke="${d}" stroke-width="2"/>
                <!-- Windows -->
                <path d="M190,165 L218,100 L280,100 L280,165 Z" fill="${glass}" opacity="0.7" stroke="${glassDark}" stroke-width="1"/>
                <path d="M285,165 L285,100 L365,100 L410,165 Z" fill="${glass}" opacity="0.7" stroke="${glassDark}" stroke-width="1"/>
                <!-- Door line -->
                <line x1="282" y1="100" x2="282" y2="228" stroke="${d}" stroke-width="1.5"/>
                <!-- Handle -->
                <rect x="310" y="180" width="30" height="6" rx="3" fill="${darken(c,40)}"/>
                <!-- Wheels -->
                <circle cx="160" cy="240" r="35" fill="${tire}"/>
                <circle cx="160" cy="240" r="22" fill="${rim}" stroke="#999" stroke-width="1"/>
                <circle cx="160" cy="240" r="8" fill="#888"/>
                <circle cx="440" cy="240" r="35" fill="${tire}"/>
                <circle cx="440" cy="240" r="22" fill="${rim}" stroke="#999" stroke-width="1"/>
                <circle cx="440" cy="240" r="8" fill="#888"/>
                <!-- Headlight -->
                <path d="M80,190 L80,220 L95,215 L95,195 Z" fill="#FFF8DC" stroke="#DDD"/>
                <!-- Taillight -->
                <path d="M520,190 L520,220 L505,215 L505,195 Z" fill="#E74C3C" stroke="#C0392B"/>
                <!-- Ground shadow -->
                <ellipse cx="300" cy="280" rx="240" ry="10" fill="rgba(0,0,0,0.05)"/>
            `;
            break;
        case 'exterior-rear':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <!-- Body -->
                <path d="M150,240 Q150,160 200,130 L400,130 Q450,160 450,240 L450,260 L150,260 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <!-- Roof -->
                <path d="M200,130 Q210,80 240,70 L360,70 Q390,80 400,130" fill="${d}" stroke="${darken(c,50)}" stroke-width="1.5"/>
                <!-- Rear window -->
                <path d="M215,128 L248,80 L352,80 L385,128 Z" fill="${glass}" opacity="0.6" stroke="${glassDark}" stroke-width="1"/>
                <!-- Taillights -->
                <path d="M155,195 L155,230 L175,230 L175,195 Z" fill="#E74C3C" stroke="#C0392B" stroke-width="1" rx="3"/>
                <path d="M425,195 L425,230 L445,230 L445,195 Z" fill="#E74C3C" stroke="#C0392B" stroke-width="1" rx="3"/>
                <!-- Trunk -->
                <line x1="200" y1="200" x2="400" y2="200" stroke="${d}" stroke-width="1"/>
                <!-- Bumper -->
                <path d="M155,260 L145,280 L455,280 L445,260" fill="${darken(c,15)}" stroke="${d}" stroke-width="1"/>
                <!-- Exhaust -->
                <ellipse cx="210" cy="278" rx="12" ry="6" fill="#555" stroke="#444"/>
                <ellipse cx="390" cy="278" rx="12" ry="6" fill="#555" stroke="#444"/>
                <!-- License plate -->
                <rect x="250" y="242" width="100" height="22" rx="2" fill="white" stroke="#999"/>
                <text x="300" y="257" text-anchor="middle" font-size="10" fill="#333" font-family="monospace">1234 ${brandName.substring(0,3).toUpperCase()}</text>
                <!-- Brand -->
                <text x="300" y="225" text-anchor="middle" font-size="12" fill="${darken(c,60)}" font-weight="bold">${brandName.toUpperCase()}</text>
            `;
            break;
        case 'exterior-34':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <!-- Body - 3/4 perspective -->
                <path d="M100,230 L100,190 Q100,170 130,160 L420,140 Q480,150 490,180 L490,230 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <!-- Side panel darker -->
                <path d="M490,180 L490,230 L540,225 L540,185 Z" fill="${d}" stroke="${darken(c,50)}" stroke-width="1"/>
                <!-- Roof -->
                <path d="M170,160 L210,95 Q220,85 240,85 L360,80 Q400,85 420,100 L440,140" fill="${d}" stroke="${darken(c,50)}" stroke-width="1.5"/>
                <!-- Windshield -->
                <path d="M180,155 L215,98 L340,93 L405,140 Z" fill="${glass}" opacity="0.65" stroke="${glassDark}" stroke-width="1"/>
                <!-- Side window -->
                <path d="M410,138 L350,90 L440,100 L470,148 Z" fill="${glass}" opacity="0.5" stroke="${glassDark}" stroke-width="1"/>
                <!-- Front wheel -->
                <ellipse cx="175" cy="240" rx="38" ry="35" fill="${tire}"/>
                <ellipse cx="175" cy="240" rx="24" ry="22" fill="${rim}" stroke="#999"/>
                <ellipse cx="175" cy="240" rx="8" ry="7" fill="#888"/>
                <!-- Rear wheel -->
                <ellipse cx="435" cy="237" rx="34" ry="32" fill="${tire}"/>
                <ellipse cx="435" cy="237" rx="22" ry="20" fill="${rim}" stroke="#999"/>
                <ellipse cx="435" cy="237" rx="7" ry="6" fill="#888"/>
                <!-- Headlight -->
                <path d="M100,185 L100,220 L120,218 L120,190 Z" fill="#FFF8DC" stroke="#DDD"/>
                <!-- Ground shadow -->
                <ellipse cx="300" cy="278" rx="230" ry="12" fill="rgba(0,0,0,0.06)"/>
            `;
            break;
        case 'interior':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="#1a1a1a" rx="12"/>
                <!-- Dashboard -->
                <path d="M50,180 L550,180 L550,280 L50,280 Z" fill="#2C2C2C"/>
                <path d="M50,170 L550,170 L560,185 L40,185 Z" fill="#333"/>
                <!-- Windshield view -->
                <path d="M60,30 L540,30 L555,170 L45,170 Z" fill="#87CEEB" opacity="0.3"/>
                <!-- Steering wheel -->
                <circle cx="180" cy="230" r="55" fill="none" stroke="#444" stroke-width="8"/>
                <circle cx="180" cy="230" r="20" fill="#333" stroke="#555" stroke-width="2"/>
                <text x="180" y="234" text-anchor="middle" font-size="9" fill="#888" font-weight="bold">${brandName.substring(0,1)}</text>
                <!-- Center screen -->
                <rect x="280" y="190" width="130" height="70" rx="6" fill="#111" stroke="#444" stroke-width="1.5"/>
                <rect x="285" y="195" width="120" height="55" rx="4" fill="#1E3A5F"/>
                <text x="345" y="225" text-anchor="middle" font-size="11" fill="#5DADE2">${brandName}</text>
                <text x="345" y="240" text-anchor="middle" font-size="8" fill="#888">${modelName}</text>
                <!-- AC vents -->
                <rect x="430" y="200" width="50" height="20" rx="3" fill="#222" stroke="#444"/>
                <rect x="430" y="230" width="50" height="20" rx="3" fill="#222" stroke="#444"/>
                <!-- Gear -->
                <rect x="260" y="275" width="60" height="40" rx="5" fill="#222" stroke="#444"/>
                <rect x="275" y="282" width="30" height="10" rx="3" fill="#444"/>
                <!-- Seats hint -->
                <path d="M80,290 Q80,300 120,310 L120,350 L80,350 Z" fill="${c === '#1A1A1A' ? '#333' : darken(c, 60)}" opacity="0.5"/>
                <path d="M520,290 Q520,300 480,310 L480,350 L520,350 Z" fill="${c === '#1A1A1A' ? '#333' : darken(c, 60)}" opacity="0.5"/>
                <!-- Ambient light strip -->
                <line x1="50" y1="178" x2="550" y2="178" stroke="rgba(51,170,221,0.4)" stroke-width="2"/>
            `;
            break;
    }
    svg.innerHTML = content;
}

// Color picker
document.querySelectorAll('.cfg-color').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cfg-color').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentColor = this.dataset.color;
        document.getElementById('colorName').textContent = this.dataset.name;
        renderVehicle();
    });
});

// View selector
document.querySelectorAll('.cfg-view').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cfg-view').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentView = this.dataset.view;
        document.getElementById('viewLabel').textContent = viewLabels[currentView];
        renderVehicle();
    });
});

// Initial render
if (modelName) renderVehicle();
</script>
@endpush
@endsection
