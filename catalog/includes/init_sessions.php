<?php
/** 
* @(#) $Id: init_sessions.php $
* 
* session functions.
*
* @package functions 
*
* @copyright 2013 ecomextra
* @copyright Portions Copyright 2007 osCommerce
* @copyright Portions Copyright 2003-2012 Zen Cart Development Team
* @license   Released under the GNU General Public License. 
*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}


// set the session name and save path
  ecx_session_name('ecxsid');
  ecx_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters

   if (function_exists('session_set_cookie_params')) {
   $secureFlag = ((ENABLE_SSL == 'true' && substr(HTTP_SERVER, 0, 6) == 'https:' && substr(HTTPS_SERVER, 0, 6) == 'https:') || (ENABLE_SSL == 'false' && substr(HTTP_SERVER, 0, 6) == 'https:')) ? TRUE : FALSE;
   if (PHP_VERSION >= '5.2.0') {
    session_set_cookie_params(0, $cookie_path, $cookie_domain, $secureFlag, TRUE);
    } else {
    session_set_cookie_params(0, $cookie_path, $cookie_domain, $secureFlag);
    }
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

  @ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);

// set the session ID if it exists
   if (isset($_POST[ecx_session_name()])) {
     ecx_session_id($_POST[ecx_session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($_GET[ecx_session_name()]) ) {
     ecx_session_id($_GET[ecx_session_name()]);
   }

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    ecx_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($_COOKIE['cookie_test'])) {
      ecx_session_start();
      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (ecx_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (ecx_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      ecx_session_start();
      $session_started = true;
    }
  } else {
    ecx_session_start();
    $session_started = true;
  }

  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }



// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
  if (!$_SESSION['SSL_SESSION_ID']) {
    $_SESSION['SSL_SESSION_ID'] = $ssl_session_id;
  }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      ecx_session_destroy();
      ecx_redirect(ecx_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
  if (!$_SESSION['SESSION_USER_AGENT']) {
    $_SESSION['SESSION_USER_AGENT'] = $http_user_agent;
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      ecx_session_destroy();
      ecx_redirect(ecx_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = ecx_get_ip_address();
    if (!$_SESSION['SESSION_IP_ADDRESS']) {
    $_SESSION['SESSION_IP_ADDRESS']= $ip_address;

    }

    if ($_SESSION['SESSION_IP_ADDRESS'] != $ip_address) {
      ecx_session_destroy();
      ecx_redirect(ecx_href_link(FILENAME_LOGIN));
    }
  }

// create the shopping cart
  if (!$_SESSION['cart'] || !is_object($cart)) {
    $_SESSION['cart'] = 'cart';
    $cart = new shoppingCart;
  }
?>	