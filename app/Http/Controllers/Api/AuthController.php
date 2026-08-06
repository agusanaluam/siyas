<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Master\Group;
use App\Models\Master\Volunteer;
use App\Mail\EmailVerificationMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial tidak sesuai dengan data kami.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->level,
                'email_verified_at' => $user->email_verified_at,
            ],
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone_number' => 'nullable|string',
            'role' => 'required|in:relawan,donatur',
            'group_id' => 'required_if:role,relawan|nullable|exists:m_group,id',
        ]);

        DB::beginTransaction();

        try {
            $token = Str::random(60);
            $volunteerId = null;
            $userLevel = 'donatur';

            if ($request->role === 'relawan') {
                $volunteer = Volunteer::create([
                    'group_id' => $request->group_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'profile_picture' => 'profile_pictures/user-01.jpg',
                    'points' => 0,
                ]);
                $volunteerId = $volunteer->id;
                $userLevel = 'volunteer';
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'level' => $userLevel,
                'volunteer_id' => $volunteerId,
                'remember_token' => $token,
            ]);

            $verificationLink = url('/email-verification?email=' . $user->email . '&token=' . $token);
            Mail::to($user->email)->send(new EmailVerificationMail($verificationLink));

            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'level' => $user->level,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'token' => $token,
                'message' => 'Registrasi berhasil. Silakan cek email untuk verifikasi.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Registration Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Terjadi kesalahan saat registrasi.',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->load('profile');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->level,
                'email_verified_at' => $user->email_verified_at,
                'phone_number' => $user->phone_number,
                'volunteer_id' => $user->volunteer_id,
                'profile' => $user->profile,
            ],
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->updated_at = now();
        $user->save();

        $verificationLink = url('/reset-password?token=' . $token . '&email=' . $user->email);
        Mail::to($user->email)->send(new ForgotPasswordMail($verificationLink));

        return response()->json([
            'message' => 'Link reset password telah dikirim ke email Anda.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid atau sudah kedaluwarsa.',
            ], 400);
        }

        $expirationTime = $user->updated_at->addHour();
        if (Carbon::now()->greaterThan($expirationTime)) {
            return response()->json([
                'message' => 'Token sudah kedaluwarsa. Silakan request reset password lagi.',
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        return response()->json([
            'message' => 'Password berhasil direset. Silakan login dengan password baru.',
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Token tidak valid atau sudah kedaluwarsa.',
            ], 400);
        }

        $expirationTime = $user->updated_at->addHour();
        if (Carbon::now()->greaterThan($expirationTime)) {
            return response()->json([
                'message' => 'Token sudah kedaluwarsa. Silakan request verifikasi email lagi.',
            ], 400);
        }

        $user->email_verified_at = now();
        $user->remember_token = null;
        $user->save();

        return response()->json([
            'message' => 'Email berhasil diverifikasi.',
        ]);
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email sudah terverifikasi.',
            ], 400);
        }

        $user->remember_token = Str::random(60);
        $user->updated_at = now();
        $user->save();

        $verificationLink = url('/api/auth/verify-email?email=' . $user->email . '&token=' . $user->remember_token);
        Mail::to($user->email)->send(new EmailVerificationMail($verificationLink));

        return response()->json([
            'message' => 'Link verifikasi telah dikirim ulang ke email Anda.',
        ]);
    }
}

