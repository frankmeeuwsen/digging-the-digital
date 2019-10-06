<?php
/**
 * Custom template tags for this childtheme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package DTD Indieweb Publisher
 * @since   DTD Indieweb Publisher 1.0
 */


if ( !function_exists( 'dtd_publisher_show_excerpt' ) ):
	/*
	 * Determines if an excerpt should be shown for a given post. Used in the loop.
	 */
	function dtd_publisher_show_excerpt() {
		/* Only show excerpts for Standard post format OR Chat format,
		 * when this is not both the very first standard post and also a Sticky post AND
		 * when excerpts enabled or One-Sentence Excerpts enabled AND
		 * this is not the very first standard post when Show Full Content First Post enabled
		 */
		if ( ( !get_post_format() || 'chat' === get_post_format() ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
endif;