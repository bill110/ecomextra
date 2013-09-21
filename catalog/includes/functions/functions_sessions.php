<?php
/** 
* @(#) $Id: functions_sessions.php $
* 
* Functions for handeling sessions
*
* @package Sessions 
*
* @copyright (ported version)2013 ecomextra
* @copyright (Most) Portions Copyright 2012 osCommerce
* @changes changes made to accomodate depreciated PHP session functions
* @license   Released under the GNU General Public License. 
*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
  if ( (PHP_VERSION >= 4.3) && ((bool)ini_get('register_globals') == false) ) {
    @ini_set('session.bug_compat_42', 1);
    @ini_set('session.bug_compat_warn', 0);
  }

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $value_query = ecx_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . ecx_db_input($key) . "' and expiry > '" . time() . "'");
      $value = ecx_db_fetch_array($value_query);

      if (isset($value['value'])) {
        return $value['value'];
      }

      return '';
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $check_query = ecx_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . ecx_db_input($key) . "'");
      $check = ecx_db_fetch_array($check_query);

      if ($check['total'] > 0) {
        return ecx_db_query("update " . TABLE_SESSIONS . " set expiry = '" . ecx_db_input($expiry) . "', value = '" . ecx_db_input($value) . "' where sesskey = '" . ecx_db_input($key) . "'");
      } else {
        return ecx_db_query("insert into " . TABLE_SESSIONS . " values ('" . ecx_db_input($key) . "', '" . ecx_db_input($expiry) . "', '" . ecx_db_input($value) . "')");
      }
    }

    function _sess_destroy($key) {
      return ecx_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . ecx_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime) {
      ecx_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function ecx_session_start() {
    global $_GET, $_POST, $_COOKIE;

    $sane_session_id = true;

    if (isset($_GET[ecx_session_name()])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_GET[ecx_session_name()]) == false) {
        unset($_GET[ecx_session_name()]);

        $sane_session_id = false;
      }
    } elseif (isset($_POST[ecx_session_name()])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_POST[ecx_session_name()]) == false) {
        unset($_POST[ecx_session_name()]);

        $sane_session_id = false;
      }
    } elseif (isset($_COOKIE[ecx_session_name()])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $_COOKIE[ecx_session_name()]) == false) {
        $session_data = session_get_cookie_params();

        setcookie(ecx_session_name(), '', time()-42000, $session_data['path'], $session_data['domain']);

        $sane_session_id = false;
      }
    }

    if ($sane_session_id == false) {
      ecx_redirect(ecx_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }


    return session_start();
  }

  function ecx_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
 
        if (!isset($GLOBALS[$variable])) {
          $GLOBALS[$variable] = null;
        }

        $_SESSION[$variable] =& $GLOBALS[$variable];
      
    }

    return false;
  }

  function ecx_session_is_registered($variable) {
      return isset($_SESSION) && array_key_exists($variable, $_SESSION);
  }

  function ecx_session_unregister($variable) {
      unset($_SESSION[$variable]);
  }

  function ecx_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function ecx_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function ecx_session_close() {
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }

  function ecx_session_destroy() {
    return session_destroy();
  }

  function ecx_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function ecx_session_recreate() {
    global $SID;

    if (PHP_VERSION >= 5.1) {
      session_regenerate_id(true);

      if (!empty($SID)) {
        $SID = ecx_session_name() . '=' . ecx_session_id();
      }
    }
  }
?>