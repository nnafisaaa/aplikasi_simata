<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // =======================
    // LOGIN UNTUK APLIKASI
    // =======================
    public function loginAplikasi(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'imei'     => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        $imei = $request->imei;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek IMEI
            if ($user->imei !== $imei) {
                Auth::logout();
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Login gagal, IMEI tidak sesuai'
                ], 401);
            }

            // Buat token API
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'       => 'success',
                'message'      => 'Login berhasil',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user' => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'role'     => $user->role,
                    'unit_id'  => $user->unit_id,
                ]
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Login gagal, username atau password salah'
        ], 401);
    }

    // =======================
    // REGISTER UNTUK APLIKASI
    // =======================
    public function registerAplikasi(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'imei'     => 'required|string|unique:users,imei',
            'role'     => 'required|string|in:guru,siswa',
            'unit_id'  => 'required|exists:units,id',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'imei'     => $validated['imei'],
            'role'     => $validated['role'],
            'unit_id'  => $validated['unit_id'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Registrasi berhasil',
            'access_token' => $token,
            'user' => [
                'id'       => $user->id,
                'username' => $user->username,
                'role'     => $user->role,
                'unit_id'  => $user->unit_id,
            ]
        ], 201);
    }

    // =======================
    // LOGIN UNTUK WEB (Blade)
    // =======================
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'imei'     => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        $imei = $request->imei;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Arahkan sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'tu':
                    return redirect()->route('tu.dashboard');
                case 'kabid':
                    return redirect()->route('kabid.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['role' => 'Role tidak dikenali']);
            }
        }

        return redirect()->back()->withErrors(['login_error' => 'Username atau password salah']);
    }

    // =======================
    // LOGOUT (Web)
    // =======================
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}
