<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Tambahkan validasi ends_with:@gmail.com di sini
            'email' => ['required', 'string', 'lowercase', 'email:dns', 'max:255', 'unique:'.User::class, 'ends_with:@gmail.com'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            // Tambahkan pesan error kustom dalam Bahasa Indonesia
            'email.ends_with' => 'Pendaftaran hanya diperbolehkan menggunakan alamat @gmail.com.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // 1. Matikan fungsi auto-login bawaan Laravel
        // Auth::login($user);

        // 2. Redirect ke halaman login dan kirim pesan sukses
        return redirect(route('login'))->with('success', 'Akun berhasil dibuat! Silakan login untuk mulai menggunakan aplikasi.');
    }
}