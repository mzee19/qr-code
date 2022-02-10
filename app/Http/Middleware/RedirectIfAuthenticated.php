<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'admin':
                if (Auth::guard($guard)->check()) {
                    return redirect('admin/dashboard');
                }
                break;
            default:
                if (Auth::guard($guard)->check()) {
                    return redirect('/dashboard');
                }
                break;
        }
        if ($guard == 'web') {
            if ($request->session()->get('url')) {
                $getUrl = isset($request->session()->get('url')['intended']) ? $request->session()->get('url')['intended'] : null;
                if ($getUrl) {
                    $isAdminUrl = str_contains($getUrl,'/admin');
                    if ($isAdminUrl) {
                        $request->session()->pull('url', url('admin/dashboard'));
                    }
                }
            }
        } else {
            if ($request->session()->get('url')) {
                $getUrl = isset($request->session()->get('url')['intended']) ? $request->session()->get('url')['intended'] : null;
                if ($getUrl) {
                    $isAdminUrl = str_contains($getUrl,'/admin');
                    if (!$isAdminUrl) {
                        $request->session()->pull('url', url('dashboard'));
                    }
                }
            }
        }
        return $next($request);
    }
}
