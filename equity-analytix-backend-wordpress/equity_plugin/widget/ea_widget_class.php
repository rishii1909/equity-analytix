<?php

add_action( 'widgets_init', function() {
	register_widget( 'EA_Chat_Widget' );
});

class EA_Chat_Widget extends WP_Widget {

    function __construct() {

        parent::__construct(
            'ea_chat',
            'EA Chat'
        );
    }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if ( 'blocked' === esc_attr(get_the_author_meta('access', get_current_user_id()))) {
            return;
        }

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo '<div class="textwidget">';

        require_once EA_PLUGIN_DIR . '/ea_messages_DB.php';
        $message_obj = new EA_Messages_DB();
        $ea_messages = $message_obj->listMessages();
	    if (wp_get_session_token()) {
		    $user_session_id = 'ea_' . md5(wp_get_current_user()->ID);
		    require_once 'ea_chat_view.php';
	    }

        echo '</div>';
        echo $args['after_widget'];
    }

    public function form( $instance ) {

    }

    public function update( $new_instance, $old_instance ) {

    }
}
