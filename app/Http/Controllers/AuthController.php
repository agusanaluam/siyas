<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Master\Group;
use App\Models\Master\Volunteer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }
        return redirect('login');
    }

    public function signIn()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }
        return view('auth.signin');
    }


    public function userSignin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],
        [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',

        ]);

        $credentials = $request->only('email', 'password');
        Log::info('User attempting login', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');
        }

        Log::error('Login failed', ['email' => $credentials['email']]);
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function registration()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }
        $group = Group::orderBy('name', 'asc')->get();

        return view('auth.register', compact('group'));
    }


    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'confirmpassword' => 'required|min:6',
            'group' =>'required'
        ],
         [
            'name.required' => 'Userame is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'confirmpassword.required' => 'Confirm Password is required or mismatch with password',
            'group.required' => 'Group is required'
        ]);

        DB::beginTransaction();

        try {

            // Simpan data ke tabel user_profiles
            $userProfile = Volunteer::create([
                'group_id' => $request->group,
                'name' => $request->name,
                'email' => $request->email,
                'profile_picture' => 'build/img/profiles/avatar-01.jpg'

            ]);

            // Buat token verifikasi
            $token = Str::random(32);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'volunteer_id' => $userProfile->id,
                'remember_token' => $token,
            ]);

            // Kirim email

            $verificationLink = route('email-verification.verify', ['token' => $token, 'email' => $user->email]);
            Mail::to($user->email)->send(new EmailVerificationMail($verificationLink));

            // Commit transaksi jika semuanya berhasil
            DB::commit();


        return redirect()->route('email-verification', ['email' => $user->email])->withSuccess('Register Success, please verify your email');
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();

            // Tangani error
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function emailVerification(Request $request)
    {
        $email = request()->query('email');
        $link = request()->query('link');
        return view('auth.email-verification', compact('email'));
    }

    public function resendVerification(Request $request)
    {
        // Buat token verifikasi
        $token = Str::random(32);

        $user = User::where('email', $request->email)
        ->first();
        $user->updated_at = now();
        $user->remember_token = $token; // Hapus token
        $user->save();

        $verificationLink = route('email-verification.verify', ['token' => $token, 'email' => $user->email]);
        Mail::to($user->email)->send(new EmailVerificationMail($verificationLink));

        return redirect()->route('email-verification', ['email' => $user->email])->withSuccess('Email verification request was resend');

    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', request()->query('email'))
            ->where('remember_token', request()->query('token'))
            ->first();

        if (!$user) {
            return redirect()->route('email-verification', ['email' => $user->email])->withErrors(['error' => 'Invalid token or resource not found.']);
        }
        // Cek apakah token sudah kedaluwarsa
        $expirationTime = $user->updated_at->addHour();
        if (Carbon::now()->greaterThan($expirationTime)) {
            return redirect()->route('email-verification', ['email' => $user->email])->withErrors(['error' => 'Expired verification token. Please resend verification request.']);
        }

        // Tandai email sebagai terverifikasi
        $user->email_verified_at = now();
        $user->updated_at = now();
        $user->remember_token = null; // Hapus token
        $user->save();

        return redirect("login")->withSuccess('Email verification Success, please login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPasswordVerification(Request $request)
    {
        // Buat token verifikasi
        $token = Str::random(60);
        $email = $request->email;

        $user = User::where('email', $email)
            ->first();
        $user->updated_at = now();
        $user->remember_token = $token;
        $user->save();

        $verificationLink = route('reset-password', ['token' => $token, 'email' => $user->email]);
        Mail::to($user->email)->send(new ForgotPasswordMail($verificationLink));
        return view('auth.forgot-password-verification',compact('email'));
    }
    public function resetPassword(Request $request)
    {
        $email = request()->query('email');
        $token = request()->query('token');
        return view('auth.reset-password', compact('email','token'));
    }

    public function resetPasswordVerification(Request $request)
    {
        $request->validate(
            [
                'email' =>'',
                'token' => '',
                'password' => 'required|min:6',
                'confirmpassword' => 'required|min:6',
            ],
            [
                'password.required' => 'Password is required',
                'confirmpassword.required' => 'Confirm Password is required or mismatch with password',
            ]
        );

        $email = $request->email;
        $token = $request->token;
        $user = User::where('email', $email)
            ->where('remember_token', $token)
            ->first();

        if (!$user) {
            return redirect()->route('reset-password', ['email' => $email, 'token' => $token])->withErrors(['error' => 'Invalid token or resource not found.']);
        }
        $user->password = Hash::make($request->password);
        $user->updated_at = now();
        $user->remember_token = null; // Hapus token
        $user->save();

        return redirect("login")->withSuccess('Your Password has been reset, please login');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
