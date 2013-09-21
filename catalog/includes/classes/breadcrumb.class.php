<?php
/** 
* @(#) $Id: breadcrumb.class.php $
* 
* Class to handle breadcrumb trail. 
*
* @package breadcrumb 
*
* @copyright (ported version)2013 ecomextra
* @copyright (Most) Portions Copyright 2003 osCommerce
* @license   Released under the GNU General Public License. 
*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

  class breadcrumb {
    var $_trail;

    function breadcrumb() {
      $this->reset();
    }

    function reset() {
      $this->_trail = array();
    }

    function add($title, $link = '') {
      $this->_trail[] = array('title' => $title, 'link' => $link);
    }

    function trail($separator = ' - ') {
      $trail_string = '';

      for ($i=0, $n=sizeof($this->_trail); $i<$n; $i++) {
        if (isset($this->_trail[$i]['link']) && ecx_not_null($this->_trail[$i]['link'])) {
          $trail_string .= '<a href="' . $this->_trail[$i]['link'] . '" class="headerNavigation">' . $this->_trail[$i]['title'] . '</a>';
        } else {
          $trail_string .= $this->_trail[$i]['title'];
        }

        if (($i+1) < $n) $trail_string .= $separator;
      }

      return $trail_string;
    }
  }
do_action('extend_breadcrumb');
?>
