<?php

if (!is_admin()) {
    wp_enqueue_style('eq_scrollbar_css', get_stylesheet_directory_uri()."/assets/css/jquery.scrollbar.css");
    wp_enqueue_style('eq_main', get_stylesheet_directory_uri()."/assets/css/main.css");
    wp_enqueue_style('eq_normalize', get_stylesheet_directory_uri()."/assets/css/normalize.css");
    wp_enqueue_style('eq_slick', get_stylesheet_directory_uri()."/assets/css/slick.css");
    wp_enqueue_script('jquery');
    wp_enqueue_script('eq_libs_min_js', get_stylesheet_directory_uri()."/assets/js/jquery.min.js", null, null, true);
    wp_enqueue_script('eq_main', get_stylesheet_directory_uri()."/assets/js/main.js", null, null, true);
    wp_enqueue_script('eq_scrollbar', get_stylesheet_directory_uri()."/assets/js/jquery.scrollbar.min.js", null, null, true);
    wp_enqueue_script('eq_slick', get_stylesheet_directory_uri()."/assets/js/slick.min.js", null, null, true);

    eq_login_params();
}

add_action('wp_ajax_eq_ajax_login', 'eq_ajax_login');
add_action('wp_ajax_nopriv_eq_ajax_login', 'eq_ajax_login');

function eq_login_params()
{
    wp_register_script('eq_login', get_stylesheet_directory_uri()."/assets/js/ajax/login.js", ['jquery'],null, true);

    wp_localize_script('eq_login', 'obj_login', [
        'ajaxurl' => admin_url('admin-ajax.php'),
    ]);

    wp_enqueue_script('eq_login');
}

function eq_ajax_login(){
    $data = $_POST['fields'];
    $fields = [];

    $fields['user_login'] = $data['user_login'];
    $fields['user_password'] = $data['user_password'];
    $fields['remember'] = $data['remember'];

    $result = wp_signon($fields, false);

    if ($result instanceof WP_User){
      echo json_encode([
          'status' => 'success'
      ]);
        wp_die();
    }

    if ($result instanceof WP_Error){
        echo json_encode([
            'status' => 'error',
            'error' => $result->get_error_code(),
            'message' => $result->get_error_message(),
        ]);
        wp_die();
    }


}
