<?php
/**
 * Plugin Name: Boldify
 * Plugin URI: https://www.boldifyplugin.com
 * Description: Easily add bold, italic, highlighted, and underlined text styles for maximum impact!
 * Version: 1.0.0
 * Author: Vinci Group
 * Author URI: https://www.boldifyplugin.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Requires at least: 4.9
 * Requires PHP: 5.2.4
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function boldify_settings_init() {
    register_setting('boldify', 'boldify_options');

    add_settings_section(
        'boldify_section_info',
        __('Boldify Settings.', 'boldify'), 'boldify_section_info_callback',
        'boldify'
    );



    add_settings_field(
        'boldify_field_words',
        __('Words', 'boldify'),
        'boldify_field_words_cb',
        'boldify',
        'boldify_section_info',
        array(
            'label_for'         => 'boldify_field_words',
            'class'             => 'boldify_row',
        )
    );
}

add_action('admin_init', 'boldify_settings_init');

function boldify_section_info_callback($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Enter the word to make it bold.', 'boldify'); ?></p>
    <?php
}

function boldify_field_words_cb($args) {
    $options = get_option('boldify_options');
    $index = isset($options['boldify_field_words']) ? $options['boldify_field_words'] : array(
        array(
            'word'=> '',
            'grassetto' => true,
            'corsivo' => false,
            'sottolineato' => false,
            'evidenziato' => false,
            'link' => '',
        ),
    );
    // Rimuovi gli elementi in cui "word" è vuoto
    $index = array_filter($index, function($item) {
        return !empty($item['word']);
    });
    // Reindex array
    $index = array_values($index);
    // Aggiungi un nuovo elemento vuoto come ultimo elemento di "boldify_field_words"
    if ($options['boldify_field_license_key'] === '1x03849og94-938h480h'){
        $index[] = array(
            'word'=> '',
            'grassetto' => true,
            'corsivo' => false,
            'sottolineato' => false,
            'evidenziato' => false,
            'link' => '',
        );
    }else{
        $index = array_slice($index, 0, 1);
    }


    ?>
    <?php
    if(empty($index)){
        $index[] = array(
            'word'=> '',
            'grassetto' => true,
            'corsivo' => false,
            'sottolineato' => false,
            'evidenziato' => false,
            'link' => '',
        );
    }
    foreach ($index as $i => $item) : ?>
        <h4>Word <?php echo (esc_attr($i) + 1); ?>:</h4>
        <input type="text" name="boldify_options[boldify_field_words][<?php echo esc_attr($i); ?>][word]" value="<?php echo esc_attr($item['word']); ?>"/><br>
        <label for="boldify_options[boldify_field_words][<?php echo esc_attr($i); ?>][grassetto]">Bold:</label>
        <input type="checkbox" name="boldify_options[boldify_field_words][<?php echo esc_attr($i); ?>][grassetto]" value="1" <?php checked(1, $item['grassetto'], true); ?> /><br>

    <?php endforeach; ?>
    <?php
}


function boldify_options_page() {
    add_menu_page(
        __('Boldify', 'boldify'), // Aggiungi la funzione di traduzione __() qui
        __('Boldify Options', 'boldify'), // Aggiungi la funzione di traduzione __() qui
        'manage_options',
        'boldify',
        'boldify_options_page_html'
    );
}
add_action('admin_menu', 'boldify_options_page');

function boldify_options_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('boldify_messages', 'boldify_message', __('Settings Saved', 'boldify'), 'updated');
    }

    settings_errors('boldify_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('boldify');
            do_settings_sections('boldify');
            submit_button('Salva');
            ?>
        </form>
    </div>
    <?php
}
function boldify_add_custom_class_to_content($content) {
    $custom_class = 'boldify-content';
    $content_with_class = '<div class="' . $custom_class . '">' . $content . '</div>';
    return $content_with_class;
}
add_filter('the_content', 'boldify_add_custom_class_to_content');

function boldify_enqueue_scripts() {
    wp_enqueue_script('boldify-frontend', plugin_dir_url(__FILE__) . 'boldify-frontend.js', array(), '1.0.0', true);

    $boldify_options = get_option('boldify_options');
    $boldify_field_words = isset($boldify_options['boldify_field_words']) ? $boldify_options['boldify_field_words'] : array();

    wp_localize_script('boldify-frontend', 'boldifySettings', $boldify_field_words);
}
add_action('wp_enqueue_scripts', 'boldify_enqueue_scripts');
