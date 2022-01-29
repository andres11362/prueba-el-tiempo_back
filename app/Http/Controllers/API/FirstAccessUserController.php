<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FirstAccess\AccessRequest;

class FirstAccessUserController extends Controller
{
    /**
     * Metodo para validar si un usuario ha accedido por primera vez
     * En el front end se pedira un cambio de contraseña
     * Y se habilitara el acceso a la pagina
     * @return response
     */
    public function firstAccess(AccessRequest $request)
    {
        $user = Auth::user();
        if (!$user->email_verified_at) {
            if (!$request->has('errors')) {
                $validated = $request->validated();
                DB::beginTransaction();
                try {
                    $user->password = $validated['password'];
                    $user->email_verified_at = now();
                    $user->save();
                    DB::commit();
                    return response()->json(['message' => 'Datos verificados correctamente'], 200);
                } catch (\Exception $e) {
                    return response()->json(['message' => $e->getMessage()], 500);
                }
            } else {
                return response()->json($request->validated(), 422);
            }
        } else {
            return response()->json(['message' => 'El usuario ya ha sido validado'], 200);
        }
    }
}
