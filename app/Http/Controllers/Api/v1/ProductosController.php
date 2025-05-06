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
        $productos = Producto::all()->map(function ($producto) {
            $producto->imagen_url = $producto->imagen 
                ? Storage::disk('public')->url('imagenes/' . $producto->imagen)
                : null;
            return $producto;
        });
        return response()->json($productos, 200);
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
                "producto" => $producto
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->imagen_url = $producto->imagen 
                ? Storage::disk('public')->url('imagenes/' . $producto->imagen)
                : null;
            return response()->json($producto, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Producto no encontrado"], 404);
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
                "producto" => $producto
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Producto no encontrado"], 404);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
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

            return response()->json(["message" => "Producto eliminado correctamente"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Producto no encontrado"], 404);
        }
    }
}