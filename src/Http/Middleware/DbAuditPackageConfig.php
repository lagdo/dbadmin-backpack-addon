<?php

namespace Lagdo\Dbadmin\Backpack\Http\Middleware;

use Illuminate\Http\Request;
use Lagdo\DbAdmin\Db\DbAuditPackage;
use Symfony\Component\HttpFoundation\Response;
use Closure;

use function config;
use function route;

class DbAuditPackageConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Copy the queries options into the DbAuditPackage package options.
        $options = config('jaxon.app.packages')[DbAuditPackage::class] ?? [];
        $queriesOptions = [
            'audit' => config('dbadmin.queries.audit'),
            'database' => config('dbadmin.queries.database'),
        ];
        config([
            'jaxon.app.packages' => [
                DbAuditPackage::class => [
                    ...$options,
                    ...$queriesOptions,
                ],
            ],
            'jaxon.lib.core.request.uri' => route(name: 'dbaudit.jaxon', absolute: false),
            'jaxon.app.assets.file' => 'audit-0.2.1',
        ]);

        return $next($request);
    }
}
