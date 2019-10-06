<?php
/**
 * Custom template tags for this childtheme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Indieweb Publisher
 * @since   Indieweb Publisher 1.0
 */

if ( ! function_exists( 'indieweb_publisher_the_posts_navigation' ) ) :
	/**
	 * Customized Post Navigation
	 */
	function indieweb_publisher_the_posts_navigation() {
		if ( function_exists( 'wp_pagenavi' ) ) { // WP-PageNavi Support
			wp_pagenavi();
		} else {
			the_posts_navigation( 
				array(
					'mid_size' => 2,
					'prev_text' => sprintf( '<button><span class="meta-nav">&larr;</span>%1$s</button>', __( 'Ouwe meuk', 'indieweb-publisher' ) ),
					'next_text' => sprintf( '<button><span class="meta-nav">&rarr;</span>%1$s</button>', __( 'Nieuwere meuk', 'indieweb-publisher' ) )
				)
			);
		}
	}
endif; // indieweb_publisher_the_posts_navigation


if ( !function_exists( 'indieweb_publisher_show_excerpt' ) ):
	/*
	 * Determines if an excerpt should be shown for a given post. Used in the loop.
	 */
	function indieweb_publisher_show_excerpt() {
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