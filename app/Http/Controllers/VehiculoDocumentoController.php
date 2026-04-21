<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\VehiculoDocumento;
use App\Services\VehiculoEstadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehiculoDocumentoController extends Controller
{
    public function __construct(
        private readonly VehiculoEstadoService $estadoService,
    ) {}

    public function store(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $this->authorize('update', $vehiculo);

        $request->validate([
            'tipo' => 'required|in:'.implode(',', array_keys(VehiculoDocumento::$tipos)),
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'fecha_vencimiento' => 'nullable|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $archivo = $request->file('archivo');
        $ruta = $archivo->store("vehiculos/{$vehiculo->id}/documentos", 'public');

        $documento = VehiculoDocumento::create([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'tipo' => $request->tipo,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta' => $ruta,
            'mime' => $archivo->getClientMimeType(),
            'tamano_bytes' => $archivo->getSize(),
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'observaciones' => $request->observaciones,
        ]);

        $this->estadoService->registrar($vehiculo, 'documento_subido', "Documento {$documento->tipo_etiqueta}: {$documento->nombre_original}");

        return back()->with('success', 'Documento añadido correctamente.');
    }

    public function destroy(VehiculoDocumento $documento): RedirectResponse
    {
        $this->authorize('update', $documento->vehiculo);

        Storage::disk('public')->delete($documento->ruta);
        $this->estadoService->registrar($documento->vehiculo, 'documento_eliminado', "Documento {$documento->tipo_etiqueta}: {$documento->nombre_original}");
        $documento->delete();

        return back()->with('success', 'Documento eliminado.');
    }

    public function download(VehiculoDocumento $documento)
    {
        $this->authorize('view', $documento->vehiculo);

        return Storage::disk('public')->download($documento->ruta, $documento->nombre_original);
    }
}
