<?php
/**
 * Twitter Module Bootstrap
 */

//******************************************************************************
//* Bootstrap logic
//******************************************************************************

if (!function_exists('__twitter_bootstrap')) {
    /** @return \Determine\Module\Twitter\Module */
    function __twitter_bootstrap()
    {
        //  Load the environment configuration
        try {
            $_de = new \Dotenv\Dotenv(__DIR__ . '/../');
            $_de->load();
        } catch (\Dotenv\Exception\InvalidPathException $_ex) {
        }

        //  Save for later
        $_er = error_reporting();
        $_de = ini_get('display_errors');

        $_app = new \Determine\Module\Twitter\Module(realpath(__DIR__ . '/../'));
        $_app->withEloquent()->withFacades();

        $_app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, Determine\Module\Twitter\Exceptions\Handler::class);
        $_app->singleton(Illuminate\Contracts\Console\Kernel::class, Determine\Module\Twitter\Console\Kernel::class);

        //  Register the service and facade
        $_app->register(ChaoticWave\Twister\Providers\TwisterServiceProvider::class);

        //  Only need this for composer update
        if ('cli' === PHP_SAPI) {
            $_app->register(Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        //@todo Change log file to be in the files/log directory
        $_app->configureMonologUsing(function($monolog) {
            /** @var \Monolog\Logger $monolog */
            return $monolog->pushHandler(
                new \Monolog\Handler\StreamHandler(
                    storage_path('/logs/' . \Determine\Module\Twitter\Module::NAME . '.log'),
                    config(\Determine\Module\Twitter\Module::NAME . '.log-level', \Monolog\Logger::DEBUG)
                )
            );
        });

        //  Force error_reporting back to "eased"
        error_reporting($_er);
        ini_set('display_errors', $_de);

        return $_app;
    }
}

//******************************************************************************
//* Composer autoloader compensation
//******************************************************************************

//  Bootstrap the autoloader if we are stand-alone
if (!isset($GLOBALS['__composer_autoload_files'])) {
    if (!function_exists('__twitter_bootstrap_autoload')) {
        /**
         * Bootstrap composer autoloader
         *
         * @return bool|mixed
         * @todo This is for TESTING only. Inclusion of /vendor/autoload.php needs to be located in the front-controller or index.php, and well-known.
         */
        function __twitter_bootstrap_autoload()
        {
            static $_location = null;

            if ($_location) {
                /** @noinspection PhpIncludeInspection */
                return require_once $_location;
            }

            if (isset($GLOBALS['__determine_vendor_dir'])) {
                /** @noinspection PhpIncludeInspection
                 * Known location
                 */
                return require_once($_location = $GLOBALS['__determine_vendor_dir'] . '/autoload.php');
            }

            //  The other possibilities
            $_locations = [__DIR__ . '/../vendor/autoload.php',];

            if (isset($_ENV['BPACK_CORE'])) {
                $_locations[] = $_ENV['BPACK_CORE'] . '/vendor/autoload.php';
            }

            if (isset($_SERVER['BPACK_CORE'])) {
                $_locations[] = $_SERVER['BPACK_CORE'] . '/vendor/autoload.php';
            }

            if (false !== ($_check = __locate_core())) {
                $_locations[] = $_check;
            }

            //  Look in order
            foreach ($_locations as $_check) {
                if (file_exists($_check)) {
                    /** @noinspection PhpIncludeInspection */
                    return require_once($_location = $_check);
                }
            }

            return false;
        }
    }

    if (!function_exists('__locate_core')) {
        /**
         * Try and find the core
         *
         * @return string
         * @todo This is for TESTING only. Inclusion of /vendor/autoload.php needs to be located in the front-controller or index.php, and well-known.
         */
        function __locate_core()
        {
            $_checks = ['trunk', 'd', 's', 't', 'l',];
            $_path = __DIR__ . '/../../../';

            while (true) {
                foreach ($_checks as $_check) {
                    if (file_exists($_path . $_check . '/vendor/autoload.php')) {
                        return $_path . $_check . '/vendor/autoload.php';
                    }
                }

                if ('/' === ($_path = dirname($_path)) || false === $_path || !is_dir($_path)) {
                    break;
                }
            }

            return false;
        }
    }

    __twitter_bootstrap_autoload();
}

//******************************************************************************
//* Bootstrap module
//******************************************************************************

/** Don't load unless we have a session */
if ('cli' === PHP_SAPI || PHP_SESSION_ACTIVE === session_status()) {
    return __twitter_bootstrap();
}

return true;
