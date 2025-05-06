<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Validation\ValidationException;

class ClientesController extends Controller
{
    /**
     * Muestra el listado de clientes.
     */
    public function index()
    {
        try {
            $clientes = Cliente::all();
            return response()->json([
                "message" => "Listado de clientes",
                "status" => 200,
                "data" => $clientes
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los datos",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'email' => 'required|email|unique:clientes,email|max:100',
                'telefono' => 'nullable|string|max:20'
            ]);

            $cliente = Cliente::create($request->all());

            return response()->json([
                "message" => "Cliente creado correctamente",
                "status" => 201,
                "data" => $cliente
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el cliente",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Muestra un cliente en específico.
     */
    public function show($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return response()->json([
                "message" => "Información del cliente",
                "status" => 200,
                "data" => $cliente
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Cliente no encontrado",
                "status" => 404
            ], 404);
        }
    }

    /**
     * Actualiza los datos de un cliente.
     */
    public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:15',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($validatedData);

        return response()->json([
            "message" => "Cliente actualizado correctamente",
            "status" => 200,
            "data" => $cliente
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            "message" => $e->getMessage(),
            "status" => 422
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "message" => "Error al actualizar el cliente",
            "status" => 500,
            // Extra opcional: ayuda a debuggear
            "error" => $e->getMessage()
        ]);
    }
}


    /**
     * Elimina un cliente de la base de datos.
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return response()->json([
                "message" => "Cliente eliminado correctamente",
                "status" => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Cliente no encontrado",
                "status" => 404
            ], 404);
        }
    }
}
