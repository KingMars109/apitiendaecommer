<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    public function index()
    {
        return response()->json(Producto::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la imagen
            ]);

            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('public/imagenes');
                $validatedData['imagen'] = basename($path);
            }

            $producto = Producto::create($validatedData);

            return response()->json([
                "message" => "Producto creado correctamente",
                "producto" => $producto
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            return response()->json(Producto::findOrFail($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Producto no encontrado", "status" => 404], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $producto = Producto::findOrFail($id);

            $validatedData = $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'precio' => 'sometimes|required|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la imagen
            ]);

            if ($request->hasFile('imagen')) {
                // Eliminar la imagen anterior si existe
                if ($producto->imagen) {
                    Storage::delete('public/imagenes/' . $producto->imagen);
                }
                $path = $request->file('imagen')->store('public/imagenes');
                $validatedData['imagen'] = basename($path);
            }

            $producto->update($validatedData);

            return response()->json([
                "message" => "Producto actualizado correctamente",
                "producto" => $producto
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Producto no encontrado", "status" => 404], 404);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            if ($producto->imagen) {
                Storage::delete('public/imagenes/' . $producto->imagen);
            }
            $producto->delete();

            return response()->json(["message" => "Producto eliminado correctamente"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Producto no encontrado", "status" => 404], 404);
        }
    }
}
