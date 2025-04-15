<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Validation\ValidationException;

class EmpleadosController extends Controller
{
    public function index()
    {
        try {
            $empleados = Empleado::all();
            return response()->json([
                "message" => "Listado de empleados",
                "status" => 200,
                "data" => $empleados
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al obtener los datos",
                "status" => 500
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'puesto' => 'required|string|max:50'
            ]);

            $empleado = Empleado::create($request->all());

            return response()->json([
                "message" => "Empleado creado correctamente",
                "status" => 201,
                "data" => $empleado
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear el empleado",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $empleado = Empleado::findOrFail($id);
            return response()->json([
                "message" => "Información del empleado",
                "status" => 200,
                "data" => $empleado
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Empleado no encontrado",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $empleado = Empleado::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'puesto' => 'required|string|max:50'
            ]);

            $empleado->update($request->all());

            return response()->json([
                "message" => "Empleado actualizado correctamente",
                "status" => 200,
                "data" => $empleado
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Empleado no encontrado",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar el empleado",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $empleado = Empleado::findOrFail($id);
            $empleado->delete();

            return response()->json([
                "message" => "Empleado eliminado correctamente",
                "status" => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Empleado no encontrado",
                "status" => 404
            ], 404);
        }
    }
}
