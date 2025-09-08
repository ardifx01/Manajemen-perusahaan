<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    /**
     * Show the OTP verification form
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->session()->get('otp_email');
        
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        return view('auth.verify-otp', compact('email'));
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;
        
        // Generate OTP
        $otpRecord = OtpCode::generateOtp($email);
        
        // Send OTP via email
        try {
            Mail::to($email)->send(new OtpMail($otpRecord->otp_code));
            
            // Store email in session for OTP verification
            $request->session()->put('otp_email', $email);
            
            // For testing: if using log driver, show OTP in message
            $mailDriver = config('mail.default');
            if ($mailDriver === 'log' || $mailDriver === 'array') {
                return redirect()->route('otp.verify.form')
                    ->with('status', 'Kode OTP telah dikirim ke email Anda. [MODE TESTING - OTP: ' . $otpRecord->otp_code . ']');
            }
            
            return redirect()->route('otp.verify.form')->with('status', 'Kode OTP telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi. Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Verify OTP and show reset password form
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('otp_email');
        // Normalisasi input: buang semua karakter non-digit untuk menghindari mismatch akibat spasi/formatting
        $normalizedCode = preg_replace('/\D+/', '', (string) $request->otp_code);

        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        if (strlen($normalizedCode) !== 6) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid.']);
        }

        if (OtpCode::verifyOtp($email, $normalizedCode)) {
            // OTP verified, show reset password form
            $request->session()->put('otp_verified', true);
            return redirect()->route('password.reset.form')->with('status', 'OTP berhasil diverifikasi. Silakan masukkan password baru.');
        }

        return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
    }

    /**
     * Show reset password form after OTP verification
     */
    public function showResetForm(Request $request)
    {
        $email = $request->session()->get('otp_email');
        $otpVerified = $request->session()->get('otp_verified');
        
        if (!$email || !$otpVerified) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        return view('auth.reset-password-otp', compact('email'));
    }

    /**
     * Reset password after OTP verification
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->session()->get('otp_email');
        $otpVerified = $request->session()->get('otp_verified');
        
        if (!$email || !$otpVerified) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        // Update user password
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Clear session
            $request->session()->forget(['otp_email', 'otp_verified']);

            return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => 'User tidak ditemukan.']);
    }
}
