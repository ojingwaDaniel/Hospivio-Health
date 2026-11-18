<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveOrganization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        $baseDomain = strtolower(config('app.base_domain', 'localhost'));

        $pattern = '/^([a-z]+)\.' . preg_quote($baseDomain, '/') . '$/';

        if (!preg_match($pattern, $host, $matches)) {
            abort(404, 'Invalid workspace address.');
        }

        $subdomain = $matches[1];


        $domain = Domain::where("subdomain", $subdomain)->firstOrFail();
        $organization = $domain->organization;
        if ($organization->status != "active") {
            abort(403, 'This organization is not currently active.');
        }

        $request->attributes->set("currentOrganization", $organization);

        return $next($request);
    }
}