<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\User;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\ResetRequest;

class ForgotController extends Controller
{
    /**
     * Genera una petición por correo que genera un token para solicitud de cambio de contraseña
     * 
     * @return response
     */
    public function forgot(ForgotRequest $request)
    {
        if (!$request->has('errors')) {
            $validated = $request->validated();
            if($this->validateUser($validated['email'])) return response()->json([ 'message' => 'El usuario no existe'], 404);
            $token = Str::random(10);
            DB::beginTransaction();
            try {
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                ]);
                $data = $this->getEmailData($validated['email'], $token);
                Mail::to($validated['email'])->send(new ForgotPassword($data));
                DB::commit();
                return response()->json(['message' => 'Revisa tu correo electronico para hacer el cambio de contraseña'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([ 'message' => $e->getMessage() ], 500);
            }
        } else {
            return response()->json($request->validated(), 422);
        } 
    }
    /**
     * Hace la petición con el cambio de contraseña
     * 
     * @return response
     */
    public function reset(ResetRequest $request)
    {
        if (!$request->has('errors')) {
            $validated = $request->validated();
            $passwordResets = $this->getInfoToken($validated['token']);
            if (!$passwordResets)  return response()->json(['message' => 'No hay peticion asociada'], 400);
            $user = $this->getInfoUser($passwordResets->email);
            if (!$user) return response()->json(['message' => 'No existe petición de cambio para este usuario'], 404);
            DB::beginTransaction();
            try {
                $user->password = $validated['password'];
                $user->save();
                $this->deleteToken($validated['token']);
                DB::commit();
                return response()->json(['message' => 'Contraseña cambiada correctamente'], 200);
            } catch (\Exception $e) {
                return response()->json([ 'message' => $e->getMessage() ], 500);
            }
        } else {
            return response()->json($request->validated(), 422);
        }
    }

    /**
     * Procesa y devuelve un objeto con los datos del usuario para enviar un correo
     * 
     * @return object
    */
    private function getEmailData($email, $token) 
    {
        $data = new stdClass;
        $data->email = $email;
        $data->token = $token;
        return $data;
    }

    private function validateUser($email) 
    {
        $user_exist = false;
        if (User::where('email', $email)->count() == 0) {
           $user_exist = true;
        }
        return $user_exist;
    }

    private function getInfoToken($token) {
        $passwordResets = DB::table('password_resets')->where('token', $token)->first();
        return $passwordResets;
    }

    private function getInfoUser($email) {
        $user = User::where('email', $email)->first();
        return $user;
    }

    private function deleteToken($token) {
        DB::table('password_resets')->where('token', $token)->delete();
    }
}
