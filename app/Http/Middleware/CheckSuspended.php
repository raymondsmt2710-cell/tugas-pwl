<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user() ?? auth('web')->user() ?? auth('sanctum')->user();

        if ($user && $user->account_status === 'suspended') {
            // If it's an API/JSON request, return 403 Forbidden
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akun Anda telah ditangguhkan sementara karena melanggar ketentuan dan kebijakan layanan kami.',
                ], 403);
            }

            // Exclude safe paths/routes from redirect to avoid loops and allow logging out
            if (!$request->is('suspended') && 
                !$request->is('logout') && 
                !$request->is('admin/logout') &&
                !$request->routeIs('logout') &&
                !$request->routeIs('filament.admin.auth.logout')) {
                
                return redirect()->route('suspended');
            }
        }

        return $next($request);
    }
}
