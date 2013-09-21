<?php
/** 
* @(#) $Id: application_top.php $
* 
* Initializes common classes, functions, and parameters used site-wide.
*
* @package initialize system 
*
* @copyright 2013 ecomextra
* @copyright Portions Copyright 2003 osCommerce
* @copyright Portions Copyright 2003-2012 Zen Cart Development Team
* @license   Released under the GNU General Public License. 
*/

/**
 * inoculate against hack attempts which waste CPU cycles
 */
$contaminated = (isset($_FILES['GLOBALS']) || isset($_REQUEST['GLOBALS'])) ? true : false;
$paramsToAvoid = array('GLOBALS', '_COOKIE', '_ENV', '_FILES', '_GET', '_POST', '_REQUEST', '_SERVER', '_SESSION', 'HTTP_COOKIE_VARS', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_RAW_POST_DATA', 'HTTP_SERVER_VARS', 'HTTP_SESSION_VARS');
$paramsToAvoid[] = 'autoLoadConfig';
$paramsToAvoid[] = 'mosConfig_absolute_path';
$paramsToAvoid[] = 'hash';
$paramsToAvoid[] = 'main';
foreach($paramsToAvoid as $key) {
  if (isset($_GET[$key]) || isset($_POST[$key]) || isset($_COOKIE[$key])) {
    $contaminated = true;
    break;
  }
}
$paramsToCheck = array('main_page', 'cPath', 'products_id', 'language', 'currency', 'action', 'manufacturers_id', 'pID', 'pid', 'reviews_id', 'filter_id', 'ecxid', 'sort', 'number_of_uploads', 'notify', 'disp_order', 'id', 'key', 'set_session_login', 'faq_item', 'edit', 'delete', 'search_in_description', 'payment_error', 'order', 'pos', 'addr', 'error', 'count', 'error_message', 'info_message');
if (!$contaminated) {
  foreach($paramsToCheck as $key) {
    if (isset($_GET[$key]) && !is_array($_GET[$key])) {
      if (substr($_GET[$key], 0, 4) == 'http' || strstr($_GET[$key], '//')) {
        $contaminated = true;
        break;
      }
      if (isset($_GET[$key]) && strlen($_GET[$key]) > 43) {
        $contaminated = true;
        break;
      }
    }
  }
}
unset($paramsToCheck, $paramsToAvoid, $key);
if ($contaminated)
{
  header('HTTP/1.1 406 Not Acceptable');
  exit(0);
}
unset($contaminated);
/* *** END OF INNOCULATION *** */

/** determine if script was called by program and not attempting to be called from a browser */
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

/** integer saves the time at which the script started. */
define('PAGE_PARSE_START_TIME', microtime());
@ini_set("arg_separator.output","&");

/**
 * set the level of error reporting
 *
 * Note STRICT_ERROR_REPORTING should never be set to true on a production site. <br />
 * It is mainly there to show php warnings during testing/bug fixing phases.<br />
 */
 define('STRICT_ERROR_REPORTING', true);
if (defined('STRICT_ERROR_REPORTING') && STRICT_ERROR_REPORTING == true) {
  @ini_set('display_errors', TRUE);
  error_reporting(version_compare(PHP_VERSION, 5.3, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE : version_compare(PHP_VERSION, 5.4, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT : E_ALL & ~E_NOTICE);
} else {
  error_reporting(0);
}

/**
 * turn off magic-quotes support, for both runtime and sybase, as both will cause problems if enabled
 */
if (version_compare(PHP_VERSION, 5.3, '<') && function_exists('set_magic_quotes_runtime')) set_magic_quotes_runtime(0);
if (version_compare(PHP_VERSION, 5.4, '<') && @ini_get('magic_quotes_sybase') != 0) @ini_set('magic_quotes_sybase', 0);

/** Set the local configuration parameters - mainly for developers */
if (file_exists('includes/local/configure.php')) {
  /** load any local(user created) configure file. */
  include('includes/local/configure.php');
}else{
  /** load the configure file. */
include('includes/configure.php');
}

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }
/** set the type of request (secure or not) */
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

/** set php_self in the local scope */
  $PHP_SELF = (((strlen(ini_get('cgi.fix_pathinfo')) > 0) && ((bool)ini_get('cgi.fix_pathinfo') == false)) || !isset($_SERVER['SCRIPT_NAME'])) ? basename($_SERVER['PHP_SELF']) : basename($_SERVER['SCRIPT_NAME']);

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

 
/** include the list of filenames */
    require(DIR_WS_INCLUDES . 'filenames.php');
  
/** include the list of project database tables */
    require(DIR_WS_INCLUDES . 'database_tables.php');
  
/** include the database functions */
    require(DIR_WS_FUNCTIONS . 'functions_database.php');
  
/** make a connection to the database... now */
    ecx_db_connect() or die('Unable to connect to database server!');
  
/** set the application parameters */
    $configuration_query = ecx_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
    while ($configuration = ecx_db_fetch_array($configuration_query)) {
      define($configuration['cfgKey'], $configuration['cfgValue']);
    }
/** if gzip_compression is enabled, start to buffer the output */
// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && !headers_sent() ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION < '5.4' || PHP_VERSION > '5.4.5') { // see PHP bug 55544
        if (PHP_VERSION >= '4.0.4') {
          ob_start('ob_gzhandler');
        } elseif (PHP_VERSION >= '4.0.1') {
          include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
          ob_start();
          ob_implicit_flush();
        }
      }
    } elseif (function_exists('ini_set')) {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

/** set the HTTP GET parameters manually if search_engine_friendly_urls is enabled */
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $_GET[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $_GET[$key] = $value;
        }
      }
    }
  }     
 /** load the plugin class, then include the plugin functions */
     include(DIR_WS_CLASSES. 'plugin.class.php');
     include(DIR_WS_FUNCTIONS . 'plugin.php');
 /** load all active plugins */
     $plugins = new ecxplugins();
     $plugins->load_active_plugins();
 
