<?php
/*  Author:  Scott Fine | https://www.scott-fine.com
 *  URL: https://slimpress.org
 *  Based on HTML5 Blank functions.php by Todd Motto | @toddmotto
 */

/*** Optional Features:
     (Search this file for relevant terms)

    * Enable/Disable style.css
    * Enable/Disable jQuery
    * Enable/Disable Gutenberg
    * Enable/Disable wp-embed
    * Enable/Disable emojis
    * Enable/Disable Modernizr
    * Enable/Disable Conditionizr
*/

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('slimpress', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/
/**
 * Create HTML list of nav menu items.
 * Replacement for the native Walker, using the description.
 * Removes <ul> and <li> elements from <nav> for simple <nav><a></a></nav> structure
 */

 class SlimPress_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent\n";
    }
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "\n";
    }
 }

// SlimPress navigation
function slimpress_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '%3$s',
		'depth'           => 0,
		'walker'          => new SlimPress_Nav_Walker
		)
	);
}

// Load SlimPress scripts (header.php)
function slimpress_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
      wp_register_script('customscripts', get_template_directory_uri() . '/js/slimpress.min.js', false, '1.0.0'); // Custom scripts
      wp_enqueue_script('customscripts'); // Enqueue
    }
}

// Load SlimPress conditional scripts
function slimpress_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue
    }
}

//Remove JQuery migrate
 function remove_jquery_migrate( $scripts ) {
   if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
     $script = $scripts->registered['jquery'];

     if ( $script->deps ) { // Check whether the script has any dependencies
       $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
     }
   }
 }

// Load SlimPress styles
function slimpress_styles()
{
    wp_register_style('slimpress', get_template_directory_uri() . '/css/slimpress.min.css', array(), '1.0', 'all');
    wp_enqueue_style('slimpress'); // Compiled SCSS files;
    // STYLES.CSS is by default not loaded. You can uncomment below if you want to use it, or you can use _base-styles.scss or create a _custom-styles.scss.
    /*
    wp_register_style('style.css', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('style.css');
    */
}

// Register SlimPress Navigation
function register_slimpress_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'slimpress'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'slimpress'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'slimpress') // Extra Navigation if needed (duplicate as many as needed)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'slimpress'),
        'description' => __('Description for this widget-area...', 'slimpress'),
        'id' => 'widget-area-1',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'slimpress'),
        'description' => __('Description for this widget-area...', 'slimpress'),
        'id' => 'widget-area-2',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function slimpresswp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function slimpresswp_index($length) // Create 20 Word Callback for Index page Excerpts, call using slimpresswp_excerpt('slimpresswp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using slimpresswp_excerpt('slimpresswp_custom_post');
function slimpresswp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function slimpresswp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function slimpress_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'slimpress') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from enqueued stylesheet
function slimpress_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function slimpressgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function slimpresscomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- Please note: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters
\*------------------------------------*/

// Add Actions
add_action('init', 'slimpress_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'slimpress_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'slimpress_styles'); // Add Theme Stylesheet
add_action('init', 'register_slimpress_menu'); // Add SlimPress Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'slimpresswp_pagination'); // Add slimpress Pagination
add_action('wp_default_scripts', 'remove_jquery_migrate'); // Disable loading of jQuery Migrate

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'slimpressgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'slimpress_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'slimpress_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

/*-------------------------------------------------------------*\
 Enable & Disable Features:
    - jQuery
    - Gutenberg
    - WP_Embed
    - Emojis
    - Modernizr
    - Conditionizr
\*------------------------------------------------------------*/

//jQuery is disabled by default; comment out below to enable it
function disable_jquery() {
    if ( !is_admin() ) wp_deregister_script('jquery');
} // Disables jQuery (1 of 2)

add_action( 'wp_enqueue_scripts', 'disable_jquery' ); // Disables jQuery (2 of 2)

// Gutenberg is disabled by default; comment out below to enable it.
function remove_gutenberg_styles() { wp_dequeue_style( 'wp-block-library' ); } // Disables Gutenberg (1 of 3)
add_action('wp_enqueue_scripts', 'remove_gutenberg_styles', 100); // Disables Gutenberg (2 of 3)
add_filter('use_block_editor_for_post', '__return_false'); // Disables Gutenberg (3 of 3)

// wp-embed is disabled by default; comment out below to enable it.
remove_action('rest_api_init', 'wp_oembed_register_route'); //Remove the REST API endpoint. (1 of 6)
add_filter( 'embed_oembed_discover', '__return_false' ); // Turn off oEmbed auto discovery. (2 of 6)
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10); //Don't filter oEmbed results. (3 of 6)
remove_action('wp_head', 'wp_oembed_add_discovery_links'); //Remove oEmbed discovery links. (4 of 6)
remove_action('wp_head', 'wp_oembed_add_host_js'); //Remove oEmbed JavaScript from the front-end and back-end. (5 of 6);
remove_action('wp_head', 'rest_output_link_wp_head', 10); //Remove API from head. (6 of 6)

// emojis are disabled by default; comment out below to enable them.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // Disables emojis (1 of 3)
remove_action( 'wp_print_styles', 'print_emoji_styles' ); // Disables emojis (2 of 3)
add_filter( 'emoji_svg_url', '__return_false' ); // Disables emoji DNS prefetch (3 of 3)

// Modernizr: uncomment below to enable.
/*
wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // enables Modernizr (1 of 2)
wp_enqueue_script('modernizr'); // enables Modernizr (2 of 2)
*/

// Conditionizr: uncomment below to enable.
/*
wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // enables Conditionizr (1 of 2)
wp_enqueue_script('conditionizr'); // enables Conditionizr (2 of 2)
*/

?>
