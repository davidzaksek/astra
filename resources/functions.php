<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.1', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7.1 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'assets' => require dirname(__DIR__).'/config/assets.php',
            'theme' => require dirname(__DIR__).'/config/theme.php',
            'view' => require dirname(__DIR__).'/config/view.php',
        ]);
    }, true);






// *** CUSTOM CODE ***


// NOT USED
function get_main_url() {

    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . "/";
}
function get_sub_categories($parent_category_id) {
    $subcategories = array();
    $categories = get_categories();

    // Get all child categories
    foreach($categories as $subcategory) {

        // Check that child_cat is child of main category
        if (cat_is_ancestor_of($parent_category_id, $subcategory)) {

            $subcategories[] = array(
                'ID' => $subcategory->cat_ID,
                'name' => $subcategory->cat_name,
                'slug' => $subcategory->slug
            );
        }
    }

    return $subcategories;
}
function get_latest_posts($type, $amount, $post_type = 'post', $ignore_post_ID = false, $random = false) {

    $orderby = 'date';
    if ($random) $orderby = 'rand';

    $query = new WP_Query( array(
        'category_name'   => $type,
        'posts_per_page'  => $amount,
        'post_status'     => 'publish',
        'post_type'       => $post_type,
        'orderby'         => $orderby,
        'order'           => 'DESC',
        'post__not_in'    => array($ignore_post_ID)
    ));

    $result = array();

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
      
            $title = wp_specialchars_decode(get_the_title());
            $date = get_the_date();
            $excerpt = wp_specialchars_decode(get_the_excerpt());
            $img_url = get_the_post_thumbnail_url();
            $post_url = get_the_permalink();

            $sub_category_names = array();
            $sub_category_slugs = array();
            $categories = get_the_category();
            // Get post child categories
            foreach($categories as $childcat) {
                // Check that child_cat is child of main category
                if (cat_is_ancestor_of(get_category_by_slug($type), $childcat)) {
                    $sub_category_names[] = $childcat->cat_name;
                    $sub_category_slugs[] = $childcat->slug;
                }
            }

            // ***IMP*** CURRENTLY IGNORED
            $event_date = null;
            if ($type == 'events') {
                // Get event date meta-data from document settings - option modules
                $event_date = get_post_meta(get_the_ID(), 'event_date_time', true);
            }

            // Store data in array
            $result[] = array(
                'post_id' => get_the_ID(),
                'title' => $title,
                'published_date' => $date,
                'event_date' => $date,
                'excerpt' => $excerpt,
                'img_url' => $img_url,
                'sub_categories' => $sub_category_names,
                'sub_categories_slug' => $sub_category_slugs,
                'post_url' => $post_url,
            );

        endwhile;

        // Reset post data
        wp_reset_postdata();
    
    else :
    
        return false;

    endif;

    return $result;
}

// TOPIC FUNCTIONS

// Used in:
// 1) header.blade.php
// 2) themes.blade.php
function get_all_topics() {

    $args = array(
        'posts_per_page'    => -1,
        'category_name'     => 'topic',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'post_type'         => 'page',
        'post_status'       => 'publish'
    );
    
    // Query posts
    $topic_pages = get_posts($args);
    $topics = array();
    // $chapters_count = 0;

    if ( count($topic_pages) ):
        
        // Go through each page
        foreach ($topic_pages as $topic_page):

            $topic = get_topic_data($topic_page);

            // Store each topic data in topics array
            $topics[] = $topic;

        endforeach;
        
    endif;

    return $topics;
}
function get_topic_data($topic) {

    // print_r($topic);
    $topic_id = $topic->ID;
    // Topic Link
    $topic_url = get_page_link($topic_id);
    // Topic Title
    $topic_title = wp_specialchars_decode($topic->post_title);
    // Topic Excerpt
    $topic_desc = wp_specialchars_decode($topic->post_excerpt);

    // Get all chapters
    list($chapters, $videos_count, $watched_amount) = get_all_chapters($topic_id);

    // Get all videos from all chapters length in seconds combined
    $total_seconds = 0;
    foreach ($chapters as $chapter)
    {
        // Add total seconds from this chapter
        $total_seconds += $chapter->total_seconds;
    }

    return (object) [
        'id' => $topic_id,
        'url' => $topic_url,
        'title' => $topic_title,
        'desc' => $topic_desc,
        'chapters' => $chapters,
        'videos_count' => $videos_count,
        'total_seconds' => $total_seconds,
        'total_watched' => $watched_amount
    ];
}

