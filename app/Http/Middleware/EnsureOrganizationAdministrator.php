<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $organization = $request->attributes->get("currentOrganization");
        $isAdministrator = $user->organizations()->where("organizations.id",$organization->id)->wherePivot("role","administrator")->wherePivot("status","active")->exists();
        if (!$isAdministrator) {
            abort(403, "Only hospital administrators can perform this action.");
        }

        return $next($request);
    }
}
