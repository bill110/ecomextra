<?php
/** 
* @(#) $Id: functions_specials.php $
* 
* functions for handling specials
*
* @package specials 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2012 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

////
// Sets the status of a special product
  function ecx_set_specials_status($specials_id, $status) {
    return ecx_db_query("update " . TABLE_SPECIALS . " set status = '" . (int)$status . "', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
  }

////
// Auto expire products on special
  function ecx_expire_specials() {
    $specials_query = ecx_db_query("select specials_id from " . TABLE_SPECIALS . " where status = '1' and now() >= expires_date and expires_date > 0");
    if (ecx_db_num_rows($specials_query)) {
      while ($specials = ecx_db_fetch_array($specials_query)) {
        ecx_set_specials_status($specials['specials_id'], '0');
      }
    }
  }
?>