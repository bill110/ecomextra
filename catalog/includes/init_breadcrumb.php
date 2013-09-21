<?php
/** 
* @(#) $Id: init_breadcrumb.php $
* 
* start the breadcrumbpath
*
* @package breadcrumb 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2010 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
  if(USE_TOP_LINK) :
  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
endif;
  $breadcrumb->add(HEADER_TITLE_CATALOG, ecx_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = ecx_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (ecx_db_num_rows($categories_query) > 0) {
        $categories = ecx_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], ecx_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($_GET['manufacturers_id'])) {
    $manufacturers_query = ecx_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
    if (ecx_db_num_rows($manufacturers_query)) {
      $manufacturers = ecx_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], ecx_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']));
    }
  }

// add the products model to the breadcrumb trail
  if (isset($_GET['products_id'])) {
    $model_query = ecx_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'");
    if (ecx_db_num_rows($model_query)) {
      $model = ecx_db_fetch_array($model_query);
      $breadcrumb->add($model['products_model'], ecx_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    }
  }
?>	