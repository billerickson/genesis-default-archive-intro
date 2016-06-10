<?php
/**
 * Plugin Name: Genesis Default Archive Intro
 * Plugin URI: https://github.com/billerickson/genesis-default-archive-intro
 * Description: 
 * Version: 1.0.0
 * Author: Bill Erickson
 * Author URI: http://www.billerickson.net
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package DPS Columns Extension
 * @version 1.0.0
 * @author Bill Erickson <bill@billerickson.net>
 * @copyright Copyright (c) 2016, Bill Erickson
 * @link http://www.billerickson.net/shortcode-to-display-posts/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Default Titles/Descriptions for Term Archives
 *
 * @author Bill Erickson
 * @see http://www.billerickson.net/default-category-and-tag-titles
 *
 * @param string $headline
 * @param object $term
 * @return string $headline
 */
function be_genesis_default_archive_intro( $value, $term_id, $meta_key, $single ) {

	$options = array(
		'headline'   => 'name',
		'intro_text' => 'description',
	);

    if( ( is_category() || is_tag() || is_tax() ) && array_key_exists( $meta_key, $options ) && ! is_admin() ) {

        // Grab the current value, be sure to remove and re-add the hook to avoid infinite loops
        remove_action( 'get_term_metadata', 'be_genesis_default_archive_intro', 10 );
        $value = get_term_meta( $term_id, $meta_key, true );
        add_action( 'get_term_metadata', 'be_genesis_default_archive_intro', 10, 4 );

        // Use fallback if empty
        if( empty( $value ) ) {
            $term = get_term_by( 'term_taxonomy_id', $term_id );
            $value = $term->$options[$meta_key];
        }

    }

    return $value;      
}
add_filter( 'get_term_metadata', 'be_genesis_default_archive_intro', 10, 4 );