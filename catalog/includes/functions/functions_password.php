<?php
/** 
* @(#) $Id: functions_password.php $
* 
* functions for encrypting and validatind passwords
*
* @package passwords 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2010 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
////
// This function validates a plain text password with a
// salted or phpass password
  function ecx_validate_password($plain, $encrypted) {
    if (ecx_not_null($plain) && ecx_not_null($encrypted)) {
      if (ecx_password_type($encrypted) == 'salt') {
        return ecx_validate_old_password($plain, $encrypted);
      }

      if (!class_exists('PasswordHash')) {
        include(DIR_WS_CLASSES . 'passwordhash.class.php');
      }

      $hasher = new PasswordHash(10, true);

      return $hasher->CheckPassword($plain, $encrypted);
    }

    return false;
  }

////
// This function validates a plain text password with a
// salted password
  function ecx_validate_old_password($plain, $encrypted) {
    if (ecx_not_null($plain) && ecx_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
  }

////
// This function encrypts a phpass password from a plaintext
// password.
  function ecx_encrypt_password($plain) {
    if (!class_exists('PasswordHash')) {
      include(DIR_WS_CLASSES . 'passwordhash.php');
    }

    $hasher = new PasswordHash(10, true);

    return $hasher->HashPassword($plain);
  }

////
// This function encrypts a salted password from a plaintext
// password.
  function ecx_encrypt_old_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= ecx_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }

////
// This function returns the type of the encrpyted password
// (phpass or salt)
  function ecx_password_type($encrypted) {
    if (preg_match('/^[A-Z0-9]{32}\:[A-Z0-9]{2}$/i', $encrypted) === 1) {
      return 'salt';
    }

    return 'phpass';
  }
?>