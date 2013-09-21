<?php
/** 
* @(#) $Id: init_category_path.php $
* 
* calculate the category path
*
* @package categories 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2010 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

 // calculate category path
    if (isset($_GET['cPath'])) {
      $cPath = $_GET['cPath'];
    } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
      $cPath = ecx_get_product_path($_GET['products_id']);
    } else {
      $cPath = '';
    }
  
    if (ecx_not_null($cPath)) {
      $cPath_array = ecx_parse_category_path($cPath);
      $cPath = implode('_', $cPath_array);
      $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
    } else {
      $current_category_id = 0;
    } 
?>	