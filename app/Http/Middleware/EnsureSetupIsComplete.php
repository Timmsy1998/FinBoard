<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class EnsureSetupIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $isSetupDone = Setting::get('setup_complete') === 'true';
        $path = $request->path();

        $isSetupRoute = str_starts_with($path, 'setup');

        if (!$isSetupDone && !$isSetupRoute) {
            return redirect()->route('setup');
        }

        if ($isSetupDone && $isSetupRoute) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
