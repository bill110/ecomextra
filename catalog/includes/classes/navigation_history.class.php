<?php
/** 
* @(#) $Id: navigation_history.php $
* 
* Functions used used site-wide.
*
* @package Navigation 
*
* @copyright 2013 ecomextra
* @copyright (Most) Portions Copyright 2012 osCommerce
* @changes changes made to accomodate depreciated PHP session functions
* @license   Released under the GNU General Public License. 
*/

if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
  class navigationHistory {
    var $path, $snapshot;

    function navigationHistory() {
      $this->reset();
    }

    function reset() {
      $this->path = array();
      $this->snapshot = array();
    }

    function add_current_page() {
      global $PHP_SELF, $_GET, $_POST, $request_type, $cPath;

      $set = 'true';
      for ($i=0, $n=sizeof($this->path); $i<$n; $i++) {
        if ( ($this->path[$i]['page'] == basename($PHP_SELF)) ) {
          if (isset($cPath)) {
            if (!isset($this->path[$i]['get']['cPath'])) {
              continue;
            } else {
              if ($this->path[$i]['get']['cPath'] == $cPath) {
                array_splice($this->path, ($i+1));
                $set = 'false';
                break;
              } else {
                $old_cPath = explode('_', $this->path[$i]['get']['cPath']);
                $new_cPath = explode('_', $cPath);

                for ($j=0, $n2=sizeof($old_cPath); $j<$n2; $j++) {
                  if ($old_cPath[$j] != $new_cPath[$j]) {
                    array_splice($this->path, ($i));
                    $set = 'true';
                    break 2;
                  }
                }
              }
            }
          } else {
            array_splice($this->path, ($i));
            $set = 'true';
            break;
          }
        }
      }

      if ($set == 'true') {
        $this->path[] = array('page' => basename($PHP_SELF),
                              'mode' => $request_type,
                              'get' => $this->filter_parameters($_GET),
                              'post' => $this->filter_parameters($_POST));
      }
    }

    function remove_current_page() {
      global $PHP_SELF;

      $last_entry_position = sizeof($this->path) - 1;
      if ($this->path[$last_entry_position]['page'] == basename($PHP_SELF)) {
        unset($this->path[$last_entry_position]);
      }
    }

    function set_snapshot($page = '') {
      global $PHP_SELF, $_GET, $_POST, $request_type;

      if (is_array($page)) {
        $this->snapshot = array('page' => $page['page'],
                                'mode' => $page['mode'],
                                'get' => $this->filter_parameters($page['get']),
                                'post' => $this->filter_parameters($page['post']));
      } else {
        $this->snapshot = array('page' => basename($PHP_SELF),
                                'mode' => $request_type,
                                'get' => $this->filter_parameters($_GET),
                                'post' => $this->filter_parameters($_POST));
      }
    }

    function clear_snapshot() {
      $this->snapshot = array();
    }

    function set_path_as_snapshot($history = 0) {
      $pos = (sizeof($this->path)-1-$history);
      $this->snapshot = array('page' => $this->path[$pos]['page'],
                              'mode' => $this->path[$pos]['mode'],
                              'get' => $this->path[$pos]['get'],
                              'post' => $this->path[$pos]['post']);
    }

    function debug() {
      for ($i=0, $n=sizeof($this->path); $i<$n; $i++) {
        echo $this->path[$i]['page'] . '?';
        while (list($key, $value) = each($this->path[$i]['get'])) {
          echo $key . '=' . $value . '&';
        }
        if (sizeof($this->path[$i]['post']) > 0) {
          echo '<br />';
          while (list($key, $value) = each($this->path[$i]['post'])) {
            echo '&nbsp;&nbsp;<strong>' . $key . '=' . $value . '</strong><br />';
          }
        }
        echo '<br />';
      }

      if (sizeof($this->snapshot) > 0) {
        echo '<br /><br />';

        echo $this->snapshot['mode'] . ' ' . $this->snapshot['page'] . '?' . ecx_array_to_string($this->snapshot['get'], array(ecx_session_name())) . '<br />';
      }
    }

    function filter_parameters($parameters) {
      $clean = array();

      if (is_array($parameters)) {
        reset($parameters);
        while (list($key, $value) = each($parameters)) {
          if (strpos($key, '_nh-dns') < 1) {
            $clean[$key] = $value;
          }
        }
      }

      return $clean;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
  }
  /** used to extend class */
  do_action('extend_navigation_history');
?>