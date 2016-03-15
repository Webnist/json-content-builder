<?php
class JsonContentBuilderAdminParts extends JsonContentBuilderInit {

	public function __construct() {
		parent::__construct();
	}

	public function file_field( $args ) {
		extract( $args );

		$id      = ! empty( $id ) ? $id : $name;
		$desc    = ! empty( $desc ) ? $desc : '';
		$class   = ! empty( $class ) ? $class: 'regular-text';
		$output  = '<input type="file" name="' . $name .'" id="' . $id .'" class="' . $class . '" value="' . $value .'" />' . "\n";
		if ( $desc )
			$output .= '<p class="description">' . $desc . '</p>' . "\n";

		echo $output;
	}

}
