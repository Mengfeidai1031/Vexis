<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LogViewerController extends Controller
{
    private const MAX_LINES = 500;

    private const ALLOWED_LEVELS = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

    public function index(Request $request)
    {
        $files = $this->listLogFiles();
        $currentFile = $this->resolveCurrentFile($request->get('file'), $files);

        $levelFilter = strtolower((string) $request->get('level', ''));
        $search = trim((string) $request->get('q', ''));

        $entries = $currentFile
            ? $this->parseLog($currentFile['path'], $levelFilter, $search)
            : [];

        return view('gestion.logs', [
            'files' => $files,
            'currentFile' => $currentFile,
            'entries' => $entries,
            'levelFilter' => $levelFilter,
            'search' => $search,
            'levels' => self::ALLOWED_LEVELS,
        ]);
    }

    public function stream(Request $request): JsonResponse
    {
        $files = $this->listLogFiles();
        $currentFile = $this->resolveCurrentFile($request->get('file'), $files);
        if (! $currentFile) {
            return response()->json(['entries' => []]);
        }

        $entries = $this->parseLog($currentFile['path'], strtolower((string) $request->get('level', '')), trim((string) $request->get('q', '')));

        return response()->json(['entries' => $entries, 'count' => count($entries)]);
    }

    public function clear(Request $request): RedirectResponse
    {
        $files = $this->listLogFiles();
        $currentFile = $this->resolveCurrentFile($request->get('file'), $files);
        if (! $currentFile) {
            return back()->with('error', 'Archivo de log no válido.');
        }

        File::put($currentFile['path'], '');
        Log::channel('security')->warning('logviewer.clear', [
            'file' => $currentFile['name'],
            'actor_id' => auth()->id(),
        ]);

        return back()->with('success', 'Log limpiado: '.$currentFile['name']);
    }

    public function download(Request $request)
    {
        $files = $this->listLogFiles();
        $currentFile = $this->resolveCurrentFile($request->get('file'), $files);
        if (! $currentFile) {
            abort(404);
        }

        return response()->download($currentFile['path'], $currentFile['name']);
    }

    private function listLogFiles(): array
    {
        $logsPath = storage_path('logs');
        if (! File::isDirectory($logsPath)) {
            return [];
        }

        return collect(File::files($logsPath))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.log'))
            ->map(fn ($f) => [
                'name' => $f->getFilename(),
                'path' => $f->getRealPath(),
                'size' => $f->getSize(),
                'modified' => $f->getMTime(),
            ])
            ->sortByDesc('modified')
            ->values()
            ->all();
    }

    private function resolveCurrentFile(?string $requested, array $files): ?array
    {
        if (empty($files)) {
            return null;
        }
        if ($requested) {
            $normalized = basename($requested);
            foreach ($files as $file) {
                if ($file['name'] === $normalized) {
                    return $file;
                }
            }
        }

        return $files[0];
    }

    private function parseLog(string $path, string $levelFilter, string $search): array
    {
        if (! is_readable($path)) {
            return [];
        }

        // Leer últimas N líneas eficiente.
        $content = $this->tail($path, 4000);
        $lines = preg_split('/\R/', $content) ?: [];

        $pattern = '/^\[(\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2})[^\]]*\] ([a-zA-Z0-9_\-]+)\.([A-Z]+):\s*(.*)$/';
        $entries = [];
        $current = null;

        foreach ($lines as $line) {
            if (preg_match($pattern, $line, $m)) {
                if ($current) {
                    $entries[] = $current;
                }
                $current = [
                    'timestamp' => $m[1],
                    'channel' => $m[2],
                    'level' => strtolower($m[3]),
                    'message' => $m[4],
                    'context' => '',
                ];
            } elseif ($current !== null && $line !== '') {
                $current['context'] .= "\n".$line;
            }
        }
        if ($current) {
            $entries[] = $current;
        }

        if ($levelFilter !== '' && in_array($levelFilter, self::ALLOWED_LEVELS, true)) {
            $entries = array_filter($entries, fn ($e) => $e['level'] === $levelFilter);
        }

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $entries = array_filter($entries, function ($e) use ($needle) {
                return str_contains(mb_strtolower($e['message']), $needle)
                    || str_contains(mb_strtolower($e['context']), $needle)
                    || str_contains(mb_strtolower($e['channel']), $needle);
            });
        }

        return array_slice(array_reverse(array_values($entries)), 0, self::MAX_LINES);
    }

    private function tail(string $path, int $maxLines): string
    {
        $size = filesize($path) ?: 0;
        if ($size === 0) {
            return '';
        }

        $chunkSize = 8192;
        $offset = max(0, $size - ($maxLines * 200));
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return '';
        }
        fseek($handle, $offset);
        $content = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $content;
    }
}
