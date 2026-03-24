<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPatchRequest;
use App\Http\Requests\UserPostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list()
    {
        $users = User::select([
            'idUsuario',
            'nombreApellido',
            'email',
            'usuario',
            'rol',
            'estado',
        ])->get();

        return view('user.user', ['users' => $users]);
    }

    public function store(UserPostRequest $request)
    {
        $user = new User();

        $user->nombreApellido = $request->input('name');
        $user->email = $request->input('email');
        $user->usuario = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->rol = $request->input('role');
        $user->estado = $request->input('state');

        $user->save();

        return redirect('usuarios');
    }

    public function update(int $userId, UserPatchRequest $request)
    {
        $user = User::find($userId);

        $user->nombreApellido = $request->input('name', $user->nombreApellido);
        $user->email = $request->input('email', $user->email);
        $user->usuario = $request->input('username', $user->usuario);
        $user->password = $request->input('password') !== null ? Hash::make($request->input('password')) : $user->password;
        $user->rol = $request->input('role', $user->rol);
        $user->estado = $request->input('state', $user->estado);

        $user->save();

        return redirect('usuarios');
    }
}
