<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Use Auth:: instead of auth()->
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Use Redirect:: instead of redirect()
        return Redirect::to('dashboard')->with('error', 'You do not have permission.');
    }
}
?>
