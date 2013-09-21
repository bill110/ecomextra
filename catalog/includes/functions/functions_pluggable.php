<?php
/** 
* @(#) $Id: functions_pluggable.php $
* 
* These functions can be replaced via plugins. If plugins do not redefine these
* functions, then these will be used instead.
* @package functions 
*
* @copyright 2013 ecomextra
* @license   Released under the GNU General Public License. 
*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
/** use this action to replace any of the following functions */
do_action('pluggable');

 if(!function_exists('ecx_get_version')) :
/** Get the installed version number */
  function ecx_get_version() {
    static $v;

    if (!isset($v)) {
      $v = trim(implode('', file(DIR_FS_CATALOG . 'includes/version.php')));
    }

    return $v;
  }
endif;

 if(!function_exists('ecx_sanitize_string')):
  function ecx_sanitize_string($string) {
    $patterns = array ('/ +/','/[<>]/');
    $replace = array (' ', '_');
    return preg_replace($patterns, $replace, trim($string));
  }
endif;

 if(!function_exists('ecx_get_all_get_params')):
/** Return all $_GET variables, except those passed as a parameter */
  function ecx_get_all_get_params($exclude_array = '') {
    global $_GET;

    if (!is_array($exclude_array)) $exclude_array = array();

    $get_url = '';
    if (is_array($_GET) && (sizeof($_GET) > 0)) {
      reset($_GET);
      while (list($key, $value) = each($_GET)) {
        if ( is_string($value) && (strlen($value) > 0) && ($key != ecx_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
        }
      }
    }

    return $get_url;
  }
endif;

  if(!function_exists('ecx_create_sort_heading')):
/** Return table heading with sorting capabilities */
  function ecx_create_sort_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . ecx_href_link(basename($PHP_SELF), ecx_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . ecx_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }
endif;

  if(!function_exists('ecx_customer_greeting')):
/** Return a customer greeting */
  function ecx_customer_greeting() {
    global $customer_id, $customer_first_name;

    if (ecx_session_is_registered('customer_first_name') && ecx_session_is_registered('customer_id')) {
      $greeting_string = sprintf(TEXT_GREETING_PERSONAL, ecx_output_string_protected($customer_first_name), ecx_href_link(FILENAME_PRODUCTS_NEW));
    } else {
      $greeting_string = sprintf(TEXT_GREETING_GUEST, ecx_href_link(FILENAME_LOGIN, '', 'SSL'), ecx_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    return $greeting_string;
  }
endif;

  if(!function_exists('ecx_get_uprid')):
// Return a product ID with attributes
  function ecx_get_uprid($prid, $params) {
    if (is_numeric($prid)) {
      $uprid = $prid;

      if (is_array($params) && (sizeof($params) > 0)) {
        $attributes_check = true;
        $attributes_ids = '';

        reset($params);
        while (list($option, $value) = each($params)) {
          if (is_numeric($option) && is_numeric($value)) {
            $attributes_ids .= '{' . (int)$option . '}' . (int)$value;
          } else {
            $attributes_check = false;
            break;
          }
        }

        if ($attributes_check == true) {
          $uprid .= $attributes_ids;
        }
      }
    } else {
      $uprid = ecx_get_prid($prid);

      if (is_numeric($uprid)) {
        if (strpos($prid, '{') !== false) {
          $attributes_check = true;
          $attributes_ids = '';

// strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
          $attributes = explode('{', substr($prid, strpos($prid, '{')+1));

          for ($i=0, $n=sizeof($attributes); $i<$n; $i++) {
            $pair = explode('}', $attributes[$i]);

            if (is_numeric($pair[0]) && is_numeric($pair[1])) {
              $attributes_ids .= '{' . (int)$pair[0] . '}' . (int)$pair[1];
            } else {
              $attributes_check = false;
              break;
            }
          }

          if ($attributes_check == true) {
            $uprid .= $attributes_ids;
          }
        }
      } else {
        return false;
      }
    }

    return $uprid;
  }
endif;

  if(!function_exists('ecx_get_prid')):
// Return a product ID from a product ID with attributes
  function ecx_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    if (is_numeric($pieces[0])) {
      return $pieces[0];
    } else {
      return false;
    }
  }
endif;
?>