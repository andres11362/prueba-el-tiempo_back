<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Requests\user\UserRequest;

class UserController extends Controller
{
    /**
     * Se listan todos los usuarios autorizados al sistema
     * Se restringen algunos campos por motivos de seguridad
     * 
     * @return response
     */
    public function index()
    {
        $users = User::paginate(15);

        $users->makeHidden(['email_verified_at', 'created_at', 'updated_at']);

        return response()->json(['users' => $users], 200);
    }

    /**
     * Metodo para que el usuario pueda actualizar sus datos
     * 
     * @return response
    */
    public function update(UserRequest $request)
    {
        $user = Auth::user();

        if (!$request->has('errors')) {
            DB::beginTransaction();
            try {
                $validated = $request->validated();
                $user->name = $validated['name'] ? $validated['name'] : $user->name;
                $user->email = $validated['email'] ? $validated['email'] : $user->email;
                $user->password = $validated['password'] ? $validated['password'] : $user->password;
                $user->save();
                DB::commit();
                return response()->json(['message' => 'Datos actualizados correctamente'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json($request->validated(), 422);
        }
    }
}
