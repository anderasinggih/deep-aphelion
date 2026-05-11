<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        $maintenance = Setting::get('maintenance_mode', '0');

        if ($maintenance === '1') {
            // Cek apakah ini route login atau logout
            if ($request->is('login') || $request->is('logout') || $request->is('livewire/*')) {
                return $next($request);
            }

            // Cek apakah user adalah admin atau superadmin
            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
                return $next($request);
            }

            // Jika route sudah maintenance, jangan loop
            if ($request->is('maintenance')) {
                return $next($request);
            }

            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}