// CHAPTER FUNCTIONS

function get_all_chapters($parent_page_id) {

    // Get all chapters
    $child_args = array(
        'post_parent' => $parent_page_id,
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'post_type'   => 'page',
        'post_status' => 'publish'
    );

    // Get all page children pages of parent topic page
    $chapter_pages = get_children($child_args);
    $chapters = array();
    $videos_count = 0;
    $watched_amount = 0;

    // Check if any chapter pages exist
    if (count($chapter_pages) > 0):

        // Go through each page
        foreach ($chapter_pages as $chapter_page):

            $chapter = get_chapter_data($chapter_page);

            // Store each chapter data in chapters array
            $chapters[] = $chapter;

            // Count all videos
            $videos_count += count($chapter->videos);

            // Count all watched videos
            $watched_amount += $chapter->watched_amount;

        endforeach;

    endif;

    return array($chapters, $videos_count, $watched_amount);
}
function get_chapter_data($chapter) {

    // print_r($chapter);
    $chapter_id = $chapter->ID;
    // Chapter Link
    $chapter_url = get_page_link($chapter_id);
    // Chapter Title
    $chapter_title = wp_specialchars_decode($chapter->post_title);
    // Chapter Excerpt
    $chapter_desc = wp_specialchars_decode($chapter->post_excerpt);

    // Get all videos
    $videos = get_all_videos($chapter_id);

    // Get all videos length in seconds combined
    // and get watched amount
    $total_seconds = 0;
    $watched_amount = 0;
    foreach ($videos as $video)
    {
        // Add seconds
        $total_seconds += $video->length->sec;
        // Convert min to sec and add to $total_seconds
        $total_seconds += ($video->length->min * 60);

        if ($video->watched) $watched_amount++;
    }

    return (object) [
        'id' => $chapter_id,
        'url' => $chapter_url,
        'title' => $chapter_title,
        'desc' => $chapter_desc,
        'videos' => $videos,
        'total_seconds' => $total_seconds,
        'watched_amount' => $watched_amount
    ];
}

// VIDEO FUNTIONS

function get_all_videos($parent_page_id) {

    // Get all videos
    $child_args = array(
        'post_parent' => $parent_page_id,
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'post_type'   => 'page',
        'post_status' => 'publish'
    );

    // Get all page children pages of parent chapter page
    $video_pages = get_children($child_args);
    $videos = array();

    // Check if any video pages exist
    if (count($video_pages) > 0):

        // Go through each page
        foreach ($video_pages as $video_page):

            // Store each video data in videos array
            $videos[] = get_video_data($video_page);

        endforeach;

    endif;

    return $videos;
}
function get_video_data($video) {

    // print_r($video);
    $video_id = $video->ID;
    // Video Link
    $video_url = get_page_link($video_id);
    // Video Title
    $video_title = wp_specialchars_decode($video->post_title);
    
    // Get Video Custom Data

    // YouTube Code
    $video_code = get_post_meta($video_id, 'settings_video_code', true);
    // Video Image URL (YouTube)
    $video_img_url = get_post_meta($video_id, 'settings_video_image_url', true);
    // Video Length (Minutes & Seconds)
    $vid_len_min = get_post_meta($video_id, 'settings_video_length_minutes', true);
    $vid_len_sec = get_post_meta($video_id, 'settings_video_length_seconds', true);

    // Check Video Watched State in Cookie
    // $wacthed_vids_obj = json_decode(stripslashes($_COOKIE["ASTRAVIDS"]));
    // $in_progress = isset($wacthed_vids_obj) ? property_exists($wacthed_vids_obj, $video_code) : false;
    // $watched = ($in_progress && $wacthed_vids_obj->{$video_code} == 1) ? true : false;

    return (object) [
        'id' => $video_id,
        'url' => $video_url,
        'title' => $video_title,
        'code' => $video_code,
        'img_url' => $video_img_url,
        'watched' => false,             // Disable feature
        'in_progress' => false,         // Disable feature
        'length' => (object) [
            'min' => $vid_len_min,
            'sec' => $vid_len_sec
        ]
    ];
}


