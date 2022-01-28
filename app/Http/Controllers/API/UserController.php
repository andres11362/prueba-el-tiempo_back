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

    public function index()
    {
        $users = User::all();

        $users->makeHidden(['email_verified_at', 'created_at', 'updated_at']);

        return response()->json(['users' => $users], 200);
    }


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
                $user->phone = $validated['phone'] ? $validated['phone'] : $user->phone;
                $user->mobile = $validated['mobile'] ? $validated['mobile'] : $user->mobile;
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