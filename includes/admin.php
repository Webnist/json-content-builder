<?php
new JsonContentBuilderAdmin();
class JsonContentBuilderAdmin extends JsonContentBuilderInit {

	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'add_fields' ) );
		add_filter( 'admin_init', array( $this, 'builder' ) );
	}

	public function admin_menu() {
		$plugin_page = add_menu_page( __( $this->name, $this->domain ), __( $this->name, $this->domain ), 'create_users', $this->basename, array( $this, 'add_admin_edit_page' ), '
dashicons-clipboard' );
	}

	public function add_admin_edit_page() {
		echo '<div id="options-wrap" class="wrap">' . "\n";
		echo '<h2>' . esc_html( get_admin_page_title() ) . '</h2>' . "\n";
		echo '<form method="post" action="" enctype="multipart/form-data">' . "\n";
		settings_fields( $this->basename );
		do_settings_sections( $this->basename );
		echo '<div class="submit">' . "\n";
		submit_button( __( 'Builder', $this->domain ), 'primary', $this->domain );
		echo '</div>' . "\n";
		echo '</form>' . "\n";
		echo '</div>' . "\n";
	}

	public function add_fields() {
		$parts = new JsonContentBuilderAdminParts();

		add_settings_section(
			'json',
			__( 'Import json', $this->domain ),
			'',
			$this->basename
		);

		add_settings_field(
			'json-file',
			__( 'Select Data', $this->domain ),
			array( $parts, 'file_field' ),
			$this->basename,
			'json',
			array(
				'name' => 'json-builder',
			)

		);

	}

	public function register_setting() {
		register_setting( $this->basename, 'json-builder', array( $this, 'builder' ) );
	}

	public function builder() {
		$nonce = $_REQUEST['_wpnonce'];

		if ( ! wp_verify_nonce( $nonce, $this->basename . '-options' ) )
			return;

		$file = $_FILES['json-builder'];
		if ( ! isset( $file ) || empty( $file ) || $file['error'] || empty( $file['tmp_name'] ) )
			return;

		$mimes    = array( 'json' => 'application/json' );
		$filetype = wp_check_filetype( $file['name'], $mimes );

		if ( ! $ext = $filetype['ext'] )
			return false;

		$json  = json_decode( file_get_contents( $file['tmp_name'] ), true );
		$type  = $json['type'];
		$content = $json['content'];
		if ( empty( $type ) || 'post' === $type ) {
			$this->insert_post( $content );
		} elseif ( 'term' === $type ) {
			$this->insert_term( $content );
		}
	}

	public function insert_post( $content, $parent = 0 ) {
		if ( empty( $content ) )
			return;

		$count = 1;
		foreach ( $content as $key => $value ) {
			$post_title  = $key;
			$post_name   = $value['name'];
			$post_type   = $value['post_type'];
			$child       = $value['child'];
			$insert_post = array(
				'post_name'   => $post_name,
				'post_title'  => $post_title,
				'post_type'   => $post_type,
				'post_parent' => $parent,
				'post_status' => 'publish',
				'menu_order'  => $count,
			);
			$post_id = wp_insert_post( $insert_post );
			if ( ! empty( $child ) )
				$this->insert_post( $child, $post_id );

			$count++;
		}
		return;
	}

	public function insert_term( $content, $parent = 0 ) {
		if ( empty( $content ) )
			return;

		foreach ( $content as $key => $value ) {
			$term_name   = $key;
			$taxonomy    = $value['taxonomy'];
			$slug        = $value['slug'];
			$child       = $value['child'];
			$args        = array(
				'parent' => $parent,
				'slug'   => $slug,
			);
			$term = wp_insert_term( $term_name, $taxonomy, $args );

			if ( is_wp_error( $term ) ) {
				$term_id = $term->get_error_data();
			} else {
				$term_id = $term['term_id'];
			}

			if ( ! empty( $child ) )
				$this->insert_term( $child, $term_id );

		}
		return;
	}
}
