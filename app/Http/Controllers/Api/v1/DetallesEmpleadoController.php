<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Detalles_Empleado;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Validation\ValidationException;

class DetallesEmpleadoController extends Controller
{
    public function index()
    {
        try {
            $detallesEmpleados = Detalles_Empleado::all();
            return response()->json([
                "message" => "Listado de detalles de empleados",
                "status" => 200,
                "data" => $detallesEmpleados
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
            $validatedData = $request->validate([
                'empleado_id' => 'required|integer|exists:empleados,id',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:15',
            ]);

            $detallesEmpleado = Detalles_Empleado::create($validatedData);

            return response()->json([
                "message" => "Detalles del empleado creados correctamente",
                "status" => 201,
                "data" => $detallesEmpleado
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al crear los detalles del empleado",
                "status" => 500
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $detallesEmpleado = Detalles_Empleado::findOrFail($id);
            return response()->json([
                "message" => "Información del detalle del empleado",
                "status" => 200,
                "data" => $detallesEmpleado
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Detalles del empleado no encontrados",
                "status" => 404
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $detallesEmpleado = Detalles_Empleado::findOrFail($id);
            
            $validatedData = $request->validate([
                'empleado_id' => 'required|integer|exists:empleados,id',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:15',
            ]);

            $detallesEmpleado->update($validatedData);

            return response()->json([
                "message" => "Detalles del empleado actualizados correctamente",
                "status" => 200,
                "data" => $detallesEmpleado
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Detalles del empleado no encontrados",
                "status" => 404
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => 422
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error al actualizar los detalles del empleado",
                "status" => 500
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $detallesEmpleado = Detalles_Empleado::findOrFail($id);
            $detallesEmpleado->delete();

            return response()->json([
                "message" => "Detalles del empleado eliminados correctamente",
                "status" => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => "Detalles del empleado no encontrados",
                "status" => 404
            ], 404);
        }
    }
}
