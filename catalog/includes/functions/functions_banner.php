<?php
/** 
* @(#) $Id: functions_validations.php $
* 
* functions for validating email addresses
*
* @package email 
*
* @copyright (ported version)2013 ecomextra 
* @copyright (Most) Portions Copyright 2012 osCommerce
* @license   Released under the GNU General Public License

*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}

////
// Sets the status of a banner
  function ecx_set_banner_status($banners_id, $status) {
    if ($status == '1') {
      return ecx_db_query("update " . TABLE_BANNERS . " set status = '1', date_status_change = now(), date_scheduled = NULL where banners_id = '" . (int)$banners_id . "'");
    } elseif ($status == '0') {
      return ecx_db_query("update " . TABLE_BANNERS . " set status = '0', date_status_change = now() where banners_id = '" . (int)$banners_id . "'");
    } else {
      return -1;
    }
  }

////
// Auto activate banners
  function ecx_activate_banners() {
    $banners_query = ecx_db_query("select banners_id, date_scheduled from " . TABLE_BANNERS . " where date_scheduled != ''");
    if (ecx_db_num_rows($banners_query)) {
      while ($banners = ecx_db_fetch_array($banners_query)) {
        if (date('Y-m-d H:i:s') >= $banners['date_scheduled']) {
          ecx_set_banner_status($banners['banners_id'], '1');
        }
      }
    }
  }

////
// Auto expire banners
  function ecx_expire_banners() {
    $banners_query = ecx_db_query("select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from " . TABLE_BANNERS . " b, " . TABLE_BANNERS_HISTORY . " bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id");
    if (ecx_db_num_rows($banners_query)) {
      while ($banners = ecx_db_fetch_array($banners_query)) {
        if (ecx_not_null($banners['expires_date'])) {
          if (date('Y-m-d H:i:s') >= $banners['expires_date']) {
            ecx_set_banner_status($banners['banners_id'], '0');
          }
        } elseif (ecx_not_null($banners['expires_impressions'])) {
          if ( ($banners['expires_impressions'] > 0) && ($banners['banners_shown'] >= $banners['expires_impressions']) ) {
            ecx_set_banner_status($banners['banners_id'], '0');
          }
        }
      }
    }
  }

////
// Display a banner from the specified group or banner id ($identifier)
  function ecx_display_banner($action, $identifier) {
    if ($action == 'dynamic') {
      $banners_query = ecx_db_query("select count(*) as count from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . ecx_db_input($identifier) . "'");
      $banners = ecx_db_fetch_array($banners_query);
      if ($banners['count'] > 0) {
        $banner = ecx_random_select("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . ecx_db_input($identifier) . "'");
      } else {
        return '<strong>TEP ERROR! (ecx_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</strong>';
      }
    } elseif ($action == 'static') {
      if (is_array($identifier)) {
        $banner = $identifier;
      } else {
        $banner_query = ecx_db_query("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . (int)$identifier . "'");
        if (ecx_db_num_rows($banner_query)) {
          $banner = ecx_db_fetch_array($banner_query);
        } else {
          return '<strong>TEP ERROR! (ecx_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</strong>';
        }
      }
    } else {
      return '<strong>TEP ERROR! (ecx_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</strong>';
    }

    if (ecx_not_null($banner['banners_html_text'])) {
      $banner_string = $banner['banners_html_text'];
    } else {
      $banner_string = '<a href="' . ecx_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '" target="_blank">' . ecx_image(DIR_WS_IMAGES . $banner['banners_image'], $banner['banners_title']) . '</a>';
    }

    ecx_update_banner_display_count($banner['banners_id']);

    return $banner_string;
  }

////
// Check to see if a banner exists
  function ecx_banner_exists($action, $identifier) {
    if ($action == 'dynamic') {
      return ecx_random_select("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . ecx_db_input($identifier) . "'");
    } elseif ($action == 'static') {
      $banner_query = ecx_db_query("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . (int)$identifier . "'");
      return ecx_db_fetch_array($banner_query);
    } else {
      return false;
    }
  }

////
// Update the banner display statistics
  function ecx_update_banner_display_count($banner_id) {
    $banner_check_query = ecx_db_query("select count(*) as count from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    $banner_check = ecx_db_fetch_array($banner_check_query);

    if ($banner_check['count'] > 0) {
      ecx_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_shown = banners_shown + 1 where banners_id = '" . (int)$banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    } else {
      ecx_db_query("insert into " . TABLE_BANNERS_HISTORY . " (banners_id, banners_shown, banners_history_date) values ('" . (int)$banner_id . "', 1, now())");
    }
  }

////
// Update the banner click statistics
  function ecx_update_banner_click_count($banner_id) {
    ecx_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_clicked = banners_clicked + 1 where banners_id = '" . (int)$banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
  }
?>
