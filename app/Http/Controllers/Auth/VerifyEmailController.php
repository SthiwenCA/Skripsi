<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Jika user ternyata SUDAH pernah verifikasi, arahkan ke halaman centang hijau
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('auth.verified');
        }

        // Jika ini adalah pertama kalinya mereka verifikasi, tandai sukses
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Setelah sukses, arahkan ke halaman centang hijau
        return redirect()->route('auth.verified');
    }
}