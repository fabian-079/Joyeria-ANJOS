<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function __construct()
    {
        // Constructor vacío - las verificaciones de rol se hacen en los métodos individuales
    }

    public function index()
    {
        $repairs = Repair::with(['user', 'assignedTechnician'])->get();
        return view('reparaciones.index', compact('repairs'));
    }

    public function create()
    {
        return view('reparaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['repair_number'] = 'REP-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('repairs', 'public');
        }

        Repair::create($data);

        return redirect()->route('reparaciones.index')->with('success', 'Solicitud de reparación enviada exitosamente');
    }

    public function show(Repair $repair)
    {
        $repair->load(['user', 'assignedTechnician']);
        return view('reparaciones.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $technicians = User::whereHas('roles', function($query) {
            $query->where('name', 'empleado');
        })->get();

        return view('reparaciones.edit', compact('repair', 'technicians'));
    }

    public function update(Request $request, Repair $repair)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'assigned_technician_id' => 'nullable|exists:users,id',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $repair->update($request->all());

        return redirect()->route('reparaciones.index')->with('success', 'Reparación actualizada exitosamente');
    }

    public function destroy(Repair $repair)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        if ($repair->image) {
            Storage::disk('public')->delete($repair->image);
        }
        
        $repair->delete();
        return redirect()->route('reparaciones.index')->with('success', 'Reparación eliminada exitosamente');
    }

    public function assignTechnician(Request $request, Repair $repair)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $request->validate([
            'assigned_technician_id' => 'required|exists:users,id'
        ]);

        $repair->update([
            'assigned_technician_id' => $request->assigned_technician_id,
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', 'Técnico asignado exitosamente');
    }
}