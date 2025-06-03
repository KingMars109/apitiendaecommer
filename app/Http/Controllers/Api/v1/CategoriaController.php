<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    /**
     * Muestra el listado de categorías.
     */
    public function index()
    {
        try {
            $categorias = Categoria::all();
            return response()->json([
                "message" => "Listado de categorías",
                "status" => 200,
                "data" => $categorias
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los datos",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'descripcion' => 'nullable|string|max:255'
            ]);

            $categoria = Categoria::create($request->all());

            return response()->json([
                "message" => "Categoría creada correctamente",
                "status" => 201,
                "data" => $categoria
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear la categoría",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Muestra una categoría específica.
     */
    public function show($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return response()->json([
                "message" => "Información de la categoría",
                "status" => 200,
                "data" => $categoria
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Categoría no encontrada",
                "status" => 404
            ], 404);
        }
    }

    /**
     * Actualiza los datos de una categoría.
     */
    public function update(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'descripcion' => 'nullable|string|max:255'
            ]);

            $categoria->update($request->all());

            return response()->json([
                "message" => "Categoría actualizada correctamente",
                "status" => 200,
                "data" => $categoria
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Categoría no encontrada",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar la categoría",
                "status" => 500
            ], 500);
        }
    }

    /**
     * Elimina una categoría de la base de datos.
     */
    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return response()->json([
                "message" => "Categoría eliminada correctamente",
                "status" => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Categoría no encontrada",
                "status" => 404
            ], 404);
        }
    }

    /**
     * Obtiene todos los productos de una categoría específica.
     */
    public function productos($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return response()->json([
                "message" => "Productos de la categoría",
                "status" => 200,
                "data" => $categoria->productos
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Categoría no encontrada",
                "status" => 404
            ], 404);
        }
    }
}
