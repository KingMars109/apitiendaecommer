<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductosController extends Controller
{
    public function index()
    {
        try {
            $productos = Producto::all()->map(function ($producto) {
                $producto->imagen_url = $producto->imagen 
                    ? Storage::disk('public')->url('imagenes/' . $producto->imagen)
                    : null;
                return $producto;
            });
            return response()->json([
                "message" => "Listado de productos",
                "status" => 200,
                "data" => $productos
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los productos",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('imagenes', 'public');
                $validatedData['imagen'] = basename($path);
            }

            $producto = Producto::create($validatedData);
            $producto->imagen_url = $producto->imagen 
                ? Storage::disk('public')->url('imagenes/' . $producto->imagen)
                : null;

            return response()->json([
                "message" => "Producto creado correctamente",
                "status" => 201,
                "data" => $producto
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el producto",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->imagen_url = $producto->imagen 
                ? Storage::disk('public')->url('imagenes/' . $producto->imagen)
                : null;
            return response()->json([
                "message" => "Información del producto",
                "status" => 200,
                "data" => $producto
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Producto no encontrado",
                "status" => 404
            ], 404);
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
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior
                if ($producto->imagen) {
                    Storage::disk('public')->delete('imagenes/' . $producto->imagen);
                }
                
                // Guardar nueva imagen
                $path = $request->file('imagen')->store('imagenes', 'public');
                $validatedData['imagen'] = basename($path);
            }

            $producto->update($validatedData);
            $producto->imagen_url = $producto->imagen 
                ? Storage::disk('public')->url('imagenes/' . $producto->imagen)
                : null;

            return response()->json([
                "message" => "Producto actualizado correctamente",
                "status" => 200,
                "data" => $producto
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Producto no encontrado",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar el producto",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            if ($producto->imagen) {
                Storage::disk('public')->delete('imagenes/' . $producto->imagen);
            }
            $producto->delete();

            return response()->json([
                "message" => "Producto eliminado correctamente",
                "status" => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Producto no encontrado",
                "status" => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al eliminar el producto",
                "status" => 500
            ], 500);
        }
    }
}
