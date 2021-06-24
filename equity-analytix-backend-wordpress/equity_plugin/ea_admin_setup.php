<?php

add_action( 'admin_menu', 'ea_admin_messenger_menu');
add_action('admin_enqueue_scripts', 'ea_plugin_styles_setting_up');
add_action( 'show_user_profile', 'ea_user_profile_fields' );
add_action( 'edit_user_profile', 'ea_user_profile_fields' );
add_action( 'personal_options_update', 'save_ea_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_ea_user_profile_fields' );

/**
 * @param $hook
 */
function ea_plugin_styles_setting_up($hook) {
    if ('toplevel_page_admin-messenger' == $hook) {
        wp_register_style(
            'ea_plugin_styles',
            plugin_dir_url(__FILE__).'assets/css/ea_style.css'
        );
        wp_enqueue_style('ea_plugin_styles');
    }

}

function ea_admin_messenger_menu() {
    add_menu_page(
        'Equity Analityx Messenger',
        'EA Messenger',
        'manage_options',
        'admin-messenger',
        'ea_admin_messenger_page',
        'dashicons-schedule',
        3
    );
}

function ea_admin_messenger_page() {
    require_once(__DIR__ . '/ea_messages_DB.php');

    if (isset($_SESSION['ea_redis_error'])) {
	    echo $_SESSION['ea_redis_error'];
	    return;
    }

    $messages_obj = new EA_Messages_DB();

    $messages = $messages_obj->listMessages();

    $page_num = isset( $_GET['page_number'] ) ? absint( $_GET['page_number'] ) : 1;
    $limit = 10;
    $offset = ( $page_num - 1 ) * $limit;
    $total = count($messages);
    $num_of_pages = ceil( $total / $limit );
    $lists = $messages_obj->listMessages($offset, $limit);
    $users = get_users();

    $page_links = paginate_links( array(
        'base' => add_query_arg( 'page_number', '%#%' ),
        'format' => '',
        'prev_text' => '«',
        'next_text' => '»',
        'total' => $num_of_pages,
        'current' => $page_num
    ) );
    ea_render($users, $lists, $page_links);
}

/**
 * @param $users
 * @param $messages
 * @param $page_links
 */
function ea_render($users, $messages, $page_links) {
    include_once ( 'views/ea_admin_view.php' );
}


/**
 * @param $user
 */
function ea_user_profile_fields( $user ) { ?>
        <?php if(in_array('administrator', (wp_get_current_user())->roles)):?>
    <h3><?php _e("Access to chat", "blank"); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="address"><?php _e("Access to chat"); ?></label></th>
            <td>
                <select name="access" id="access">
                    <option value="active"<?php echo 'active' === esc_attr(get_the_author_meta
                    ('access', $user->ID )) ?
                        'selected' : '' ;?>>Active</option>
                    <option value="blocked"<?php echo 'blocked' ===
                    esc_attr(get_the_author_meta
                    ('access', $user->ID )) ?
                        'selected' : '' ;?>>Block</option>
                </select><br />
                <span class="description"><?php _e("Block or activate the user."); ?></span>
            </td>
        </tr>
        </tr>
    </table>
    <?php endif; ?>
<?php }

/**
 * @param $user_id
 *
 * @return false|void
 */
function save_ea_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    update_user_meta( $user_id, 'access', $_POST['access'] );
}