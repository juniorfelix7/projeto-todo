<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index(Request $request) {
        return view('login');        
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Login realizado com sucesso!');
        }

        throw ValidationException::withMessages([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ]);
    }

    public function register(Request $request){
        return view('register');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect(route('home'))->with('success', 'Conta criada com sucesso!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect(route('login'))->with('success', 'Logout realizado com sucesso!');
    }
    public function profile(Request $request){
     
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }
    
}
