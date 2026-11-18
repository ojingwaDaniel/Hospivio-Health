<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
           abort(401,"unauthenticated");
        }
        $organization = $request->attributes->get("currentOrganization");
        $belongToOrganization = $user->organizations()->where("organizations.id",$organization->id)->wherePivot("status","active")->exists();
        if (!$belongToOrganization) {
            abort(403, "You do not have access to this organization.");
        }

        return $next($request);
    }
}
