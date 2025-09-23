<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Joyeria;
use Illuminate\Support\Facades\Gate;

class JoyeriaController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Joyeria::class);

        $joyerias = Joyeria::query();

        if ($request->filled('nombre')) {
            $joyerias->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('categoria')) {
            $joyerias->where('categoria', $request->categoria);
        }
        if ($request->filled('min_precio')) {
            $joyerias->where('precio', '>=', $request->min_precio);
        }
        if ($request->filled('max_precio')) {
            $joyerias->where('precio', '<=', $request->max_precio);
        }

        $joyerias = $joyerias->paginate(10);

        return view('joyerias.index', compact('joyerias'));
    }

    public function create()
    {
        Gate::authorize('create', Joyeria::class);
        return view('joyerias.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Joyeria::class);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        Joyeria::create($validated);

        return redirect()->route('joyerias.index')->with('success', 'Joyería creada.');
    }

    public function show(Joyeria $joyeria)
    {
        Gate::authorize('view', $joyeria);
        return view('joyerias.show', compact('joyeria'));
    }

    public function edit(Joyeria $joyeria)
    {
        Gate::authorize('update', $joyeria);
        return view('joyerias.edit', compact('joyeria'));
    }

    public function update(Request $request, Joyeria $joyeria)
    {
        Gate::authorize('update', $joyeria);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $joyeria->update($validated);

        return redirect()->route('joyerias.index')->with('success', 'Joyería actualizada.');
    }

    public function destroy(Joyeria $joyeria)
    {
        Gate::authorize('delete', $joyeria);
        $joyeria->delete();
        return redirect()->route('joyerias.index')->with('success', 'Joyería eliminada.');
    }

    public function report(Request $request)
    {
        Gate::authorize('viewAny', Joyeria::class);

        $query = Joyeria::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('min_precio')) {
            $query->where('precio', '>=', $request->min_precio);
        }
        if ($request->filled('max_precio')) {
            $query->where('precio', '<=', $request->max_precio);
        }

        $reportes = $query->get();

        if ($request->filled('export')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('joyerias.report-pdf', compact('reportes'));
            return $pdf->download('reporte-joyeria.pdf');
        }

        return view('joyerias.report', compact('reportes'));
    }
}
