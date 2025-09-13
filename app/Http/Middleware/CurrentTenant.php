<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CurrentTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('web')->check()) {
            $user = auth('web')->user();
            /** @var \App\Models\Team|null $tenant */
            $tenant = Filament::getTenant();
            if ($tenant?->id) {
                if ($user->current_team_id !== $tenant->id) {
                    $user->update(['current_team_id' => $tenant->id]);
                }
            }
        }

        return $next($request);
    }
}
