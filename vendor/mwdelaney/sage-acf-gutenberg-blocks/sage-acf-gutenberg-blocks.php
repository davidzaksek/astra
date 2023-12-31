<?php

namespace App;

// Check whether WordPress and ACF are available; bail if not.
if (! function_exists('acf_register_block')) {
    return;
}
if (! function_exists('add_filter')) {
    return;
}
if (! function_exists('add_action')) {
    return;
}

// Add the default blocks location, 'views/blocks', via filter
add_filter('sage-acf-gutenberg-blocks-templates', function () {
    return array('views/blocks');
});

/**
 * Create blocks based on templates found in Sage's "views/blocks" directory
 */
add_action('acf/init', function () {

    // Global $sage_error so we can throw errors in the typical sage manner
    global $sage_error;

    // Get an array of directories containing blocks
    $directories = apply_filters('sage-acf-gutenberg-blocks-templates', []);

    // Check whether ACF exists before continuing
    foreach ($directories as $dir) {

        // Sanity check whether the directory we're iterating over exists first
        if (!file_exists(\locate_template($dir))) {
            return;
        }

        // Iterate over the directories provided and look for templates
        $template_directory = new \DirectoryIterator(\locate_template($dir));

        foreach ($template_directory as $template) {
            if (!$template->isDot() && !$template->isDir()) {

            // Strip the file extension to get the slug
                $slug = removeBladeExtension($template->getFilename());

                // Get header info from the found template file(s)
                $file_path = locate_template($dir."/${slug}.blade.php");
                $file_headers = get_file_data($file_path, [
                      'title' => 'Title',
                      'description' => 'Description',
                      'category' => 'Category',
                      'icon' => 'Icon',
                      'keywords' => 'Keywords',
                      'mode' => 'Mode',
                      'post_types' => 'PostTypes',
                    ]);

                if (empty($file_headers['title'])) {
                    $sage_error(__('This block needs a title: ' . $dir . '/' . $template->getFilename(), 'sage'), __('Block title missing', 'sage'));
                }

                if (empty($file_headers['category'])) {
                    $sage_error(__('This block needs a category: ' . $dir . '/' . $template->getFilename(), 'sage'), __('Block category missing', 'sage'));
                }

                // Set up block data for registration
                $data = [
                      'name' => $slug,
                      'title' => $file_headers['title'],
                      'description' => $file_headers['description'],
                      'category' => $file_headers['category'],
                      'icon' => $file_headers['icon'],
                      'keywords' => explode(' ', $file_headers['keywords']),
                      'mode' => $file_headers['mode'],
                      'render_callback'  => __NAMESPACE__.'\\sage_blocks_callback',
                    ];

                // If the PostTypes header is set in the template, restrict this block to thsoe types
                if (!empty($file_headers['post_types'])) {
                    $data['post_types'] = explode(' ', $file_headers['post_types']);
                }

                // Register the block with ACF
                \acf_register_block($data);
            }
        }
    }
});

/**
 * Callback to register blocks
 */
function sage_blocks_callback($block)
{

  // Set up the slug to be useful
    $slug = str_replace('acf/', '', $block['name']);

    // Set up the block data
    $block['slug'] = $slug;

    // Check whether we have both a class & align set
    // from WordPress Block settings
    if (!empty($block['className']) && !empty($block['align']))
    {
        $block['classes'] = implode(' ', [$block['slug'].'-block', $block['className'], ' align-'.$block['align']]);
    }
    // Check whether we have only a class set
    // from WordPress Block settings
    else if (!empty($block['className']))
    {
        $block['classes'] = implode(' ', [$block['slug'].'-block', $block['className']]);
    }
    // Check whether we have only align set
    // from WordPress Block settings
    else if (!empty($block['align']))
    {
        $block['classes'] = implode(' ', [$block['slug'].'-block', 'align-'.$block['align']]);
    }
    else 
    {
        $block['classes'] = implode(' ', [$block['slug'].'-block']);
    }

    // $block['classes'] = implode(' ', [$block['slug'], $block['className'], 'align'.$block['align']]);

    // Use Sage's template() function to echo the block and populate it with data
    echo \App\template("blocks/${slug}", ['block' => $block]);
}

/**
 * Function to strip the `.blade.php` from a blade filename
 */
function removeBladeExtension($filename)
{

  // Remove the unwanted extensions
    $return = substr($filename, 0, strrpos($filename, '.blade.php'));

    // Always return
    return $return;
}
