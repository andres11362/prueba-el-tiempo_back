<?php

namespace App\Http\Controllers\API;

use App\Helpers\HeaderTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Mail\RegisterUser;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use stdClass;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**W
     * Registra un nuevo usuario y envia un correo de datos al mismo
     * 
     * @return response
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $pwd = $this->generateRandomPassword();
            $validated['password'] = $pwd;
            $user = User::create($validated);
            $user->createToken('authToken')->accessToken;
            $data = $this->getEmailData($user, $pwd);
            Mail::to($request->email)->send(new RegisterUser($data));
            DB::commit();
            return response()->json(['estado' => 'OK', 'message' => '¡Usuario creado correctamente!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()]);
        }
    }

    /**
     * Deja ingresar un usuario a la plataforma, si las credenciales son validas
     * 
     * @return response
     */
    public function login(LoginRequest $request)
    {
        if (!$request->has('errors')) {
            try {
                if (!auth()->attempt($request->validated())) {
                    return response()->json(['error' => 1, 'message' => 'Credenciales invalidas'], 401);
                }
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                return response(['error' => 0, 'user' => auth()->user(), 'access_token' => $accessToken]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json($request->validated(), 422);
        }
    }

    /**
     * Desconecta al usuario de la sesion 
     * 
     * @return response
     */
    public function logout()
    {
        $user = auth()->user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
        $user->save();
        return response()->json(['estado' => 'logout', 'message' => 'Sesion cerrada correctamente'], 200);
    }

    /**
     * Retorna el usuario autenticado
     * 
     * @return user
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Retorna el usuario autenticado
     * 
     * @return user
     */
    public function userWithNews()
    {
        try {
            $id = Auth::user()->id;

            $user = User::findOrFail($id);

            $news = $user->noticias()->paginate(10);

            $headers = new HeaderTables('noticias');

            $list_header = $headers->getTableColumns();

            return response()->json(['headers' => $list_header, 'user' => $user, 'news' => $news], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Procesa y devuelve un objeto con los datos del usuario para enviar un correo
     * 
     * @return object
     */
    private function getEmailData($user, $pwd)
    {
        $data = new stdClass;
        $data->user = $user->email;
        $data->name = $user->name;
        $data->password = $pwd;
        return $data;
    }

    /**
     * Genera una contraseña aleatoria de 10 digitos para el primer acceso del usuario
     * 
     * @return string
     */
    private function generateRandomPassword()
    {
        $pass = Str::random(10);
        return $pass;
    }
}
