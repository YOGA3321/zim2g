<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(url('/auth/google/callback'))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(url('/auth/google/callback'))
                ->user();
            
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                return redirect('/login')->with('unregistered', 'Akun Google Anda (' . $googleUser->email . ') belum terdaftar di aplikasi ini. Silakan hubungi Admin untuk didaftarkan.');
            }

            // Update info google
            $user->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);

            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {

            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
