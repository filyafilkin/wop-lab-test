<?php
/* Styles and scripts*/
function add_theme_scripts() {
    wp_enqueue_style('reset', get_template_directory_uri() . '/assets/css/reset.css');
    wp_enqueue_style('style', get_template_directory_uri() . '/assets/scss/style.css');
    wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/main.js.js', array( 'jquery' ), 1.1, true );
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );



/* Create Custom Post Type */

function wop_create_post_type() {
    register_post_type('car',
        array(
            'labels' => array(
                'name' => __('Car'),
                'singular_name' => __('Car')
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'car'),
            'supports' => ['title', 'thumbnail', 'custom-fields'],
        )
    );

    register_taxonomy('brand', ['car'], [
        'labels' => [
            'name' => 'Brand',
            'singular_name' => 'Brand',
            'search_items' => 'Search Brand',
            'all_items' => 'All Brands',
            'view_item ' => 'View Brands',
            'parent_item' => 'Parent Brands',
            'parent_item_colon' => 'Parent Brands:',
            'edit_item' => 'Edit Brands',
            'update_item' => 'Update Brands',
            'add_new_item' => 'Add New Brand',
            'new_item_name' => 'New Brand',
            'menu_name' => 'Brands',
            'back_to_items' => '← Back to Brand',
        ],
        'public' => true,
        'hierarchical' => false,
        'rewrite' => true,
    ]);

    register_taxonomy('country', ['car'], [
        'labels' => [
            'name' => 'Country',
            'singular_name' => 'Country',
            'search_items' => 'Search Country',
            'all_items' => 'All Countries',
            'view_item ' => 'View Countries',
            'parent_item' => 'Parent Countries',
            'parent_item_colon' => 'Parent Countries:',
            'edit_item' => 'Edit Countries',
            'update_item' => 'Update Countries',
            'add_new_item' => 'Add New Country',
            'new_item_name' => 'New Country',
            'menu_name' => 'Countries',
            'back_to_items' => '← Back to Countries',
        ],
        'public' => true,
        'hierarchical' => false,
        'rewrite' => true,
    ]);
}
add_action('init', 'wop_create_post_type');
add_theme_support('post-thumbnails');
add_post_type_support( 'car', 'thumbnail' );


/* Create shortcode */

add_shortcode('cars','wop_shortcode' );

function wop_shortcode() {
    ob_start();
    ?>
        <div class="home__list car">
            <?php
            $args=array(
                'post_type' => 'car',
                'posts_per_page' => 10,
                'post_status' => 'publish',
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) :
                while ( $query->have_posts() ) : $query->the_post();
                    $postId = get_the_ID(); ?>
                    <a class="car__item" href="<?php echo get_permalink($postId)?>">
                            <div class="car__img">
                                <?php if(has_post_thumbnail()) {
                                    the_post_thumbnail();
                                 }  else { ?>
                                        <img src="/wp-content/uploads/logo-1.png" alt="WOP LAB TEST">
                              <?php  } ?>
                            </div>
                        <span class="car__title"> <?php the_title() ?></span>
                    </a>
                <?php endwhile;
                wp_reset_postdata();
            endif;
            ?>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}



/* Add title and custom logo */

function wop_add_theme_support() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo' );
}

add_action( 'after_setup_theme', 'wop_add_theme_support');


/* Enable SVG upload */

function wop_enable_svg_upload( $upload_mimes ) {
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';
    return $upload_mimes;
}

add_filter( 'upload_mimes', 'wop_enable_svg_upload', 10, 1 );



/* Add number to customizer */

function wop_customize_register( $wp_customize ) {

    $wp_customize->add_section( 'wop_company_section' , array(
        'title'      => __( 'Number', 'wop' ),
        'priority'   => 30,
    ));

    $wp_customize->add_setting( 'wop_company-name', array());
    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize, 'wop_company_control',
            array(
                'label'      => __( 'Number', 'wop' ),
                'section'    => 'wop_company_section',
                'settings'   => 'wop_company-name',
                'priority'   => 1
            )
        )
    );
}

add_action( 'customize_register', 'wop_customize_register' );



/* Change logo class */

function wop_change_logo_class( $html ) {
    $html = str_replace( 'custom-logo', 'header__logo', $html );
    $html = str_replace( 'custom-logo-link', 'header__link', $html );
    return $html;
}

add_filter( 'get_custom_logo', 'wop_change_logo_class' );



/* Add custom fields */

function wop_add_custom_fields() {
    add_meta_box(
            'extra_fields',
            'Car Information',
            'wop_setup_custom_fields',
            'car',
            'normal',
            'high'  );
}

add_action('add_meta_boxes', 'wop_add_custom_fields', 1);

function wop_setup_custom_fields( $post ){ ?>
    <style>
        .cf-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }
        .cf-label {
            margin-bottom: 5px;
        }
    </style>

    <div class="cf-item">
        <label class="cf-label" for="cf-color">Color</label>
        <input class="cf-input" type="color" id="cf-color" name="extra[color]" value="<?php echo get_post_meta($post->ID, 'color', 1); ?>">
    </div>

    <div class="cf-item">
        <label class="cf-label" for="cf-fuel">Fuel</label>
        <select id="cf-fuel" name="extra[fuel]">
            <?php $select = get_post_meta($post->ID, 'fuel', 1); ?>
            <option value="choose" disabled selected>Choose a value</option>
            <option value="gasoline" <?php selected( $select, 'gasoline' )?>>Gasoline</option>
            <option value="diesel" <?php selected( $select, 'diesel' )?>>Diesel</option>
            <option value="bio-diesel" <?php selected( $select, 'bio-diesel' )?>>Bio-Diesel</option>
            <option value="electric" <?php selected($select, 'electric' )?>>Electric</option>
            <option value="ethanol" <?php selected( $select, 'ethanol' )?>>Ethanol</option>
        </select>
    </div>

    <div class="cf-item">
        <label class="cf-label" for="cf-power">Power</label>
        <input class="cf-input" type="number" id="cf-power" name="extra[power]" value="<?php echo get_post_meta($post->ID, 'power', 1); ?>">
    </div>

    <div class="cf-item">
        <label class="cf-label" for="cf-price">Price</label>
        <input class="cf-input" type="number" id="cf-price" name="extra[price]" value="<?php echo get_post_meta($post->ID, 'price', 1); ?>">
    </div>

    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
<?php }



/* Save the custom field */

add_action( 'save_post', 'wop_update_custom_fields', 0 );

function wop_update_custom_fields( $post_id ){
    if (
        empty( $_POST['extra'] )
        || ! wp_verify_nonce( $_POST['extra_fields_nonce'], __FILE__ )
        || wp_is_post_autosave( $post_id )
        || wp_is_post_revision( $post_id )
    )
        return false;

    $_POST['extra'] = array_map( 'sanitize_text_field', $_POST['extra'] );
    foreach( $_POST['extra'] as $key => $value ){
        if( empty($value) ){
            delete_post_meta( $post_id, $key );
            continue;
        }

        update_post_meta( $post_id, $key, $value );
    }

    return $post_id;
}
