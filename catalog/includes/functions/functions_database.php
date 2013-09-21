<?php
/** 
* @(#) $Id: functions_database.php $
* 
* Database functions used site-wide.
*
* @package functions
*
* @copyright 2013 ecomextra
* @copyright Portions Copyright 2003 osCommerce
* @license   Released under the GNU General Public License. 
*/
if(!defined('IN_ECX')){
die ("Illegal access attempt");
}
  function ecx_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
       $$link =  mysqli_connect('p:' . $server,  $username,  $password);
     } else {
       $$link = mysqli_connect($server,  $username,  $password, $database);
     }
 
     if ($$link) mysqli_select_db($$link,$database);
 
     return $$link;
   }

  function ecx_db_close($link = 'db_link') {
    global $$link;
/* determine our thread id */
$thread_id = mysqli_thread_id($link);

/* Kill connection */
mysqli_kill($$link, $thread_id);
    return  mysqli_close($$link);
  }

  function ecx_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function ecx_db_query($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysqli_query( $$link, $query) or ecx_db_error($query, mysqli_errno(), mysqli_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysqli_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function ecx_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . ecx_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . ecx_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return ecx_db_query($query, $link);
  }

  function ecx_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query,  MYSQLI_ASSOC);
  }

  function ecx_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function ecx_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query,  $row_number);
  }

  function ecx_db_insert_id($link = 'db_link') {
    global $$link;

    return  mysqli_insert_id($$link);
  }

  function ecx_db_free_result($db_query) {
    return mysqli_free_result($db_query);
  }

  function ecx_db_fetch_fields($db_query) {
    return mysqli_fetch_field($db_query);
  }

  function ecx_db_output($string) {
    return htmlspecialchars($string);
  }

  function ecx_db_input($string, $link = 'db_link') {
    global $$link;

    if (function_exists('mysqli_real_escape_string')) {
      return mysqli_real_escape_string( $$link, $string);
    } elseif (function_exists('mysqli_escape_string')) {
      return mysqli_escape_string($$link, $string);
    }

    return addslashes($string);
  }

  function ecx_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(ecx_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = ecx_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
    function ecx_db_multi_query($query, $link = 'db_link') {
      global $$link;
      
      if (!$$link) return;
      if (!is_array($query)) return ecx_db_query($query, $link);
      
      $query_text = is_array($query)? implode(";", $query) : $query;
  
      if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        error_log('QUERY ' . $query_text . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      }
      
      $results = array();
      if (mysqli_multi_query($$link, $query_text)) {
          do {
              /* store first result set */
              if ($result = mysqli_store_result($$link)) {
                  $results[] = $result;
              }
          } while (mysqli_more_results($$link) && mysqli_next_result($$link));
      } else {
          ecx_db_error($query_text, mysqli_errno(), mysqli_error());
      }
      
      if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
         $result_error = mysqli_error();
         error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      }
  
      return $results;
    }
?>