// GENERAL FUNCTIONS

function calculateTotalTime($seconds, $minutes = null) {

    // Convert $minutes to seconds & add them to $seconds
    $sec = !empty($minutes) ? $seconds + ($minutes * 60) : $seconds;
    
    if (!empty($sec))
    {
        // Convert sec to hours, minutes, & remaining seconds
        $hours = floor($sec / 3600);
        $minutes = floor(($sec / 60) % 60);
        $seconds = $sec % 60;

        return (object) [
            'hrs' => $hours,
            'min' => $minutes,
            'sec' => $seconds
        ];
    }
    else
    {
        return false;
    }
}

function totalTimeToText($time, $include_seconds = false) {

    // Converts calculateTotalTime object to string text
    $hours = !empty($time->hrs) ? $time->hrs . 'h' : '';
    $minutes = !empty($time->min) ? $time->min . 'min' : '';
    $seconds = ($include_seconds) ? $time->sec . 's' : '';
    
    return $hours . ' ' . $minutes . ' ' . $seconds;
}

function strip_string($string) {
    //Lower case everything
    $string = wp_specialchars_decode(strtolower($string));
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

function getVideoText($video_count) {

    // Get global text content
    if ( have_rows( 'text', 'option' ) ):
        while ( have_rows( 'text', 'option' ) ) : the_row();

            $text_video = get_sub_field( 'video' );                 // 1 video
            $text_videos = get_sub_field( 'videos' );               // 2 videos
            $text_videos_3_4 = get_sub_field( 'videos_3_4' );       // 3&4 videos
            $text_videos_plus = get_sub_field( 'videos_plus' );     // 5+ videos
        
        endwhile;
    endif;

    if ($video_count == 1) return $text_video;
    else if ($video_count == 2) return $text_videos;
    else if ($video_count == 3 || $video_count == 4) return $text_videos_3_4;
    else return $text_videos_plus;
}



// WORDPRESS SPECIFIC FUNCTIONS/OVERRIDES

// Enable SVG Support in Media
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// Add Options Support - ACF - GLOBALS
// See: https://www.advancedcustomfields.com/resources/options-page/
if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
		'page_title' 	=> 'Globals',
		'menu_slug' 	=> 'global-settings'
    ));
    
    acf_add_options_sub_page(array(
		'page_title' 	=> 'General Settings',
		'menu_title'	=> 'General',
		'parent_slug'	=> 'global-settings',
    ));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'global-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'global-settings',
    ));
}

// Numeric Custom Pagination
function get_custom_posts_nav($max_num_pages = false) {
    
    if ($max_num_pages) {
        echo paginate_links( array(
            'mid_size'        => 4,
            'total'           => $max_num_pages,
            'prev_text'       => __('<'),
            'next_text'       => __('>')
        ) );
    } else {
        echo paginate_links( array(
            'mid_size'        => 4,
            'prev_text'       => __('<'),
            'next_text'       => __('>')
        ) );
    }
}

// Add scripts to footer
function add_this_script_footer() {

    echo('<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>');
    echo('<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>');
}
add_action('wp_footer', 'add_this_script_footer');

// Show Categories & Tags in page document settings
function tags_categories_page_support() {  

    // Tag support
    register_taxonomy_for_object_type('post_tag', 'page'); 
    // Category support
    register_taxonomy_for_object_type('category', 'page');  
}
add_action( 'init', 'tags_categories_page_support' );

// Ensure all Categories & Tags are included in queries
function tags_categories_page_support_query($wp_query) {

    // Tag query support
    if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
    // Category query support
	if ($wp_query->get('category')) $wp_query->set('post_type', 'any');
}
add_action( 'pre_get_posts', 'tags_categories_page_support_query' );

// Show excerpt option module in page document settings
add_post_type_support( 'page', 'excerpt' );



// TEMP

// Remove Admin Heading
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
// add_action('get_header', 'remove_admin_login_header');
// add_filter('show_admin_bar', '__return_false'); // Remove Completely

?>