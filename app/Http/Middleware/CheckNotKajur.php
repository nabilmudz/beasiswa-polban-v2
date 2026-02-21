<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Reviewer;
use Illuminate\Support\Facades\Auth;

class CheckNotKajur
{
    /**
     * Handle an incoming request.
     * Middleware ini memblokir ketua jurusan (role_id = 2) dari mengakses route tertentu
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $reviewer = Reviewer::where('user_id', Auth::id())->first();
            
            // Jika user adalah ketua jurusan (role_id = 2), redirect ke list beasiswa mahasiswa
            if ($reviewer && $reviewer->role_id == 2) {
                return redirect('/beasiswa')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
        }
        
        return $next($request);
    }
}
