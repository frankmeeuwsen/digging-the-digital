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
/*
function independent_publisher_stylesheet() {
	wp_enqueue_style( 'independent-publisher-style', get_stylesheet_uri(), '', filemtime( get_stylesheet_directory() . '/style.css') );
}
*/

/*
 * Modifies the default theme footer.
 * This also applies the changes to JetPack's Infinite Scroll footer, if you're using that module.
 */
/*
function independent_publisher_footer_credits() {
	$my_custom_footer = 'This is my custom footer.';
	return $my_custom_footer;
}
*/

function dtd_change_separator() {
    return '//';
}
add_filter( 'independent_publisher_entry_meta_separator', 'dtd_change_separator' );

function independent_publisher_maybe_linkify_the_content( $content ) {
	if ( ! is_single() && ( 'aside' === get_post_format() || 'quote' === get_post_format() ) ) {

		// Asides and Quotes might have footnotes with anchor tags, or just anchor tags, both of which would screw things up when linking the content to itself (anchors cannot have anchors inside them), so let's clean things up
		$content = independent_publisher_clean_content( $content );

		// Now we can link the Quote or Aside content to itself
		// $content = '<a href="' . get_permalink() . '" rel="bookmark" title="' . independent_publisher_post_link_title() . '">' . $content . '</a>';
	}

	return $content;
}

function independent_publisher_posted_on_date() {
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
add_filter('default_feed','atom_default_feed');
function atom_default_feed() { return 'atom'; }

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

// Add extra feeds in feed discovery 
function kindfeed_discovery() {
	echo '<link rel="alternate" type="application/atom+xml" title="Digging the Digital » Bookmarks feed" href="https://diggingthedigital.com/kind/bookmark/feed/">' . "\n";
	echo '<link rel="alternate" type="application/atom+xml" title="Digging the Digital » Likes feed" href="https://diggingthedigital.com/kind/like/feed/">' . "\n";
  }
  add_action('wp_head', 'kindfeed_discovery');

//   remove h-entry from body and add it to the post class somehow
add_filter( 'body_class', 'remove_hentry', 100, 2 );

function remove_hentry( $wp_classes, $extra_classes ) {

    # List tag to delete
    $class_delete = array('h-entry', 'hentry');

    # Verify if exist the class of WP in $class_delete
    foreach ($wp_classes as $class_css_key => $class_css) {
        if (in_array($class_css, $class_delete)) {
            unset($wp_classes[$class_css_key]);
        }
    }

    // Add the extra classes back untouched
    return array_merge( $wp_classes, (array) $extra_classes );
}

// Add specific CSS class by body.
add_filter( 'post_class', function( $classes ) {
    return array_merge( $classes, array('h-entry') );
} );

// Remove invisible author card below single posts
// remove_action( 'the_content', 'independent_publisher_before_post_author_bottom_card' );
do_action('independent_publisher_after_post_author_bottom_card');
function independent_publisher_after_post_author_bottom_card(){
	return;
}

