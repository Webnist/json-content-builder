<?php
/**
 * Plugin Name: json content builder
 * Version: 0.7.1
 * Description: json content builder
 * Author: Webnist
 * Author URI: http://profiles.wordpress.org/webnist
 * Plugin URI: http://example.org
 * Text Domain: json-content-builder
 * Domain Path: /languages
 * @package Json-content-builder
 */

new JsonContentBuilderInit();
class JsonContentBuilderInit {

	public function __construct() {
		$this->basename        = dirname( plugin_basename(__FILE__) );
		$this->dir             = plugin_dir_path( __FILE__ );
		$this->url             = plugin_dir_url( __FILE__ );
		$headers               = array(
			'name'        => 'Plugin Name',
			'version'     => 'Version',
			'domain'      => 'Text Domain',
			'domain_path' => 'Domain Path',
		);
		$data                  = get_file_data( __FILE__, $headers );
		$this->name            = $data['name'];
		$this->version         = $data['version'];
		$this->domain          = $data['domain'];
		$this->domain_path     = $data['domain_path'];
		$this->default_options = array();
		load_plugin_textdomain( $this->domain, false, $this->basename . $this->domain_path );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded() {

		// include
		$include_dir = $this->dir . 'includes/';
		$this->get_files( $include_dir );

	}

	public function get_files( $dir ) {
		if ( is_dir( $dir ) ) {
			if ( $dh = opendir( $dir ) ) {
				while ( false !== ( $file = readdir( $dh ) ) ) {
					if ( empty( $included ) && strtolower( substr( $file, -4 ) ) === '.php' ) {
						require_once $dir . $file;
					}
				}
				closedir( $dh );
			}
		}
	}

}
