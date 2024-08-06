<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Trabajadores;
use Illuminate\Support\Facades\Storage;

class ActividadesController extends Controller
{
    public function index()
    {
        $actividades = Actividades::with('trabajadores')->get();
        $trabajadores = Trabajadores::all();
        return view('actividades.index', compact('actividades', 'trabajadores'));
    }

    public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'trabajadores_id' => 'required|exists:trabajadores,id',
        'descripcion' => 'required|string',
        'ayuda' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx',
    ]);

    $filePath = null;

    if ($request->hasFile('ayuda')) {
        $filePath = $request->file('ayuda')->store('uploads', 'public'); // Guardar en storage/app/public/uploads
    }

    $actividad = Actividades::create([
        'titulo' => $request->input('titulo'),
        'trabajadores_id' => $request->input('trabajadores_id'),
        'descripcion' => $request->input('descripcion'),
        'ayuda' => $filePath, // Guardar la ruta del archivo
    ]);

    if ($request->ajax()) {
        return response()->json(['success' => true, 'actividad' => $actividad]);
    }

    return redirect()->route('actividades.index')->with('success', 'Actividad creada exitosamente.');
}
}
