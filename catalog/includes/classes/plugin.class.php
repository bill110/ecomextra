<?php
 /** 
 * @(#) $Id: plugin.class.php.php $
 *
 *Class for plugins
 *
 * @copyright 2013 ecomextra
 * portion of phphooks.class copyright and 
 * author eric.wzy@gmail.com
 * @version 1.1
 * @package phphooks
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */

class ecxplugins {
	
	/**
	 * plugins option data
	 * @var array
	 */
	var $plugins = array ();
	
	/**
	 * active plugins
	 * @var array
	 */
	var $active_plugins = NULL;
	
	/**
	 * plugins header info
	 * @var array
	 */
	var $plugins_header = array();
	
	/**
	 construct the class to allow $this->active_plugins to be available
	 */
	function __construct(){
	
	  $sql=ecx_db_query("SELECT filename FROM " . TABLE_PLUGINS . " WHERE action = '1' ");
	  $result_rows = ecx_db_fetch_array( $sql ); 
	    if($result_rows !=''){
	      foreach ( $result_rows as $key =>$value ){
	  	$plugins[] = $result_rows[$key];
		  
	      }
		$this->active_plugins = $plugins;
	    }
	}
	/**
	 * load plugins from specific folder, includes *.plugin.php files
	 * @package plugins
	 * 
	 * @param string $from_folder optional. load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
	 */
	function load_active_plugins($from_folder = DIR_WS_PLUGINS) {
	
				if($this->active_plugins != NULL){
				if ($handle = @opendir ( $from_folder )) {			
			while ( $file = readdir ( $handle ) ) {
				if (is_file ( $from_folder . $file )) {
					if (in_array ( $file, $this->active_plugins) && strpos ( $from_folder . $file, '.plugin.php' )) {
						require_once $from_folder . $file;
					}
				} else if ((is_dir ( $from_folder . $file )) && ($file != '.') && ($file != '..')) {
					$this->load_active_plugins ( $from_folder . $file . '/' );
				}
			}			
			closedir ( $handle );
		}
	    }
	}	
	/**
	 * return the all plugins ,which is stored in the plugin folder, header information.
	 * 
	 * @package phphooks
	 * @since 1.1
	 * @param string $from_folder optional. load plugins from folder, if no argument is supplied, a 'plugins/' constant will be used
	 * @return array. return the all plugins ,which is stored in the plugin folder, header information.
	 */
	function get_plugins_header($from_folder = DIR_WS_PLUGINS) {
		
		if ($handle = @opendir ( $from_folder )) {
			
			while ( $file = readdir ( $handle ) ) {
				if (is_file ( $from_folder . $file )) {
					if (strpos ( $from_folder . $file, '.plugin.php' )) {
						$fp = fopen ( $from_folder . $file, 'r' );
						// Pull only the first 8kiB of the file in.
						$plugin_data = fread ( $fp, 8192 );
						fclose ( $fp );
						
						preg_match ( '|Plugin Name:(.*)$|mi', $plugin_data, $name );
						preg_match ( '|Plugin URI:(.*)$|mi', $plugin_data, $uri );
						preg_match ( '|Version:(.*)|i', $plugin_data, $version );
						preg_match ( '|Description:(.*)$|mi', $plugin_data, $description );
						preg_match ( '|Author:(.*)$|mi', $plugin_data, $author_name );
						preg_match ( '|Author URI:(.*)$|mi', $plugin_data, $author_uri );
						
						foreach ( array ('name', 'uri', 'version', 'description', 'author_name', 'author_uri' ) as $field ) {
							if (! empty ( ${$field} ))
								${$field} = trim ( ${$field} [1] );
							else
								${$field} = '';
						}
						$plugin_data = array ('filename' => $file, 'Name' => $name, 'Title' => $name, 'PluginURI' => $uri, 'Description' => $description, 'Author' => $author_name, 'AuthorURI' => $author_uri, 'Version' => $version );
						$this->plugins_header [] = $plugin_data;
					}
				} else if ((is_dir ( $from_folder . $file )) && ($file != '.') && ($file != '..')) {
					$this->get_plugins_header ( $from_folder . $file . '/' );
				}
			}
			
			closedir ( $handle );
		}
		return $this->plugins_header;
	}
	
	/**
	 * register plugin data in $this->plugin
	 * @package phphooks
	 * @since 1.0
	 * 
	 * @param string $plugin_id. The name of the plugin.
	 * @param array $data optional.The data the plugin accessorial(default none)
	 */
	function register_plugin($plugin_id, $data = '') {
		foreach ( $data as $key => $value ) {
			$this->plugins [$plugin_id] [$key] = $value;
		}
	}

}
?>