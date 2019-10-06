<?php

/*
 * You can add your own functions here. You can also override functions that are
 * called from within the parent theme. For a complete list of function you can
 * override here, please see the docs:
 *
 * https://github.com/raamdev/independent-publisher#functions-you-can-override-in-a-child-theme
 *
 */


/*
 * Uncomment the following to add a favicon to your site. You need to add favicon
 * image to the images folder of Independent Publisher Child Theme for this to work.
 */
/*
function blog_favicon() {
  echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('stylesheet_directory').'/images/favicon.ico" />' . "\n";
}
add_action('wp_head', 'blog_favicon');
*/

/*
 * Add version number to main style.css file with version number that matches the
 * last modified time of the file. This helps when making frequent changes to the
 * CSS file as the browser will always load the newest version.
 */ 
function dtd_publisher_stylesheet() {
	wp_enqueue_style( 'dtd-style', get_stylesheet_uri(), '', filemtime( get_stylesheet_directory() . '/style.css') );
}


/**
 * Custom template tags for this theme.
 */

// function dtd_publisher_setup() {
// 	require get_template_directory() . '/inc/template-tags.php';
// }



//  Loads the parent stylesheet from indieweb publisher and overrides the one in the parent theme
add_action( 'wp_enqueue_scripts', 'indieweb_publisher_stylesheet' );
function indieweb_publisher_stylesheet() {
	$parent_style = 'parent-style'; 
	wp_enqueue_style( $parent_style, 
		get_template_directory_uri() . '/css/default.min.css',
		wp_get_theme()->get('Version'));
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

function ww_load_dashicons(){
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'ww_load_dashicons', 999);

/*
 * Modifies the default theme footer.
 * This also applies the changes to JetPack's Infinite Scroll footer, if you're using that module.
 */

function indieweb_publisher_footer_credits() {
	$my_custom_footer = 'Made with ❤️ in Utrecht, based on the <a href="https://github.com/dshanske/indieweb-publisher">Indieweb Publisher theme</a> ';
	return $my_custom_footer;
}

function dtd_change_separator() {
    return '//';
}
add_filter( 'indieweb_publisher_entry_meta_separator', 'dtd_change_separator' );

function indieweb_publisher_maybe_linkify_the_content( $content ) {
	if ( ! is_single() && ( 'aside' === get_post_format() || 'quote' === get_post_format() ) ) {

		// Asides and Quotes might have footnotes with anchor tags, or just anchor tags, both of which would screw things up when linking the content to itself (anchors cannot have anchors inside them), so let's clean things up
		$content = indieweb_publisher_clean_content( $content );

		// Now we can link the Quote or Aside content to itself
		// $content = '<a href="' . get_permalink() . '" rel="bookmark" title="' . indieweb_publisher_post_link_title() . '">' . $content . '</a>';
	}

	return $content;
}

function indieweb_publisher_posted_on_date() {
	printf(
		'<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date dt-published" datetime="%3$s" itemprop="datePublished" pubdate="pubdate">%5$s %4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_title() ),
		esc_attr( get_the_date( DATE_ISO8601 ) ),
		esc_html( get_the_time() ),
		esc_attr( get_the_date( ) )
	);
}

// set the default feed to atom
// add_filter('default_feed','atom_default_feed');
// function atom_default_feed() { return 'atom'; }

// remove the rdf and rss 0.92 feeds (nobody ever needs these)
remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 1 );
remove_action( 'do_feed_rss', 'do_feed_rss', 10, 1 );

// point those feeds at rss 2 (it is backwards compatible with both of them)
add_action( 'do_feed_rdf', 'do_feed_rss2', 10, 1 );
add_action( 'do_feed_rss', 'do_feed_rss2', 10, 1 );

// Add Aperture microsub endpoint to the header so I can own my subscriptions...
function aperture_endpoint() {
  echo '<link rel="microsub" href="https://aperture.p3k.io/microsub/146">' . "\n";
}
add_action('wp_head', 'aperture_endpoint');

// Add newsletter subscription below blogposts

register_sidebar( array(
	'name'          => 'Post Footer',
	'id'            => 'postfooter',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div> <!-- end .widget -->',
	'before_title'  => '<h2 class="widget-title">',
	'after_title'   => '</h2>',
) );

/**
 * Enable Post Thumbnails
 */
add_theme_support( 'post-thumbnails' );

/*
	* Add custom thumbnail size for use with featured images
	*/

add_image_size( 'indieweb_publisher_post_frontpage', 200, 200 );

// function indieweb_publisher_continue_reading_text($content) {
// 	return $content = $content.'Continue reading <span class="meta-nav">&rarr;</span>';
// }

// function dtd_rssclub($content) {
// 	global $wp_query;
// 	$postid = $wp_query->post->ID;
// 	if(is_feed() && has_category('kinds', $postid) ) {
// 		$content = '<div>Dit is een geheim bericht voor iedereen. </div><br /><br />';
// 		}
// 	else {
// 	$content = $content;	
// 	}
// 	return $content;
// 	}
// 	add_filter('the_excerpt_rss', 'dtd_rssclub');
// 	add_filter('the_content_rss', 'dtd_rssclub');