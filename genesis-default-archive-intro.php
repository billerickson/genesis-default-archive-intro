<?php
/**
 * Plugin Name: Genesis Default Archive Intro
 * Plugin URI: https://github.com/billerickson/genesis-default-archive-intro
 * Description: If Archive Headline / Intro Text are not specified, they default to Term Name / Description
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
function be_genesis_default_term_archive_intro( $value, $term_id, $meta_key, $single ) {

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
add_filter( 'get_term_metadata', 'be_genesis_default_term_archive_intro', 10, 4 );

/**
 * Default Title for User Archives
 *
 * @param string $value
 * @param int $user_id
 * @return string $value
 */
function be_genesis_default_user_archive_title( $title, $user_id ) {

	if( empty( $title ) ) 
		$title = get_the_author_meta( 'display_name', $user_id );
		
	return $title;
}
add_filter( 'get_the_author_headline', 'be_genesis_default_user_archive_title', 10, 2 );

/**
 * Default Description for User Archives
 *
 * @param string $description
 * @param int $user_id
 * @return string $description
 */
function be_genesis_default_user_archive_description( $description, $user_id ) {

	if( empty( $description ) )
		$description = get_the_author_meta( 'description', $user_id );

	return $description;
}
add_filter( 'get_the_author_intro_text', 'be_genesis_default_user_archive_description', 10, 2 );