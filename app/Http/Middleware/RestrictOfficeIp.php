<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictOfficeIp
{
    /**
     * Handle an incoming request.
     * Restricts access strictly to authorized office networks (Rota Pueblo & Base Naval NEX).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If IP restriction is disabled, allow access
        if (!config('kfm.whitelist_enabled', false)) {
            return $next($request);
        }

        // 2. Critical health checks and diagnostic IP-check endpoints always bypass
        if ($request->is('up') || $request->is('api/health') || $request->is('ip-check')) {
            return $next($request);
        }

        // 3. Admin emergency bypass key (?bypass=SECRET_KEY)
        $bypassKey = config('kfm.bypass_key');
        if ($bypassKey && $request->query('bypass') === $bypassKey) {
            session(['kfm_bypass_authorized' => true]);
        }

        if (session('kfm_bypass_authorized') === true) {
            return $next($request);
        }

        // 4. Resolve client IP
        $clientIp = $request->ip();

        // Always allow local development
        if (in_array($clientIp, ['127.0.0.1', '::1'])) {
            return $next($request);
        }

        // 5. Check against allowed office IPs
        $allowed = config('kfm.allowed_ips', []);

        foreach ($allowed as $pattern) {
            $pattern = trim($pattern);
            if (empty($pattern)) {
                continue;
            }

            if ($this->ipMatches($clientIp, $pattern)) {
                return $next($request);
            }
        }

        // 6. Access Denied: Render branded 403 response
        return response()->view('errors.403', [
            'clientIp' => $clientIp,
        ], 403);
    }

    /**
     * Check if an IP matches an exact IP or a CIDR subnet.
     */
    protected function ipMatches(string $ip, string $pattern): bool
    {
        if ($ip === $pattern) {
            return true;
        }

        // CIDR subnet evaluation (e.g. 192.168.1.0/24)
        if (str_contains($pattern, '/')) {
            [$subnet, $bits] = explode('/', $pattern, 2);
            $bits = (int) $bits;
            if ($bits < 0 || $bits > 32) {
                return false;
            }
            $ipLong = ip2long($ip);
            $subnetLong = ip2long($subnet);
            if ($ipLong === false || $subnetLong === false) {
                return false;
            }
            $mask = ~((1 << (32 - $bits)) - 1);
            return ($ipLong & $mask) === ($subnetLong & $mask);
        }

        return false;
    }
}
