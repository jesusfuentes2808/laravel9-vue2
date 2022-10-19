<?php
namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $response = [];
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                $response['status'] = "FAIL";
                $response['message'] = "Validar datos";
                $response['data'] = $validator->errors();

                return response()->json($response, 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $response['status'] = "OK";
            $response['message'] = "Usuario creado";
            $response['data'] = $user;

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }

    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        try{
            $response = [];

            $validator = Validator::make($request->all(),[
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);

            if ($validator->fails()) {
                $response['status'] = "FAIL";
                $response['message'] = "Validar datos";
                $response['data'] = $validator->errors();

                return response()->json($response, 400);
            }

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)){
                $response['status'] = "FAIL";
                $response['message'] = "Unauthorized";
                $response['data'] = "";

                return response()->json($response, 400);
            }

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');

            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);

            $token->save();


            $response['status'] = "OK";
            $response['message'] = "Validacion";
            $response['data'] =  $tokenResult->accessToken;
            $response['expires_at'] =  Carbon::parse($token->expires_at)->toDateTimeString();

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        try{
            $response['status'] = "OK";
            $response['message'] = "Datos dce usuario";
            $response['data'] = $request->user();

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response['status'] = "FAIL";
            $response['message'] = $e->getMessage();
            $response['data'] = "";

            return response()->json($response, 400);
        }
    }
}
