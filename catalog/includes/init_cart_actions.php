<?php
/** 
* @(#) $Id: init_cart_actions.php $
* 
* initiate shopping cart actions
*
* @package shopping cart 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2010 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
// Shopping cart actions
  if (isset($_GET['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      ecx_redirect(ecx_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($PHP_SELF);
      if ($_GET['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }
    switch ($_GET['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($_POST['products_id']); $i<$n; $i++) {
                                if (in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) {
                                  $cart->remove($_POST['products_id'][$i]);
                                } else {
                                  $attributes = ($_POST['id'][$_POST['products_id'][$i]]) ? $_POST['id'][$_POST['products_id'][$i]] : '';
                                  $cart->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
                                }
                              }
                              ecx_redirect(ecx_href_link($goto, ecx_get_all_get_params($parameters)));
                              break;
      // customer adds a product from the products page
      case 'add_product' :    if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
                                $attributes = isset($_POST['id']) ? $_POST['id'] : '';
                                $cart->add_cart($_POST['products_id'], $cart->get_quantity(ecx_get_uprid($_POST['products_id'], $attributes))+1, $attributes);
                              }
                              ecx_redirect(ecx_href_link($goto, ecx_get_all_get_params($parameters)));
                              break;
      // customer removes a product from their shopping cart
      case 'remove_product' : if (isset($_GET['products_id'])) {
                                $cart->remove($_GET['products_id']);
                              }
                              ecx_redirect(ecx_href_link($goto, ecx_get_all_get_params($parameters)));
                              break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (isset($_GET['products_id'])) {
                                if (ecx_has_product_attributes($_GET['products_id'])) {
                                  ecx_redirect(ecx_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']));
                                } else {
                                  $cart->add_cart($_GET['products_id'], $cart->get_quantity($_GET['products_id'])+1);
                                }
                              }
                              ecx_redirect(ecx_href_link($goto, ecx_get_all_get_params($parameters)));
                              break;
      case 'notify' :         if (ecx_session_is_registered('customer_id')) {
                                if (isset($_GET['products_id'])) {
                                  $notify = $_GET['products_id'];
                                } elseif (isset($_GET['notify'])) {
                                  $notify = $_GET['notify'];
                                } elseif (isset($_POST['notify'])) {
                                  $notify = $_POST['notify'];
                                } else {
                                  ecx_redirect(ecx_href_link(basename($PHP_SELF), ecx_get_all_get_params(array('action', 'notify'))));
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = ecx_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$customer_id . "'");
                                  $check = ecx_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    ecx_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$customer_id . "', now())");
                                  }
                                }
                                ecx_redirect(ecx_href_link(basename($PHP_SELF), ecx_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();
                                ecx_redirect(ecx_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if (ecx_session_is_registered('customer_id') && isset($_GET['products_id'])) {
                                $check_query = ecx_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                $check = ecx_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  ecx_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                }
                                ecx_redirect(ecx_href_link(basename($PHP_SELF), ecx_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();
                                ecx_redirect(ecx_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if (ecx_session_is_registered('customer_id') && isset($_GET['pid'])) {
                                if (ecx_has_product_attributes($_GET['pid'])) {
                                  ecx_redirect(ecx_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['pid']));
                                } else {
                                  $cart->add_cart($_GET['pid'], $cart->get_quantity($_GET['pid'])+1);
                                }
                              }
                              ecx_redirect(ecx_href_link($goto, ecx_get_all_get_params($parameters)));
                              break;
    }
  }
?>