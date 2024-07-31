<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Http\Requests\StoreAreasRequest;
use App\Http\Requests\UpdateAreasRequest;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function index()
    {
        $areas = Areas::all();
        return view('areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
        ]);

        $area = Areas::create($request->all());
        return response()->json($area);
    }

    public function edit(Areas $area)
    {
        return response()->json($area);
    }

    public function update(Request $request, Areas $area)
    {
        $request->validate([
            'nombre' => 'required',
        ]);

        $area->update($request->all());
        return response()->json($area);
    }

    public function destroy(Areas $area)
    {
        $area->delete();
        return response()->json(['message' => 'Registro eliminado exitosamente']);
    }
}
