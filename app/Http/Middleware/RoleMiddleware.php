<?php

namespace App\Http\Middleware;

use App\Models\Mahasiswa;
use App\Models\Reviewer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        // Redirect to login if not authenticated
        if (!$user) {
            return redirect('/login');
        }

        // Check role-specific logic
        if ($role === 'mahasiswa') {
            $mhs = Mahasiswa::where('user_id', $user->id)->first();
            if ($mhs) {
                return $next($request);
            }
        } elseif ($role === 'reviewer') {
            $reviewer = Reviewer::where('user_id', $user->id)->first();
            if ($reviewer) {
                return $next($request);
            }
        }

        return redirect('/unauthorized')->withErrors(['message' => 'Access denied for this role.']);
    }

}
