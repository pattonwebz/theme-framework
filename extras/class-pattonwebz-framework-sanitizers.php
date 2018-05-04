<?php
/**
 * START SANITIZATION FILTERS.
 */

namespace PattonWebz\Framework;

class Sanitizers {

	/**
	 * Sanitization for textarea field against list of allwed tags in posts.
	 *
	 * @param string $input     text area string to sanitize.
	 *
	 * @return string $output   sanitized string.
	 */
	public static function textarea( $input ) {
		global $allowedposttags;
		$output = wp_kses( $input, $allowedposttags );
		return $output;
	}

	/**
	 * Sanitization for checkbox input
	 *
	 * @param booleen $input    we either have a value or it's empty to depict
	 *                          a checkbox state.
	 * @return booleen $output
	 */
	public static function checkbox_truefalse( $input ) {
		// Checkbox is booleen, it can only be in 2 states, if we have any input
		// consider it as true otherwise it's false.
		if ( $input ) {
			$output = true;
		} else {
			$output = false;
		}
		return $output;
	}

	/**
	 * Santization for image uploads.
	 *
	 * @param  string $input    This should be a direct url to an image file.
	 *
	 * @return string           Return an excaped url to a file.
	 */
	public static function image_type( $input ) {

		// allowed file types.
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
		);

		// check file type from file name.
		$file_ext = wp_check_filetype( $input, $mimes );

		// if filetype matches the allowed types set above then cast to output,
		// otherwise pass empty string.
		$output = ( $file_ext['ext'] ? $input : '' );

		// if file has a valid mime type return it as raw url.
		return esc_url_raw( $output );
	}

	/**
	 * Sanitize inputs of select box customizer setting.
	 *
	 * NOTE: This only sanitizes against the list of select box choices.
	 *
	 * @param  string $input   String containing a value from select box.
	 * @param  mixed  $setting Object containing the info about the
	 *                         settings/control that is being sanitized.
	 *
	 * @return string containing a value to save as select box value.
	 */
	public static function customizer_select( $input, $setting ) {

		// get the list of possible select options from setting being sanitized.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// return input if matching an item in the choices array
		// otherwise return default option - expects no html so escape value.
		return esc_html( ( array_key_exists( $input, $choices ) ? $input : $setting->default ) );

	}

	/**
	 * Sanitize term ids from a select box customizer setting.
	 *
	 * NOTE: This only sanitizes as an integer - it does not validate as an existing term.
	 *
	 * @param  integer $input   An integer representing category id.
	 * @param  mixed   $setting Object containeing the info about the
	 *                          settings/control that is being sanitized.
	 *
	 * @return integer
	 */
	public static function term_id_select( $input, $setting ) {

		// input must be a integer.
		$input = absint( $input );

		// if we have a valid number then pass it through the default select
		// sanitizer then return it as integer.
		return absint( self::sanitize_category_select( $input, $setting ) );

	}

}
