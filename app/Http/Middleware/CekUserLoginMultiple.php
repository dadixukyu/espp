<?php

namespace App\Http\Middleware;

use Closure;

class CekUserLoginMultiple
{
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = auth()->user()->level;
        $allowedRoles = explode(',', implode(',', $roles)); // menggabungkan array $roles menjadi string dengan delimiter koma, kemudian memecahnya kembali menjadi array
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
