@extends('layouts.app')
@section('title', 'Configurador - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Configurador de Vehículos</h1><div class="vx-page-actions"><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>

<div style="max-width:1000px;margin:0 auto;">
    {{-- PASO 1: Marca --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header"><h4><span class="cfg-step">1</span> Selecciona marca</h4></div>
        <div class="vx-card-body" style="display:flex;gap:12px;flex-wrap:wrap;">
            @foreach($marcas as $m)
            @php
                $marcaSlug = Str::lower($m->nombre);
                $logoUrl = file_exists(storage_path("app/public/logos/{$marcaSlug}.png")) ? asset("storage/logos/{$marcaSlug}.png") : null;
            @endphp
            <a href="{{ route('cliente.configurador', ['marca_id' => $m->id]) }}" class="cfg-brand {{ $marcaId == $m->id ? 'active' : '' }}" style="{{ $marcaId == $m->id ? '--brand-color:'.$m->color.';' : '' }}">
                @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $m->nombre }}" style="height:32px;margin-right:8px;{{ $marcaId == $m->id ? 'filter:brightness(10);' : '' }}">
                @endif
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
        <div class="vx-card-header">
            <h4>
                <span class="cfg-step">3</span> Configura tu
                @if($logoMarca)<img src="{{ $logoMarca }}" alt="{{ $marca->nombre }}" style="height:22px;vertical-align:middle;margin:0 4px;">@endif
                {{ $marca->nombre ?? '' }} {{ $modeloSeleccionado }}
            </h4>
        </div>
        <div class="vx-card-body">
            {{-- Selector de color --}}
            <div style="margin-bottom:20px;">
                <label class="vx-label" style="margin-bottom:8px;">Color exterior</label>
                <div style="display:flex;gap:10px;flex-wrap:wrap;" id="colorPicker">
                    @php
                        $coloresBase = [
                            'blanco' => ['name'=>'Blanco Perla','hex'=>'#F5F5F0'],
                            'negro' => ['name'=>'Negro Metalizado','hex'=>'#1A1A1A'],
                            'gris' => ['name'=>'Gris Plata','hex'=>'#A8A9AD'],
                            'rojo' => ['name'=>'Rojo Pasión','hex'=>'#C0392B'],
                            'azul' => ['name'=>'Azul Marino','hex'=>'#2C3E50'],
                            'verde' => ['name'=>'Verde Oliva','hex'=>'#556B2F'],
                            'amarillo' => ['name'=>'Amarillo Solar','hex'=>'#F1C40F'],
                        ];
                        $coloresDisponibles = [];
                        $hasImages = !empty($imagenesDisponibles);
                        if ($hasImages) {
                            foreach ($imagenesDisponibles as $color => $vistas) {
                                if (isset($coloresBase[$color])) {
                                    $coloresDisponibles[$color] = $coloresBase[$color];
                                } else {
                                    $coloresDisponibles[$color] = ['name' => ucfirst($color), 'hex' => '#A8A9AD'];
                                }
                            }
                        } else {
                            $coloresDisponibles = $coloresBase;
                        }
                        $primerColor = array_key_first($coloresDisponibles);
                    @endphp
                    @foreach($coloresDisponibles as $slug => $color)
                    <button class="cfg-color {{ $loop->first ? 'active' : '' }}" data-color="{{ $color['hex'] }}" data-slug="{{ $slug }}" data-name="{{ $color['name'] }}" title="{{ $color['name'] }}" style="background:{{ $color['hex'] }};{{ $color['hex'] === '#F5F5F0' ? 'border:2px solid var(--vx-border);' : '' }}"></button>
                    @endforeach
                </div>
                <p id="colorName" style="font-size:12px;color:var(--vx-text-muted);margin:6px 0 0;">{{ $coloresDisponibles[$primerColor]['name'] ?? '' }}</p>
            </div>

            {{-- Vista del vehículo --}}
            <div style="margin-bottom:16px;">
                <div style="display:flex;gap:8px;margin-bottom:12px;">
                    <button class="cfg-view active" data-view="frontal">Frontal</button>
                    <button class="cfg-view" data-view="lateral">Lateral</button>
                    <button class="cfg-view" data-view="trasera">Trasera</button>
                    <button class="cfg-view" data-view="interior">Interior</button>
                    <button class="cfg-view" data-view="asientos">Asientos</button>
                </div>
                <div id="vehicleDisplay" class="cfg-vehicle-display">
                    @if($hasImages)
                    <img id="vehicleImage" src="" alt="Vehículo" style="width:100%;height:100%;object-fit:contain;display:none;">
                    <div id="noImageMsg" style="display:none;text-align:center;color:var(--vx-text-muted);">
                        <i class="bi bi-image" style="font-size:48px;opacity:0.3;"></i>
                        <p style="margin-top:8px;">Imagen no disponible para esta combinación</p>
                    </div>
                    @endif
                    <svg id="vehicleSVG" viewBox="0 0 600 350" style="width:100%;max-height:100%;{{ $hasImages ? 'display:none;' : '' }}"></svg>
                </div>
                <p id="viewLabel" style="text-align:center;font-size:12px;color:var(--vx-text-muted);margin-top:8px;">Vista frontal</p>
            </div>

            {{-- Versiones disponibles --}}
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--vx-border);">
                <i class="bi bi-list-check" style="color:var(--vx-primary);"></i>
                <h5 style="font-size:13px;font-weight:700;color:var(--vx-text-muted);margin:0;letter-spacing:0.5px;">VERSIONES DISPONIBLES</h5>
                <span class="vx-badge vx-badge-info" style="font-size:10px;">{{ count($versiones) }}</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
                @foreach($versiones as $v)
                <div class="cfg-version">
                    <div class="cfg-version-name">{{ $v->version ?? $v->modelo }}</div>
                    <div class="cfg-version-specs">
                        @if($v->combustible)<span><i class="bi bi-fuel-pump"></i> {{ $v->combustible }}</span>@endif
                        @if($v->potencia_cv)<span><i class="bi bi-speedometer2"></i> {{ $v->potencia_cv }} CV</span>@endif
                    </div>
                    <div class="cfg-version-price">
                        @if($v->precio_oferta)
                        <span class="price-main" style="color:var(--vx-success);">{{ number_format($v->precio_oferta, 0, ',', '.') }}€</span>
                        <span class="price-old">{{ number_format($v->precio_base, 0, ',', '.') }}€</span>
                        @else
                        <span class="price-main" style="color:var(--vx-primary);">{{ number_format($v->precio_base, 0, ',', '.') }}€</span>
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
.cfg-step{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--vx-primary),#2980b9);color:white;font-size:13px;font-weight:700;margin-right:8px;box-shadow:0 2px 8px rgba(51,170,221,0.3);}
.cfg-brand{display:flex;align-items:center;justify-content:center;padding:18px 30px;border:2px solid var(--vx-border);border-radius:12px;text-decoration:none;color:var(--vx-text);transition:all 0.25s cubic-bezier(0.4,0,0.2,1);cursor:pointer;background:var(--vx-surface);position:relative;overflow:hidden;}
.cfg-brand::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent,rgba(255,255,255,0.05));opacity:0;transition:opacity 0.25s;}
.cfg-brand:hover::before{opacity:1;}
.cfg-brand:hover{border-color:var(--brand-color,var(--vx-primary));box-shadow:0 4px 20px rgba(0,0,0,0.12);transform:translateY(-3px);}
.cfg-brand.active{background:var(--brand-color,var(--vx-primary));color:white;border-color:var(--brand-color,var(--vx-primary));box-shadow:0 4px 20px rgba(51,170,221,0.25);}
.cfg-model{padding:12px 22px;border:2px solid var(--vx-border);border-radius:10px;text-decoration:none;color:var(--vx-text);font-size:13px;font-weight:600;transition:all 0.2s;display:flex;align-items:center;gap:8px;background:var(--vx-surface);}
.cfg-model:hover{border-color:var(--vx-primary);transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.08);}
.cfg-model.active{border-color:var(--vx-primary);background:linear-gradient(135deg,var(--vx-primary),#2980b9);color:white;box-shadow:0 4px 16px rgba(51,170,221,0.25);}
.cfg-color{width:40px;height:40px;border-radius:50%;border:3px solid transparent;cursor:pointer;transition:all 0.25s;flex-shrink:0;box-shadow:0 2px 6px rgba(0,0,0,0.15);}
.cfg-color:hover{transform:scale(1.2);box-shadow:0 4px 12px rgba(0,0,0,0.2);}
.cfg-color.active{border-color:var(--vx-primary);box-shadow:0 0 0 3px rgba(51,170,221,0.3),0 4px 12px rgba(0,0,0,0.15);transform:scale(1.1);}
.cfg-view{padding:8px 16px;border:2px solid var(--vx-border);border-radius:8px;background:var(--vx-surface);color:var(--vx-text-muted);font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;letter-spacing:0.3px;}
.cfg-view:hover{border-color:var(--vx-primary);color:var(--vx-primary);}
.cfg-view.active{background:var(--vx-primary);color:white;border-color:var(--vx-primary);box-shadow:0 2px 8px rgba(51,170,221,0.3);}
.cfg-vehicle-display{background:var(--vx-bg);border-radius:16px;height:420px;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;border:1px solid var(--vx-border);box-shadow:inset 0 2px 8px rgba(0,0,0,0.04);}
.cfg-version{padding:16px 20px;border:2px solid var(--vx-border);border-radius:12px;background:var(--vx-surface);transition:all 0.2s;position:relative;overflow:hidden;}
.cfg-version:hover{border-color:var(--vx-primary);box-shadow:0 4px 16px rgba(0,0,0,0.08);transform:translateY(-2px);}
.cfg-version-name{font-weight:700;font-size:14px;margin-bottom:6px;color:var(--vx-text);}
.cfg-version-specs{display:flex;gap:12px;font-size:11px;color:var(--vx-text-muted);margin-bottom:10px;}
.cfg-version-specs span{display:flex;align-items:center;gap:4px;}
.cfg-version-price{display:flex;align-items:baseline;gap:8px;}
.cfg-version-price .price-main{font-size:20px;font-weight:800;font-family:var(--vx-font-mono);line-height:1;}
.cfg-version-price .price-old{font-size:12px;text-decoration:line-through;color:var(--vx-text-muted);}
</style>
@endpush

@push('scripts')
<script>
const imagenesDisponibles = @json($imagenesDisponibles ?? []);
const hasImages = Object.keys(imagenesDisponibles).length > 0;
let currentColorSlug = '{{ $primerColor ?? "blanco" }}';
let currentView = 'frontal';
let currentColor = '{{ $coloresDisponibles[$primerColor ?? "blanco"]["hex"] ?? "#F5F5F0" }}';
const modelName = '{{ $modeloSeleccionado ?? "" }}';
const brandName = '{{ $marca->nombre ?? "" }}';

const viewLabels = {
    'frontal': 'Vista frontal',
    'lateral': 'Vista lateral',
    'trasera': 'Vista trasera',
    'interior': 'Vista interior',
    'asientos': 'Vista asientos'
};

function updateDisplay() {
    if (hasImages) {
        const colorImages = imagenesDisponibles[currentColorSlug];
        const img = document.getElementById('vehicleImage');
        const svg = document.getElementById('vehicleSVG');
        const noMsg = document.getElementById('noImageMsg');

        if (colorImages && colorImages[currentView]) {
            img.src = colorImages[currentView];
            img.style.display = 'block';
            svg.style.display = 'none';
            noMsg.style.display = 'none';
        } else {
            img.style.display = 'none';
            svg.style.display = 'none';
            noMsg.style.display = 'block';
        }
    } else {
        renderVehicleSVG();
    }
    document.getElementById('viewLabel').textContent = viewLabels[currentView] || currentView;
}

function darken(hex, amt) {
    let r = parseInt(hex.slice(1,3),16), g = parseInt(hex.slice(3,5),16), b = parseInt(hex.slice(5,7),16);
    r = Math.max(0, r - amt); g = Math.max(0, g - amt); b = Math.max(0, b - amt);
    return `#${r.toString(16).padStart(2,'0')}${g.toString(16).padStart(2,'0')}${b.toString(16).padStart(2,'0')}`;
}
function lighten(hex, amt) { return darken(hex, -amt); }

function renderVehicleSVG() {
    const svg = document.getElementById('vehicleSVG');
    svg.style.display = 'block';
    const c = currentColor;
    const d = darken(c, 30);
    const glass = '#87CEEB';
    const glassDark = '#5DADE2';
    const tire = '#2C2C2C';
    const rim = '#C0C0C0';

    let content = '';
    const svgView = currentView === 'asientos' ? 'interior' : currentView;

    switch(svgView) {
        case 'frontal':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <path d="M150,240 Q150,160 200,130 L400,130 Q450,160 450,240 L450,260 L150,260 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <path d="M200,130 Q210,80 240,70 L360,70 Q390,80 400,130" fill="${d}" stroke="${darken(c,50)}" stroke-width="1.5"/>
                <path d="M210,130 L245,78 L355,78 L390,130 Z" fill="${glass}" opacity="0.7" stroke="${glassDark}" stroke-width="1"/>
                <rect x="200" y="200" width="200" height="35" rx="4" fill="#333" stroke="#555" stroke-width="1"/>
                <line x1="220" y1="210" x2="380" y2="210" stroke="#666" stroke-width="1"/>
                <line x1="220" y1="218" x2="380" y2="218" stroke="#666" stroke-width="1"/>
                <line x1="220" y1="226" x2="380" y2="226" stroke="#666" stroke-width="1"/>
                <ellipse cx="175" cy="210" rx="20" ry="18" fill="#FFF8DC" stroke="#DDD" stroke-width="1.5"/>
                <ellipse cx="425" cy="210" rx="20" ry="18" fill="#FFF8DC" stroke="#DDD" stroke-width="1.5"/>
                <path d="M155,260 L145,280 L455,280 L445,260" fill="${darken(c,15)}" stroke="${d}" stroke-width="1"/>
                <rect x="265" y="248" width="70" height="20" rx="2" fill="white" stroke="#999"/>
                <text x="300" y="262" text-anchor="middle" font-size="9" fill="#333" font-family="monospace">${brandName.substring(0,3).toUpperCase()}</text>
                <circle cx="300" cy="180" r="15" fill="#CCC" stroke="#999" stroke-width="1"/>
                <text x="300" y="184" text-anchor="middle" font-size="8" fill="#555" font-weight="bold">${brandName.substring(0,1)}</text>
            `;
            break;
        case 'lateral':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <path d="M80,230 L80,200 Q80,180 100,170 L500,170 Q520,180 520,200 L520,230 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <path d="M140,170 L180,100 Q190,90 210,90 L370,90 Q400,90 420,110 L460,170" fill="${c}" stroke="${d}" stroke-width="2"/>
                <path d="M190,165 L218,100 L280,100 L280,165 Z" fill="${glass}" opacity="0.7" stroke="${glassDark}" stroke-width="1"/>
                <path d="M285,165 L285,100 L365,100 L410,165 Z" fill="${glass}" opacity="0.7" stroke="${glassDark}" stroke-width="1"/>
                <line x1="282" y1="100" x2="282" y2="228" stroke="${d}" stroke-width="1.5"/>
                <rect x="310" y="180" width="30" height="6" rx="3" fill="${darken(c,40)}"/>
                <circle cx="160" cy="240" r="35" fill="${tire}"/>
                <circle cx="160" cy="240" r="22" fill="${rim}" stroke="#999" stroke-width="1"/>
                <circle cx="160" cy="240" r="8" fill="#888"/>
                <circle cx="440" cy="240" r="35" fill="${tire}"/>
                <circle cx="440" cy="240" r="22" fill="${rim}" stroke="#999" stroke-width="1"/>
                <circle cx="440" cy="240" r="8" fill="#888"/>
                <path d="M80,190 L80,220 L95,215 L95,195 Z" fill="#FFF8DC" stroke="#DDD"/>
                <path d="M520,190 L520,220 L505,215 L505,195 Z" fill="#E74C3C" stroke="#C0392B"/>
                <ellipse cx="300" cy="280" rx="240" ry="10" fill="rgba(0,0,0,0.05)"/>
            `;
            break;
        case 'trasera':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="var(--vx-bg)" rx="12"/>
                <path d="M150,240 Q150,160 200,130 L400,130 Q450,160 450,240 L450,260 L150,260 Z" fill="${c}" stroke="${d}" stroke-width="2"/>
                <path d="M200,130 Q210,80 240,70 L360,70 Q390,80 400,130" fill="${d}" stroke="${darken(c,50)}" stroke-width="1.5"/>
                <path d="M215,128 L248,80 L352,80 L385,128 Z" fill="${glass}" opacity="0.6" stroke="${glassDark}" stroke-width="1"/>
                <path d="M155,195 L155,230 L175,230 L175,195 Z" fill="#E74C3C" stroke="#C0392B" stroke-width="1" rx="3"/>
                <path d="M425,195 L425,230 L445,230 L445,195 Z" fill="#E74C3C" stroke="#C0392B" stroke-width="1" rx="3"/>
                <line x1="200" y1="200" x2="400" y2="200" stroke="${d}" stroke-width="1"/>
                <path d="M155,260 L145,280 L455,280 L445,260" fill="${darken(c,15)}" stroke="${d}" stroke-width="1"/>
                <rect x="250" y="242" width="100" height="22" rx="2" fill="white" stroke="#999"/>
                <text x="300" y="257" text-anchor="middle" font-size="10" fill="#333" font-family="monospace">1234 ${brandName.substring(0,3).toUpperCase()}</text>
                <text x="300" y="225" text-anchor="middle" font-size="12" fill="${darken(c,60)}" font-weight="bold">${brandName.toUpperCase()}</text>
            `;
            break;
        case 'interior':
            content = `
                <rect x="0" y="0" width="600" height="350" fill="#1a1a1a" rx="12"/>
                <path d="M50,180 L550,180 L550,280 L50,280 Z" fill="#2C2C2C"/>
                <path d="M50,170 L550,170 L560,185 L40,185 Z" fill="#333"/>
                <path d="M60,30 L540,30 L555,170 L45,170 Z" fill="#87CEEB" opacity="0.3"/>
                <circle cx="180" cy="230" r="55" fill="none" stroke="#444" stroke-width="8"/>
                <circle cx="180" cy="230" r="20" fill="#333" stroke="#555" stroke-width="2"/>
                <text x="180" y="234" text-anchor="middle" font-size="9" fill="#888" font-weight="bold">${brandName.substring(0,1)}</text>
                <rect x="280" y="190" width="130" height="70" rx="6" fill="#111" stroke="#444" stroke-width="1.5"/>
                <rect x="285" y="195" width="120" height="55" rx="4" fill="#1E3A5F"/>
                <text x="345" y="225" text-anchor="middle" font-size="11" fill="#5DADE2">${brandName}</text>
                <text x="345" y="240" text-anchor="middle" font-size="8" fill="#888">${modelName}</text>
                <rect x="430" y="200" width="50" height="20" rx="3" fill="#222" stroke="#444"/>
                <rect x="430" y="230" width="50" height="20" rx="3" fill="#222" stroke="#444"/>
                <rect x="260" y="275" width="60" height="40" rx="5" fill="#222" stroke="#444"/>
                <rect x="275" y="282" width="30" height="10" rx="3" fill="#444"/>
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
        currentColorSlug = this.dataset.slug;
        document.getElementById('colorName').textContent = this.dataset.name;
        updateDisplay();
    });
});

// View selector
document.querySelectorAll('.cfg-view').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cfg-view').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentView = this.dataset.view;
        updateDisplay();
    });
});

// Initial render
if (modelName) updateDisplay();
</script>
@endpush
@endsection
