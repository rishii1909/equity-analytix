<?php

add_action( 'widgets_init', 'ea_chat_area_register' );

function ea_chat_area_register() {
    register_sidebar(
      array(
          'id' => 'ea_chat_area',
          'name' => 'EA Chat Area',
          'description' => 'Chat bar area for EA Messenger',
          'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
          'after_widget' => '</div></div>',
          'before_title' => '<h4 class="widget-title subheading heading-size-3">',
          'after_title' => '</h4>',
      )
    );
}