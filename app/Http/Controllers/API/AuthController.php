<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
// use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'alamat' => 'nullable',
            'nomor_telepon' => 'nullable',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Sukses register',
            'data' => $success
        ]);

    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['email'] = $auth->email;

            $auth->update(['remember_token' => $success['token']]); // Menyimpan token pada kolom remember_token

            return response()->json([
                'success' => true,
                'message' => 'Login sukses',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek email dan password lagi',
                'data' => null
            ]);
        }
    }

    public function logout(Request $request)
    {
        $this->middleware('auth:sanctum');
        
        $request->user()->currentAccessToken()->delete();

        $request->user()->update(['remember_token' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
            'data' => null
        ]);
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$user->id,
        'alamat' => 'nullable',
        'nomor_telepon' => 'nullable',
        'password' => 'nullable',
        'confirm_password' => 'nullable|same:password'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Ada kesalahan',
            'data' => $validator->errors()
        ]);
    }

    $input = $request->only(['name', 'email', 'alamat', 'nomor_telepon']); // Kolom yang diperbarui

    $input['alamat'] = $request->input('alamat', $user->alamat);
    $input['nomor_telepon'] = $request->input('nomor_telepon', $user->nomor_telepon);

    if (!empty($request->input('password'))) {
        $input['password'] = bcrypt($request->input('password'));
    }

    $user->update($input);

    return response()->json([
        'success' => true,
        'message' => 'Data pengguna berhasil diperbarui',
        'data' => $user
    ]);
}


    



}