/** define general functions used application-wide */
  require(DIR_WS_FUNCTIONS . 'functions_general.php'); 
  require(DIR_WS_FUNCTIONS . 'functions_html_output.php');

/** set the cookie domain */
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

/** include shopping cart class */
  require(DIR_WS_CLASSES . 'shopping_cart.class.php');

/** include navigation history class */
  require(DIR_WS_CLASSES . 'navigation_history.class.php');
  
/** define how the session functions will be used */
  require(DIR_WS_FUNCTIONS . 'functions_sessions.php');
  include(DIR_WS_INCLUDES . 'init_sessions.php');
/** include currencies class and create an instance */
  require(DIR_WS_CLASSES . 'currencies.class.php');
  $currencies = new currencies();

/** include the mail classes */
  require(DIR_WS_CLASSES . 'mime.class.php');
  require(DIR_WS_CLASSES . 'email.class.php');

/** set the language */
  if (!ecx_session_is_registered('language') || isset($_GET['language'])) {
    if (!ecx_session_is_registered('language')) {
      ecx_session_register('language');
      ecx_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.class.php');
    $lng = new language();

    if (isset($_GET['language']) && ecx_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

/** include the language translations */
  require(DIR_WS_LANGUAGES . $language . '.php');

/** currency */
  if (!ecx_session_is_registered('currency') || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!ecx_session_is_registered('currency')) ecx_session_register('currency');

    if (isset($_GET['currency']) && $currencies->is_set($_GET['currency'])) {
      $currency = $_GET['currency'];
    } else {
      $currency = ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && $currencies->is_set(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }

/** navigation history */
  if (!ecx_session_is_registered('navigation') || !is_object($navigation)) {
    ecx_session_register('navigation');
    $navigation = new navigationHistory;
  }
  $navigation->add_current_page();

/** action recorder */
  include('includes/classes/action_recorder.class.php');

/** infobox */
    require(DIR_WS_CLASSES . 'boxes.class.php');
     
/** initialize the message stack for output messages */
  require(DIR_WS_CLASSES . 'message_stack.class.php');
  $messageStack = new messageStack;
  
/** include the cart actions */
  include('includes/init_cart_actions.php');

/** include the who's online functions */
    require(DIR_WS_FUNCTIONS . 'functions_whos_online.php');
    ecx_update_whos_online();
  
/** include the password crypto functions */
    require(DIR_WS_FUNCTIONS . 'functions_password.php');

/** include validation functions (right now only email address) */
  require(DIR_WS_FUNCTIONS . 'functions_validations.php');

/** split-page-results */
  require(DIR_WS_CLASSES . 'split_page_results.class.php');

/** auto activate and expire banners */
  require(DIR_WS_FUNCTIONS . 'functions_banner.php');
  ecx_activate_banners();
  ecx_expire_banners();

/** auto expire special products */
  require(DIR_WS_FUNCTIONS . 'functions_specials.php');
  ecx_expire_specials();

 /** include and initiate the template classes */
    include(DIR_WS_CLASSES . 'fast_template.class.php');
    $tpl = new FastTemplate();
  $tpl->ROOT = DIR_WS_THEMES . CURRENT_THEME . "/";
   $tpl->setPattern("LNG_");
   $tpl->USE_CACHE=false; 
   require(DIR_WS_CLASSES . 'osc_template.class.php');
    $oscTemplate = new oscTemplate();
include ($tpl->ROOT . 'theme_config.php');    
/** calculate the category path */
include(DIR_WS_INCLUDES . 'init_category_path.php');

/** include the breadcrumb class and start the breadcrumb trail */
  require(DIR_WS_CLASSES . 'breadcrumb.class.php');
  $breadcrumb = new breadcrumb;
  include(DIR_WS_INCLUDES . 'init_breadcrumb.php');

?>