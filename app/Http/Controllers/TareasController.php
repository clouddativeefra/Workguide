<?php

namespace App\Http\Controllers;

use App\Models\Tareas;
use App\Http\Requests\StoreTareasRequest;
use App\Http\Requests\UpdateTareasRequest;
use App\Models\Trabajadores;
use Illuminate\Http\Request;

class TareasController extends Controller
{


    public function index()
    {
        $tareas = Tareas::with('trabajadores')->get();
        $trabajadores = Trabajadores::all();
        return view('tareas.index', compact('tareas', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'cantidad_trabajadores' => 'required|integer',
            'descripcion' => 'required',
            'ayuda' => 'required',
            'trabajadores_id' => 'required|exists:trabajadores,id'
        ]);

        $tareas = Tareas::create($request->all());
        return response()->json($tareas->load('trabajadores'));
    }

    public function edit($id)
    {
        $tareas = Tareas::find($id);
        return response()->json($tareas->load('trabajadores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'cantidad_trabajadores' => 'required|integer',
            'descripcion' => 'required',
            'ayuda' => 'required',
            'trabajadores_id' => 'required|exists:trabajadores,id'
        ]);

        $tareas = Tareas::find($id);
        $tareas->update($request->all());
        return response()->json($tareas->load('trabajadores'));
    }

    public function destroy($id)
    {
        $tareas = Tareas::find($id);
        $tareas->delete();
        return response()->json(['message' => 'Registro eliminado exitosamente']);
    }
}
