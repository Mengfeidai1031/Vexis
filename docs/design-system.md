# VEXIS Design System — Guía breve (V5)

> Referencia operativa para construir vistas VEXIS coherentes con marca Grupo DAI.
> Fuente de tokens: `resources/css/design-tokens.css`.
> Componentes Blade: `resources/views/components/`.

## 1. Principios

1. **Una sola fuente de verdad**: todo el color, tipografía, espaciado, sombras, radios y z-index se consumen desde `design-tokens.css`. No se hardcodean hex ni px dentro de vistas.
2. **Accesibilidad primero**: contraste AA mínimo, focus-visible de 3 px, `prefers-reduced-motion` respetado globalmente.
3. **Mobile-first responsivo**: tres breakpoints activos (480, 768, 992, 1200). Layouts colapsan de 2/3/4 columnas a 1 por debajo de 768 px.
4. **Dark mode nativo**: variables redefinidas bajo `[data-theme="dark"]`. Nunca usar colores fijos.
5. **Un componente > cinco variantes**: si el mismo patrón aparece en 3+ vistas, va a `resources/views/components/`.

## 2. Tokens clave

### Color
| Token | Uso |
|---|---|
| `--vx-primary` (`#33AADD`) | Marca VEXIS · CTAs · links · highlights |
| `--vx-primary-bg` | Fondo claro del primario (badges, hover states) |
| `--vx-success` / `-warning` / `-danger` / `-info` | Feedback semántico |
| `--vx-surface` | Fondo de cards y paneles |
| `--vx-surface-alt` | Fondo de secciones anidadas (inputs, paneles internos) |
| `--vx-border` / `--vx-border-strong` | Bordes sutiles / marcados |
| `--vx-text` / `--vx-text-secondary` / `--vx-text-muted` | Jerarquía tipográfica |

### Tipografía
- Font: `Plus Jakarta Sans`. Monospace: `JetBrains Mono` (matrículas, DNIs, códigos postales, importes).
- Escala: `--vx-text-xs` (11) · `sm` (12) · `base` (13) · `md` (14) · `lg` (16) · `xl` (18) · `2xl` (22) · `3xl` (28).
- Pesos: 400 (regular), 500 (medium), 600 (semibold), 700 (bold), 800 (black, titulares).

### Espaciado
Escala 4 px: `--vx-space-1..16` (4, 8, 12, 16, 20, 24, 32, 40, 48, 64). Grids de formularios usan `--vx-space-4` (16 px).

### Radios
`--vx-radius-xs` (4) · `sm` (6) · base (8) · `lg` (12, cards y paneles) · `xl` (16) · `full` (pills y avatars).

### Sombras
`--vx-shadow-xs`, `-sm`, base, `-md`, `-lg`, `-xl`, `-focus` (ring de enfoque 3 px), `-focus-danger` (para campos inválidos).

### Z-index
`sticky` (5) · `modulebar` (990) · `navbar` (1000) · `dropdown` (1100) · `submenu` (1200) · `mobile-menu` (1300) · `modal` (2000) · `toast` (2500) · `loader` (99999).

## 3. Componentes Blade

Todos en `resources/views/components/`. Import con `<x-nombre>`.

| Componente | Uso | Props clave |
|---|---|---|
| `<x-page-header>` | Cabecera de cada vista con título, subtítulo, icono, botón volver y acciones | `title`, `subtitle`, `icon`, `back`, `backLabel` |
| `<x-section-title>` | Subtítulo de sección dentro de una vista | `title`, `icon`, `count` |
| `<x-form-field>` | Wrapper label + slot + error + hint | `label`, `name`, `required`, `hint`, `wide` |
| `<x-searchable-select>` | FK dropdown con buscador (auto-init JS) | `name`, `options`, `selected`, `placeholder`, `required`, `empty`, `optionValue`, `optionLabel` |
| `<x-data-table>` | Card + tabla + empty-state + paginación | `count`, `empty`, `emptyIcon`, `emptyCta` |
| `<x-empty-state>` | Estado vacío reusable | `icon`, `title`, `message`, `cta`, `ctaLabel` |
| `<x-stat-card>` | KPI de dashboard | `label`, `value`, `icon`, `color`, `href`, `trend`, `trendDirection` |
| `<x-action-menu>` + `<x-action-item>` | Menú de acciones en filas | `type`, `href`, `icon`, `label`, `as`, `confirm` |
| `<x-modal>` | Modal reusable controlado por `data-open-modal` / `data-close-modal` | `id`, `title`, `icon`, `size` |
| `<x-breadcrumb>` | Migas de pan | `items[]` |
| `<x-info-row>` | Fila label · valor para vistas `show` | `label`, `value`, `icon`, `mono` |
| `<x-badge>` | Badge semántico | `type`, `icon`, `mono` |
| `<x-filtros-avanzados>` | Cabecera de filtros para `/index` (existía) | `action` |
| `<x-columna-ordenable>` | Cabecera ordenable de tabla (existía) | `campo`, `label` |

