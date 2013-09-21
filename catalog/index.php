<?php
/** 
* @(#) $Id: index.php $
* 
* @package index 
*
* @copyright 2013 ecomextra
* @copyright Portions Copyright 2003 osCommerce
* @license   Released under the GNU General Public License. 
*/

  DEFINE('IN_ECX', TRUE);
  
  require('includes/application_top.php');
/** the following cPath references come from application_top.php */
  include('includes/head.php');
  $category_depth = 'top';
  if (isset($cPath) && ecx_not_null($cPath)) {
    $categories_products_query = ecx_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $categories_products = ecx_db_fetch_array($categories_products_query);
    if ($categories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = ecx_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = ecx_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

  $tpl->define(array(
  		'head'=>'head.html',
  		'body'=>'index.html',
		'test'=>'test.html'));
  $tpl->assign(array(
  	'TITLE'=> STORE_NAME));
  $tpl->parse('MAIN',array('head','body','test',));
 // $tpl->parse('HEAD','head');
//  $tpl->FastPrintArray(array('HEAD','MAIN'));
$tpl->FastPrint('MAIN');

  require('includes/application_bottom.php');  
?>