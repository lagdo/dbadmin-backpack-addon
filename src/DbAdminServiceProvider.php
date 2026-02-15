<?php

namespace Lagdo\Dbadmin\Backpack;

use App\Models\User;
use Dotenv\Dotenv;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jaxon\Exception\Exception as JaxonException;
use Lagdo\DbAdmin\Ajax\Exception\AppException;
use Lagdo\Dbadmin\Backpack\Http\Middleware\DbAdminPackageConfig;
use Lagdo\Dbadmin\Backpack\Http\Middleware\DbAuditPackageConfig;
use Lagdo\Dbadmin\Backpack\Http\Middleware\BackpackUserResolver;
use Lagdo\DbAdmin\Db\Config\AuthInterface;
use Lagdo\DbAdmin\Db\Config\ConfigProvider;
use Lagdo\DbAdmin\Db\Driver\Exception\DbException;
use Symfony\Component\HttpFoundation\Response;
use Exception;

use function auth;
use function base_path;
use function config;
use function config_path;
use function env;
use function in_array;
use function is_file;
use function trans;
use function Jaxon\jaxon;

class DbAdminServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider {
        boot as private bootPackage;
        register as private registerPackage;
    }

    protected $vendorName = 'lagdo';
    protected $packageName = 'dbadmin-backpack-addon';
    protected $commands = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Call the register() method from the AutomaticServiceProvider trait.
        $this->registerPackage();

        $this->app->singleton(AuthInterface::class,
            fn() => new class implements AuthInterface {
                public function user(): string
                {
                    return auth()->user()->email ?? '';
                }
                public function role(): string
                {
                    return auth()->user()->role?->name ?? '';
                }
            });
    }

    /**
     * Get the DbAdmin config file path
     *
     * @return string
     */
    private function getDbAdminConfigFile(): string
    {
        // Get the path of first available config file.
        foreach (['json', 'yaml', 'yml', 'php'] as $ext) {
            $path = config_path("dbadmin.$ext");
            if (is_file($path)) {
                return $path;
            }
        }
        return '';
    }

    /**
     * @param string $message
     * @param bool $isError
     *
     * @return Response
     */
    private function showExceptionMessage(string $message, bool $isError): Response
    {
        $jaxon = jaxon();
        $ajaxResponse = $jaxon->ajaxResponse();
        $messageType = $isError ? 'error' : 'warning';
        $messageTitle = $isError ? trans('Error') : trans('Warning');
        $ajaxResponse->dialog()->title($messageTitle)->$messageType($message);

        return $jaxon->httpResponse();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router, Handler $handler): void
    {
        // Load the custom env file
        $dotenv = Dotenv::createImmutable(base_path(), '.env.dbadmin');
        $dotenv->safeLoad();

        // Call the boot() method from the AutomaticServiceProvider trait.
        $this->bootPackage();

        // Publish the default config files.
        $this->publishes([
            __DIR__ . '/../config/jaxon.php' => config_path('jaxon.php'),
            __DIR__ . '/../config/dbadmin.php' => config_path('dbadmin.php'),
        ]);

        // Set the path to the user access config file.
        jaxon()->callback()->boot(fn() => jaxon()->di()
            ->g(ConfigProvider::class)
            ->config($this->getDbAdminConfigFile()));

        // Auth gate for the DbAdmin audit page
        Gate::define('dbaudit', function(User $user) {
            $allowed = config('dbadmin.audit.allowed', []);
            return in_array($user->email, $allowed);
        });

        // Register the middlewares
        $router->middlewareGroup('jaxon.dbadmin.config', [
            BackpackUserResolver::class,
            DbAdminPackageConfig::class,
            'jaxon.config',
        ]);
        $router->middlewareGroup('jaxon.dbaudit.config', [
            BackpackUserResolver::class,
            'can:dbaudit',
            DbAuditPackageConfig::class,
            'jaxon.config',
        ]);

        // When the session expires, redirect any Jaxon request to the login page.
        $handler->respondUsing(function (Response $response) {
            $jaxon = jaxon();
            if ($response->getStatusCode() !== 419 || !$jaxon->canProcessRequest()) {
                return $response;
            }
    
            // Handle token expiration errors on Jaxon requests.
            $ajaxResponse = $jaxon->ajaxResponse();
            $ajaxResponse->redirect(route('backpack.auth.login'));
            return $jaxon->httpResponse();
        });

        // Show the error messages in a dialog
        $handler->renderable(fn (AppException $e) =>
            $this->showExceptionMessage($e->getMessage(), false));
        $handler->renderable(fn (DbException $e) =>
            $this->showExceptionMessage($e->getMessage(), true));
        $handler->renderable(fn (JaxonException $e) =>
            $this->showExceptionMessage($e->getMessage(), true));
        $handler->renderable(function (Exception $e) {
            $errorMessage = 'Unable to process the request. Unexpected error.';
            // Also show the exception message in debug env.
            if (env('APP_DEBUG', false)) {
                $errorMessage .= ' ' . $e->getMessage();
            }
            if (jaxon()->canProcessRequest()) {
                return $this->showExceptionMessage($errorMessage, true);
            }
        });
    }
}
