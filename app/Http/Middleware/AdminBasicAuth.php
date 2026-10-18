<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = env('ADMIN_USER', 'admin');
        $pass = env('ADMIN_PASS');

        if (!$pass || $request->getUser() !== $user || $request->getPassword() !== $pass) {
            return response('Akses ditolak.', 401, ['WWW-Authenticate' => 'Basic realm="MuSantara Admin"']);
        }

        return $next($request);
    }
}
