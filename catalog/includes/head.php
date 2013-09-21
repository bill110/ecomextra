<?php
/** 
* @(#) $Id: head.php $
* 
* @package common 
*
* @copyright 2013 ecomextra
* @copyright Portions Copyright 2010 osCommerce
* @license   Released under the GNU General Public License. 
*/

$tpl->assign(array(
	'PARAMS' => LNG_HTML_PARAMS,
	'CHARSET' => LNG_CHARSET,
	'BASE' => (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG
	));
	if(! in_array('SCRIPT',$tpl->PARSEVARS)){
 $script_links='<script type="text/javascript" src="./jscript/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="./jscript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js"></script>
	<script type="text/javascript" src="./jscript/knockout-2.3.0.js"></script>';
 $tpl->assign('SCRIPT',$script_links);
 }
 do_action('headerScripts');
?>