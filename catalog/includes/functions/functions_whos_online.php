<?php
/** 
* @(#) $Id: functions_whos_online.php $
* 
* @package functions 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2003 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

  function ecx_update_whos_online() {
    global $customer_id;

    if (ecx_session_is_registered('customer_id')) {
      $wo_customer_id = $customer_id;

      $customer_query = ecx_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $customer = ecx_db_fetch_array($customer_query);

      $wo_full_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
    } else {
      $wo_customer_id = '';
      $wo_full_name = 'Guest';
    }

    $wo_session_id = ecx_session_id();
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = getenv('REQUEST_URI');

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

// remove entries that have expired
    ecx_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

    $stored_customer_query = ecx_db_query("select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . ecx_db_input($wo_session_id) . "'");
    $stored_customer = ecx_db_fetch_array($stored_customer_query);

    if ($stored_customer['count'] > 0) {
      ecx_db_query("update " . TABLE_WHOS_ONLINE . " set customer_id = '" . (int)$wo_customer_id . "', full_name = '" . ecx_db_input($wo_full_name) . "', ip_address = '" . ecx_db_input($wo_ip_address) . "', time_last_click = '" . ecx_db_input($current_time) . "', last_page_url = '" . ecx_db_input($wo_last_page_url) . "' where session_id = '" . ecx_db_input($wo_session_id) . "'");
    } else {
      ecx_db_query("insert into " . TABLE_WHOS_ONLINE . " (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('" . (int)$wo_customer_id . "', '" . ecx_db_input($wo_full_name) . "', '" . ecx_db_input($wo_session_id) . "', '" . ecx_db_input($wo_ip_address) . "', '" . ecx_db_input($current_time) . "', '" . ecx_db_input($current_time) . "', '" . ecx_db_input($wo_last_page_url) . "')");
    }
  }
?>