<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de importar el modelo User

class PerfilController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('perfil.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user(); // Obtiene el usuario autenticado
        return view('perfil.index', compact('user'));
    }

    public function update(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user(); // Obtiene el usuario autenticado

        // Verifica si la contraseña actual es correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        // Actualiza el nombre y el correo
        $user->name = $request->name;
        $user->email = $request->email;

        // Si se proporciona una nueva contraseña, actualiza la contraseña
        if ($request->new_password) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save(); // Guarda los cambios en la base de datos

        // Redirige al formulario de edición con un mensaje de éxito
        return redirect()->route('perfil.edit', $user->id)->with('success', 'Perfil actualizado con éxito');
    }
}
