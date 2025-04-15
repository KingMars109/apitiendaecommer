<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ProveedorController extends Controller
{
    public function index()
    {
        try {
            $proveedores = Proveedor::all();
            return response()->json([
                "message" => "Listado de proveedores",
                "status" => 200,
                "data" => $proveedores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Error al obtener los proveedores",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:100',
                'telefono' => 'nullable|string|max:20',
                'email' => 'required|string|email|max:100|unique:proveedores,email',
            ]);

            $proveedor = Proveedor::create($validatedData);

            return response()->json([
                "message" => "Proveedor creado correctamente",
                "proveedor" => $proveedor
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            return response()->json(Proveedor::findOrFail($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Proveedor no encontrado", "status" => 404], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);

            $validatedData = $request->validate([
                'nombre' => 'sometimes|required|string|max:100',
                'telefono' => 'sometimes|nullable|string|max:20',
                'email' => 'sometimes|required|string|email|max:100|unique:proveedores,email,' . $id,
            ]);

            $proveedor->update($validatedData);

            return response()->json([
                "message" => "Proveedor actualizado correctamente",
                "proveedor" => $proveedor
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Proveedor no encontrado", "status" => 404], 404);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->delete();

            return response()->json(["message" => "Proveedor eliminado correctamente"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Proveedor no encontrado", "status" => 404], 404);
        }
    }
}
