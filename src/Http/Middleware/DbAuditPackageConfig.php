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
        // Copy the audit options into the DbAuditPackage package options.
        $options = config('jaxon.app.packages')[DbAuditPackage::class] ?? [];
        $auditOptions = [
            'options' => config('dbadmin.audit.options'),
            'database' => config('dbadmin.audit.database'),
        ];
        config([
            'jaxon.app.packages' => [
                DbAuditPackage::class => [
                    ...$options,
                    ...$auditOptions,
                ],
            ],
            'jaxon.lib.core.request.uri' => route('dbaudit.jaxon'),
            'jaxon.app.assets.file' => 'audit-0.1.0',
        ]);

        return $next($request);
    }
}
