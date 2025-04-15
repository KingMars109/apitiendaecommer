<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Muestra el listado de usuarios.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                "message" => "Listado de usuarios",
                "status" => 200,
                "data" => $users
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los datos",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|email|unique:users,email|max:100',
                'password' => 'required|string|min:6|max:100',
                'role' => 'required|string|max:50',
                'status' => 'required|boolean'
            ]);

            $user = User::create($request->all());

            return response()->json([
                "message" => "Usuario creado correctamente",
                "status" => 201,
                "data" => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el usuario",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Muestra un usuario en específico.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                "message" => "Información del usuario",
                "status" => 200,
                "data" => $user
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Usuario no encontrado",
                "status" => 404
            ], 404);
        }
    }

    /**
     * Actualiza los datos de un usuario.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|email|unique:users,email,' . $id . '|max:100',
                'password' => 'nullable|string|min:6|max:100',
                'role' => 'required|string|max:50',
                'status' => 'required|boolean'
            ]);

            $user->update($request->all());

            return response()->json([
                "message" => "Usuario actualizado correctamente",
                "status" => 200,
                "data" => $user
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Usuario no encontrado",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar el usuario",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                "message" => "Usuario eliminado correctamente",
                "status" => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Usuario no encontrado",
                "status" => 404
            ], 404);
        }
    }
}