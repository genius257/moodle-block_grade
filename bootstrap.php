<?php


/** Used by library scripts to check they are being called by Moodle. */
define('MOODLE_INTERNAL', true);

define('CLI_SCRIPT', true);

global $CFG;
$CFG ??= new stdClass();

$CFG->dataroot = __DIR__.'/.moodledata';
$CFG->wwwroot = 'https://example.com/';

$CFG->dbtype    = 'pgsql';      // 'pgsql', 'mariadb', 'mysqli', 'auroramysql', 'sqlsrv' or 'oci'
$CFG->dblibrary = 'native';     // 'native' only at the moment
$CFG->dbhost    = 'localhost';  // eg 'localhost' or 'db.isp.com' or IP
$CFG->dbname    = 'moodle';     // database name, eg moodle
$CFG->dbuser    = 'username';   // your database username
$CFG->dbpass    = 'password';   // your database password
$CFG->prefix    = 'mdl_';       // prefix to use for all table names

$CFG->libdir    = __DIR__.'/vendor/moodle/moodle/lib';

$CFG->dirroot = dirname($CFG->libdir);

require_once($CFG->libdir . '/setuplib.php');        // Functions that MUST be loaded first

// Make sure there is some database table prefix.
if (!isset($CFG->prefix)) {
    $CFG->prefix = '';
}

// Define admin directory
if (!isset($CFG->admin)) {   // Just in case it isn't defined in config.php
    $CFG->admin = 'admin';   // This is relative to the wwwroot and dirroot
}

// Set up some paths.
$CFG->libdir = $CFG->dirroot .'/lib';

// Allow overriding of tempdir but be backwards compatible
if (!isset($CFG->tempdir)) {
    $CFG->tempdir = $CFG->dataroot . DIRECTORY_SEPARATOR . "temp";
}

// Allow overriding of backuptempdir but be backwards compatible
if (!isset($CFG->backuptempdir)) {
    $CFG->backuptempdir = "$CFG->tempdir/backup";
}

// Allow overriding of cachedir but be backwards compatible
if (!isset($CFG->cachedir)) {
    $CFG->cachedir = "$CFG->dataroot/cache";
}

// Allow overriding of localcachedir.
if (!isset($CFG->localcachedir)) {
    $CFG->localcachedir = "$CFG->dataroot/localcache";
}

// Allow overriding of localrequestdir.
if (!isset($CFG->localrequestdir)) {
    $CFG->localrequestdir = sys_get_temp_dir() . '/requestdir';
}

// Location of all languages except core English pack.
if (!isset($CFG->langotherroot)) {
    $CFG->langotherroot = $CFG->dataroot.'/lang';
}

// Location of local lang pack customisations (dirs with _local suffix).
if (!isset($CFG->langlocalroot)) {
    $CFG->langlocalroot = $CFG->dataroot.'/lang';
}

define('CACHE_DISABLE_ALL', true);
// When set to true MUC (Moodle caching) will be disabled as much as possible.
// A special cache factory will be used to handle this situation and will use special "disabled" equivalents objects.
// This ensure we don't attempt to read or create the config file, don't use stores, don't provide persistence or
// storage of any kind.
if (!defined('CACHE_DISABLE_ALL')) {
    define('CACHE_DISABLE_ALL', false);
}

define('CACHE_DISABLE_STORES', true);
// When set to true MUC (Moodle caching) will not use any of the defined or default stores.
// The Cache API will continue to function however this will force the use of the cachestore_dummy so all requests
// will be interacting with a static property and will never go to the proper cache stores.
// Useful if you need to avoid the stores for one reason or another.
if (!defined('CACHE_DISABLE_STORES')) {
    define('CACHE_DISABLE_STORES', false);
}

// Servers should define a default timezone in php.ini, but if they don't then make sure no errors are shown.
date_default_timezone_set(@date_default_timezone_get());

// core_component can be used in any scripts, it does not need anything else.
require_once($CFG->libdir .'/classes/component.php');

// Register our classloader, in theory somebody might want to replace it to load other hacked core classes.
if (defined('COMPONENT_CLASSLOADER')) {
    spl_autoload_register(COMPONENT_CLASSLOADER);
} else {
    spl_autoload_register('core_component::classloader');
}

// Remember the default PHP timezone, we will need it later.
core_date::store_default_php_timezone();

// Load up standard libraries
require_once($CFG->libdir .'/filterlib.php');       // Functions for filtering test as it is output
require_once($CFG->libdir .'/ajax/ajaxlib.php');    // Functions for managing our use of JavaScript and YUI
require_once($CFG->libdir .'/weblib.php');          // Functions relating to HTTP and content
require_once($CFG->libdir .'/outputlib.php');       // Functions for generating output
require_once($CFG->libdir .'/navigationlib.php');   // Class for generating Navigation structure
require_once($CFG->libdir .'/dmllib.php');          // Database access
require_once($CFG->libdir .'/datalib.php');         // Legacy lib with a big-mix of functions.
require_once($CFG->libdir .'/accesslib.php');       // Access control functions
require_once($CFG->libdir .'/deprecatedlib.php');   // Deprecated functions included for backward compatibility
require_once($CFG->libdir .'/moodlelib.php');       // Other general-purpose functions
require_once($CFG->libdir .'/enrollib.php');        // Enrolment related functions
require_once($CFG->libdir .'/pagelib.php');         // Library that defines the moodle_page class, used for $PAGE
require_once($CFG->libdir .'/blocklib.php');        // Library for controlling blocks
require_once($CFG->libdir .'/grouplib.php');        // Groups functions
require_once($CFG->libdir .'/sessionlib.php');      // All session and cookie related stuff
require_once($CFG->libdir .'/editorlib.php');       // All text editor related functions and classes
require_once($CFG->libdir .'/messagelib.php');      // Messagelib functions
require_once($CFG->libdir .'/modinfolib.php');      // Cached information on course-module instances
require_once($CFG->dirroot.'/cache/lib.php');       // Cache API
