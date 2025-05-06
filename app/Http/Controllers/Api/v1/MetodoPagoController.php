<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class MetodoPagoController extends Controller
{
    public function index()
    {
        return response()->json(MetodoPago::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cartera_id' => 'required|integer|exists:carteras_electronicas,id',
                'tipo_pago' => 'required|in:tarjeta_credito,tarjeta_debito,transferencia,otro',
                'informacion_pago' => 'required|string|max:255',
                'fecha_expiracion' => 'nullable|date',
            ]);

            $metodoPago = MetodoPago::create($validatedData);

            return response()->json([
                "message" => "Método de pago creado correctamente",
                "metodo_pago" => $metodoPago
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);
            return response()->json($metodoPago, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Método de pago no encontrado", "status" => 404], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);

            $validatedData = $request->validate([
                'cartera_id' => 'sometimes|required|integer|exists:carteras_electronicas,id',
                'tipo_pago' => 'sometimes|required|in:tarjeta_credito,tarjeta_debito,transferencia,otro',
                'informacion_pago' => 'sometimes|required|string|max:255',
                'fecha_expiracion' => 'nullable|date',
            ]);

            $metodoPago->update($validatedData);

            return response()->json([
                "message" => "Método de pago actualizado correctamente",
                "metodo_pago" => $metodoPago
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Método de pago no encontrado", "status" => 404], 404);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);
            $metodoPago->delete();

            return response()->json(["message" => "Método de pago eliminado correctamente"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Método de pago no encontrado", "status" => 404], 404);
        }
    }
}
