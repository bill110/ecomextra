<?php
/** 
* @(#) $Id: action_recorder.class.php $
* 
* Classs to record certin actions performed by admin and customers
*
* @package action recorder 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2010 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

  class actionRecorder {
    var $_module;
    var $_user_id;
    var $_user_name;

    function actionRecorder($module, $user_id = null, $user_name = null) {
      global $language, $PHP_SELF;

      $module = ecx_sanitize_string(str_replace(' ', '', $module));

      if (defined('MODULE_ACTION_RECORDER_INSTALLED') && ecx_not_null(MODULE_ACTION_RECORDER_INSTALLED)) {
        if (ecx_not_null($module) && in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), explode(';', MODULE_ACTION_RECORDER_INSTALLED))) {
          if (!class_exists($module)) {
            if (file_exists(DIR_WS_MODULES . 'action_recorder/' . $module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)))) {
              include(DIR_WS_LANGUAGES . $language . '/modules/action_recorder/' . $module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)));
              include(DIR_WS_MODULES . 'action_recorder/' . $module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)));
            } else {
              return false;
            }
          }
        } else {
          return false;
        }
      } else {
        return false;
      }

      $this->_module = $module;

      if (!empty($user_id) && is_numeric($user_id)) {
        $this->_user_id = $user_id;
      }

      if (!empty($user_name)) {
        $this->_user_name = $user_name;
      }

      $GLOBALS[$this->_module] = new $module();
      $GLOBALS[$this->_module]->setIdentifier();
    }

    function canPerform() {
      if (ecx_not_null($this->_module)) {
        return $GLOBALS[$this->_module]->canPerform($this->_user_id, $this->_user_name);
      }

      return false;
    }

    function getTitle() {
      if (ecx_not_null($this->_module)) {
        return $GLOBALS[$this->_module]->title;
      }
    }

    function getIdentifier() {
      if (ecx_not_null($this->_module)) {
        return $GLOBALS[$this->_module]->identifier;
      }
    }

    function record($success = true) {
      if (ecx_not_null($this->_module)) {
        ecx_db_query("insert into " . TABLE_ACTION_RECORDER . " (module, user_id, user_name, identifier, success, date_added) values ('" . ecx_db_input($this->_module) . "', '" . (int)$this->_user_id . "', '" . ecx_db_input($this->_user_name) . "', '" . ecx_db_input($this->getIdentifier()) . "', '" . ($success == true ? 1 : 0) . "', now())");
      }
    }

    function expireEntries() {
      if (ecx_not_null($this->_module)) {
        return $GLOBALS[$this->_module]->expireEntries();
      }
    }
  }
?>
