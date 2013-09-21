<?php
/** 
* @(#) $Id: gzip_compression.php $
* 
* Functions to handle gzip compression.
*
* @package gzip 
*
* @copyright 2013 ecomextra
* @copyright (Most) Portions Copyright 2012 osCommerce
* @license   Released under the GNU General Public License. 
*/

  function ecx_check_gzip() {
    global $HTTP_ACCEPT_ENCODING;

    if (headers_sent() || connection_aborted()) {
      return false;
    }

    if (strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false) return 'x-gzip';

    if (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false) return 'gzip';

    return false;
  }

/* $level = compression level 0-9, 0=none, 9=max */
  function ecx_gzip_output($level = 5) {
    if ($encoding = ecx_check_gzip()) {
      $contents = ob_get_contents();
      ob_end_clean();

      header('Content-Encoding: ' . $encoding);

      $size = strlen($contents);
      $crc = crc32($contents);

      $contents = gzcompress($contents, $level);
      $contents = substr($contents, 0, strlen($contents) - 4);

      echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
      echo $contents;
      echo pack('V', $crc);
      echo pack('V', $size);
    } else {
      ob_end_flush();
    }
  }
?>
