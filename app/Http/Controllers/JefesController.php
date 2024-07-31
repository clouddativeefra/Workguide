<?php

namespace App\Http\Controllers;

use App\Models\Jefes;
use Illuminate\Http\Request;
use App\Http\Controllers\AreasController;
use App\Models\Areas;

class JefesController extends Controller
{
    public function index()
    {
        $jefes = Jefes::with('area')->get();
        $areas = Areas::all();
        return view('jefes.index', compact('jefes', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'area_id' => 'required',
        ]);

        $jefes = Jefes::create($request->all());
        return response()->json($jefes->load('area'));
    }

    public function edit($id)
    {
        $jefes = Jefes::find($id);
        return response()->json($jefes->load('area'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email',
            'area_id' => 'required',
        ]);

        $jefes = Jefes::find($id);
        $jefes->update($request->all());
        return response()->json($jefes->load('area')); // Cargar también el área relacionada
    }

    public function destroy($id)
    {
        $jefes = Jefes::find($id);
        $jefes->delete();
        return response()->json(['message' => 'Registro eliminado exitosamente']);
    }
}
