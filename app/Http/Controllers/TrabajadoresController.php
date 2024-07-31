<?php

namespace App\Http\Controllers;

use App\Models\Jefes;
use App\Models\Trabajadores;
use App\Http\Requests\StoreTrabajadoresRequest;
use App\Http\Requests\UpdateTrabajadoresRequest;
use Illuminate\Http\Request;

class TrabajadoresController extends Controller
{

    public function index()
    {
        $trabajadores = Trabajadores::with('jefe')->get();
        $jefes = Jefes::all();
        return view('trabajadores.index', compact('trabajadores', 'jefes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'jefe_id' => 'required',
        ]);

        $trabajadores = Trabajadores::create($request->all());
        return response()->json($trabajadores->load('jefe'));
    }

    public function edit($id)
    {
        $trabajadores = Trabajadores::find($id);
        return response()->json($trabajadores->load('jefe'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'jefe_id' => 'required',
        ]);

        $trabajadores = Trabajadores::find($id);
        $trabajadores->update($request->all());
        return response()->json($trabajadores->load('jefe')); // Cambiar 'jefes' a 'jefe'
    }

    public function destroy($id)
    {
        $trabajadores = Trabajadores::find($id);
        $trabajadores->delete();
        return response()->json(['message' => 'Registro eliminado exitosamente']);
    }
}
