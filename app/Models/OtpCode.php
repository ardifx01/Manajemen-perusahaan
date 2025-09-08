<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    /**
     * Generate a new OTP code for the given email
     */
    public static function generateOtp($email)
    {
        $now = Carbon::now();

        // Bersihkan OTP yang sudah kadaluarsa (housekeeping ringan)
        self::where('expires_at', '<=', $now)->delete();

        // Reuse OTP yang masih berlaku dan belum digunakan untuk email ini (menghindari mismatch jika email terlambat masuk)
        $existing = self::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', $now)
            ->latest('id')
            ->first();

        if ($existing) {
            return $existing;
        }

        // Generate 6-digit OTP baru jika tidak ada yang aktif
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => $now->copy()->addMinutes(10),
            'is_used' => false
        ]);
    }

    /**
     * Verify OTP code
     */
    public static function verifyOtp($email, $otpCode)
    {
        $otp = self::where('email', $email)
                   ->where('otp_code', $otpCode)
                   ->where('is_used', false)
                   ->where('expires_at', '>', Carbon::now())
                   ->first();

        if ($otp) {
            $otp->update(['is_used' => true]);
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
