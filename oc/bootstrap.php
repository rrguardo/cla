<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;
require APPPATH.'classes/kohana'.EXT;

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- To debug enable DEVELOPMENT environment by changing your localhost
if (!isset($_SERVER['SERVER_NAME']))
    Kohana::$environment = Kohana::STAGING;
elseif ($_SERVER['SERVER_NAME'] !== 'reoc.lo')
    Kohana::$environment = Kohana::PRODUCTION;
else
    Kohana::$environment =  Kohana::DEVELOPMENT;

//Kohana::$environment = ($_SERVER['SERVER_NAME'] !== 'reoc.lo') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;

/**
 * Magic quotes enabled?
 */
if (function_exists('get_magic_quotes_gpc'))
{
    if (get_magic_quotes_gpc())
        Kohana::$magic_quotes = TRUE;
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
    'base_url'  => '/',//later we change it taking it from the config
    'errors'    => TRUE,
    'profile'   => (Kohana::$environment == Kohana::DEVELOPMENT),
    'caching'   => (Kohana::$environment == Kohana::PRODUCTION),
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
//Kohana::$log->attach(new Log_File(APPPATH.'logs'));
if ((Kohana::$environment !== Kohana::DEVELOPMENT) AND (Kohana::$environment !== Kohana::STAGING))
{
    Kohana::$log->attach(new Log_File(APPPATH.'logs'), array(LOG_ERR));
}
else
{
    Kohana::$log->attach(new Log_File(APPPATH.'logs'), array(LOG_INFO,LOG_ERR,LOG_DEBUG));
}

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
$modules = array(
        	   'themes'	      => DOCROOT.'themes',     // we load it as a module so we can later search file using kohana find_file
        	   'auth'         => MODPATH.'auth',       // Basic authentication
        	   'cache'        => MODPATH.'cache',      // Caching with multiple backends
        	   'database'     => MODPATH.'database',   // Database access
        	   'image'        => MODPATH.'image',      // Image manipulation
        	   'orm'          => MODPATH.'orm',        // Object Relationship Mapping
			   'pagination'   => MODPATH.'pagination', // ORM Pagination
			   'breadcrumbs'  => MODPATH.'breadcrumbs',// breadcrumb view
			   //'plugin'       => MODPATH.'plugin',     // hooks used for the plugin system
			   'formmanager'  => MODPATH.'formmanager',// forms to objects ORM
               'widgets'      => MODPATH.'widgets',    // loads default widgets
               'blacksmith'   => MODPATH.'blacksmith',    // used for custom fields
               'mysqli'       => MODPATH.'mysqli',    // mysqli driver
);

//modules for development environment, not included in distribution KO with OC, so you need to place them in your environment
//also we did a cleaning in KO removing all the tests and documentation to make it lighter
// if (Kohana::$environment == Kohana::DEVELOPMENT)
// {
//     $modules['unittest'] =  MODPATH.'unittest';   // Unit testing
//     //$modules['userguide'] = MODPATH.'userguide';  // User guide and API documentation
// }

Kohana::modules($modules);
unset($modules);

// initializing the OC APP, and routes
Core::initialize();