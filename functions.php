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
        array( $parent_style )
        // array( $parent_style ),
        // wp_get_theme()->get('Version')
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

function indieweb_publisher_get_post_date() {
	if ( ( comments_open() && ! indieweb_publisher_hide_comments()  ) ) {
		$separator = ' <span class="sep"> ' . apply_filters( 'indieweb_publisher_entry_meta_separator', '|' ) . ' </span>';
	} else {
		$separator = '';
	}

	return indieweb_publisher_posted_on_date() . $separator;
}


// set the default feed to atom
// add_filter('default_feed','atom_default_feed');
// function atom_default_feed() { return 'atom'; }

// remove the rdf and rss 0.92 feeds (nobody ever needs these)
// remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 1 );
// remove_action( 'do_feed_rss', 'do_feed_rss', 10, 1 );

// point those feeds at rss 2 (it is backwards compatible with both of them)
// add_action( 'do_feed_rdf', 'do_feed_rss2', 10, 1 );
// add_action( 'do_feed_rss', 'do_feed_rss2', 10, 1 );

// Add Aperture microsub endpoint to the header so I can own my subscriptions...
// function aperture_endpoint() {
//   echo '<link rel="microsub" href="https://aperture.p3k.io/microsub/146">' . "\n";
// }
// add_action('wp_head', 'aperture_endpoint');


// Small test
// function dtd_testfunction(){
// 	echo 'Here we are now';
// }

// add_action('indieweb_publisher_before_bottom_comment_button', 'dtd_testfunction');

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


add_filter( 'the_content_feed', 'my_content_feed' );
function my_content_feed( $content ) {
	global $post;

	if ( has_category( 'rss-club', $post->ID ) ) {
		$content = $content.'
		<aside class="notice">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" width="16" height="16" style="height: 1.2em; width: 1.2em; vertical-align: text-bottom">
        <path fill="currentColor" d="M 4 4.44 v 2.83 c 7.03 0 12.73 5.7 12.73 12.73 h 2.83 c 0 -8.59 -6.97 -15.56 -15.56 -15.56 Z m 0 5.66 v 2.83 c 3.9 0 7.07 3.17 7.07 7.07 h 2.83 c 0 -5.47 -4.43 -9.9 -9.9 -9.9 Z M 6.18 15.64 A 2.18 2.18 0 0 1 6.18 20 A 2.18 2.18 0 0 1 6.18 15.64" />
      </svg><br />
      <tt>Dit is een geheim bericht voor iedereen. RSS Only. Niet strikt geheim, maar niet direct publiek zichtbaar. Alle vormen van reacties en links zijn welkom. <br />
      <a href="' . get_permalink( get_page_by_path( 'rss-club' ) ) . '">Lees alles over de RSS Club.</a>.<br />
      </tt><br />
    </aside>';
	}

	return $content;
}

add_filter( 'the_excerpt_rss', 'my_excerpt_rss' );
function my_excerpt_rss( $content ) {
	global $post;

	if ( has_category( 'rss-club', $post->ID ) ) {
		// Excerpts usually don't contain HTML. Leave out the link.
		// $content = 'Dit bericht is alleen voor abonnees. ' . $content;

		// However, you probably could get away with it, like so:
		$content = '<p>' . $content . '</p><p>Dit is een geheim bericht voor iedereen. RSS Only. Niet strikt geheim, maar niet direct publiek zichtbaar. Alle vormen van reacties en links zijn welkom. <a href="' . get_permalink( get_page_by_path( 'rss-club' ) ) . '">Lees alles over de RSS Club.</a></p>';
	}

	return $content;
}

// remove_filter( 'the_content', array( 'Kind_View', 'content_response' ), 20 );
// remove_filter( 'the_excerpt', array( 'Kind_View', 'excerpt_response' ), 20 );
remove_filter( 'admin_post_thumbnail_html', 'indieweb_publisher_featured_image_meta' );

function indieweb_publisher_min_comments_comment_title() {
	return 0;
}

add_filter( 'simple_social_disable_custom_css', '__return_true' );

// apply_filters( 'semantic_linkbacks_facepile', true );


add_filter( 'simple_social_default_profiles', 'custom_reorder_simple_icons' );

function custom_reorder_simple_icons( $icons ) {

	// Set your new order here
	$new_icon_order = array(
		'rss'         => '',
		'linkedin'    => '',
		'medium'      => '',
		'twitter'     => '',
		'github'      => '',
		'instagram'   => '',
		'email'       => '',
		'phone'       => '',
		'youtube'     => '',
		// 'behance'     => '',
		// 'bloglovin'   => '',
		// 'dribbble'    => '',
		// 'facebook'    => '',
		// 'flickr'      => '',
		// 'gplus'       => '',
		// 'periscope'   => '',
		// 'pinterest'   => '',
		// 'snapchat'    => '',
		// 'stumbleupon' => '',
		// 'tumblr'      => '',
		// 'vimeo'       => '',
		// 'xing'        => '',
	);


	foreach( $new_icon_order as $icon => $icon_info ) {
		$new_icon_order[ $icon ] = $icons[ $icon ];
	}

	return $new_icon_order;
}

function unspam_webmentions($approved, $commentdata) {
	return $commentdata['comment_type'] == 'webmention' ? 1 : $approved;
  }
  
add_filter('pre_comment_approved', 'unspam_webmentions', '99', 2);