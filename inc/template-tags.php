<?php
/**
 * Custom template tags for this childtheme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package DTD Indieweb Publisher
 * @since   DTD Indieweb Publisher 1.0
 */

if ( !function_exists( 'indieweb_publisher_content_nav' ) ) :
	/**
	 * Display navigation to next/previous pages when applicable
	 *
	 * @since Independent Publisher 1.0
	 */
	function independent_publisher_content_nav( $nav_id ) {
		global $wp_query, $post;

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );

			if ( !$next && !$previous ) {
				return;
			}
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$nav_class = 'site-navigation paging-navigation';
		if ( is_single() ) {
			$nav_class = 'site-navigation post-navigation';
		}

		?>
		<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'independent-publisher' ); ?></h1>

			<?php if ( is_single() ) : // navigation links for single posts ?>

				<?php wp_pagenavi(); ?>

				<?php previous_post_link( '<div class="nav-previous"><button>%link</button></div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'independent-publisher' ) . '</span> %title' ); ?>
				<?php next_post_link( '<div class="nav-next"><button>%link</button></div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'independent-publisher' ) . '</span>' ); ?>

			<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

				<?php if (function_exists('wp_pagenavi')) : // WP-PageNavi support ?>

					<?php wp_pagenavi(); ?>

				<?php else : ?>

					<?php if ( get_next_posts_link() ) : ?>
						<div class="nav-previous"><?php next_posts_link( '<button>' . __( '<span class="meta-nav">&larr;</span> Older posts', 'independent-publisher' ) . '</button>' ); ?></div>
					<?php endif; ?>

					<?php if ( get_previous_posts_link() ) : ?>
						<div class="nav-next"><?php previous_posts_link( '<button>' . __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'independent-publisher' ) . '</button>' ); ?></div>
					<?php endif; ?>

				<?php endif; ?>

			<?php endif; ?>

		</nav><!-- #<?php echo $nav_id; ?> -->
		<?php
	}
endif; // independent_publisher_content_nav
