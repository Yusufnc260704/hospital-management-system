<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    private const MAKS_PERCOBAAN = 5;

    private const JEDA_DETIK = 60;

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower(trim($credentials['email']));
        $kunci = $email.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($kunci, self::MAKS_PERCOBAAN)) {
            $detik = RateLimiter::availableIn($kunci);
            $this->catat($request, null, LoginLog::GAGAL, 'Terlalu banyak percobaan masuk', $email);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$detik} detik.",
            ]);
        }

        $user = User::query()->where('email', $email)->first();

        if ($user !== null && ! $user->is_active) {
            RateLimiter::hit($kunci, self::JEDA_DETIK);
            $this->catat($request, $user, LoginLog::GAGAL, 'Akun dinonaktifkan', $email);

            throw ValidationException::withMessages([
                'email' => 'Akun Anda dinonaktifkan. Hubungi administrator rumah sakit.',
            ]);
        }

        if (! Auth::attempt(['email' => $email, 'password' => $credentials['password']], $request->boolean('ingat'))) {
            RateLimiter::hit($kunci, self::JEDA_DETIK);
            $this->catat($request, $user, LoginLog::GAGAL, 'Email atau kata sandi salah', $email);

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        RateLimiter::clear($kunci);
        $request->session()->regenerate();

        $pengguna = $request->user();
        $pengguna->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        $this->catat($request, $pengguna, LoginLog::BERHASIL, null, $email);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('sukses', 'Anda telah keluar dari sistem.');
    }

    private function catat(Request $request, ?User $user, string $status, ?string $alasan, string $email): void
    {
        LoginLog::create([
            'user_id' => $user?->id,
            'email' => $email,
            'role' => $user?->role,
            'status' => $status,
            'alasan' => $alasan,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500),
            'created_at' => now(),
        ]);
    }
}
