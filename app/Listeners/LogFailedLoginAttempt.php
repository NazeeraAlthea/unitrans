<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class LogFailedLoginAttempt
{
    protected $maxAttempts = 5;       // batas gagal login sebelum blokir/notif
    protected $decayMinutes = 15;     // waktu reset hitungan gagal login (menit)

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $email = $event->credentials['email'] ?? 'unknown';
        $ip = request()->ip();

        Log::warning("Failed login attempt for email: {$email} from IP: {$ip}");

        // Hitung jumlah gagal login berdasarkan email + IP
        $key = "login_failed:{$email}:{$ip}";
        $attempts = Cache::get($key, 0);
        $attempts++;
        Cache::put($key, $attempts, now()->addMinutes($this->decayMinutes));

        // Jika sudah melewati batas maksimal gagal login
        if ($attempts >= $this->maxAttempts) {
            // Kirim notifikasi email ke admin (atau lakukan tindakan lain)
            $this->notifyAdmin($email, $ip, $attempts);

            // Bisa tambahkan log blokir disini, atau blokir user/IP sementara di middleware (custom)
            Log::alert("User {$email} dari IP {$ip} mencapai batas gagal login. Mungkin serangan brute force.");
        }
    }

    protected function notifyAdmin($email, $ip, $attempts)
    {
        // Contoh kirim email sederhana
        Mail::raw("User {$email} dari IP {$ip} telah gagal login sebanyak {$attempts} kali.", function ($message) {
            $message->to('admin@domain.com')
                    ->subject('Peringatan: Aktivitas login gagal berulang');
        });
    }
}