### Patrón recomendado para `/index`

```blade
<x-page-header title="X" :back="..." icon="...">
    <a class="vx-btn vx-btn-primary">Nuevo</a>
</x-page-header>

<x-filtros-avanzados :action="route('x.index')">
    {{-- Filtros EN EL MISMO ORDEN que las columnas de la tabla --}}
</x-filtros-avanzados>

<x-data-table :count="$rows->count()">
    <x-slot:head>
        <tr>
            <x-columna-ordenable campo="id" label="ID" />
            ...
            <th>Acciones</th>
        </tr>
    </x-slot:head>
    @foreach($rows as $r)
        <tr>...
            <td>
                <x-action-menu>
                    <x-action-item type="view" :href="route('x.show', $r)" />
                    <x-action-item type="edit" :href="route('x.edit', $r)" />
                    <x-action-item type="delete" :href="route('x.destroy', $r)" as="form" />
                </x-action-menu>
            </td>
        </tr>
    @endforeach
    <x-slot:pagination>{{ $rows->links('vendor.pagination.vexis') }}</x-slot:pagination>
</x-data-table>
```

### Patrón recomendado para `/create` y `/edit`

```blade
<x-page-header title="..." icon="..." :back="route('x.index')" />

<div style="max-width: 800px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="..." method="POST">
            @csrf @method('...')

            <x-section-title title="Datos" icon="bi-person" />
            <div class="vx-form-grid">
                <x-form-field label="Nombre" name="nombre" required>
                    <input type="text" name="nombre" class="vx-input" required>
                </x-form-field>

                <x-form-field label="Empresa" name="empresa_id" required>
                    <x-searchable-select name="empresa_id" :options="$empresas" :selected="old('empresa_id')" required />
                </x-form-field>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;border-top:1px solid var(--vx-border);padding-top:16px;margin-top:16px;">
                <a class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary">Guardar</button>
            </div>
        </form>
    </div></div>
</div>
```

## 4. Reglas irrenunciables

1. **Orden de filtros = orden de columnas** en todas las vistas `/index`. Siempre. Revisión obligatoria al crear nuevo CRUD.
2. **FK con 5+ opciones → `<x-searchable-select>`**. Nunca un `<select>` plano.
3. **FK con `.create` → añadir `<a class="vx-select-create">`** debajo del select.
4. **Flash messages globales** — no duplicar `@if(session('success'))` en vistas.
5. **Campos monoespaciados**: matrículas, chasis, DNIs, códigos postales, importes grandes.
6. **Iconos Bootstrap** (`bi-*`) — sin mezclar con otras familias.
7. **Nunca tocar**: IDs de formulario, `name` de inputs, nombres de rutas, orden de campos en `$fillable`.
8. **Responsive**: usar `vx-form-grid` (colapsa a 1 col <768 px) en vez de CSS grid inline.
9. **Dark mode**: consumir tokens `--vx-*`, nunca hex fijos. Si necesitas override, usar `[data-theme="dark"]`.

## 5. Cómo añadir un nuevo componente

1. Crear `resources/views/components/nombre.blade.php` con `@props([...])`.
2. Encapsular estilos en `@once @push('styles')...@endpush @endonce`.
3. Encapsular scripts en `@once @push('scripts')...@endpush @endonce` — usar flag `window.__vxComponenteInit` para evitar doble bind.
4. Documentar en este archivo (tabla de Componentes) + añadir ejemplo.
5. Respetar tokens: 0 hex, 0 px fuera del sistema.

## 6. Checklist de revisión (antes de merge)

- [ ] Orden de filtros coincide con columnas
- [ ] Todos los FK >5 usan `<x-searchable-select>`
- [ ] Todos los FK con `.create` tienen enlace "Crear nuevo"
- [ ] Vista probada en 375 px, 768 px y 1280 px
- [ ] Vista probada en modo oscuro (`localStorage.setItem('vexis-theme','dark')`)
- [ ] Keyboard: tab-order lógico, focus-visible activo
- [ ] No se duplican flash messages
- [ ] Iconos semánticos (no decorativos) tienen `aria-label`
- [ ] `php artisan view:clear && php artisan view:cache` sin errores
