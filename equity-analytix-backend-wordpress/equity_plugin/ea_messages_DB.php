<?php

if ( ! class_exists('EA_Messages_DB')) {

    class EA_Messages_DB
    {
        public function listMessages($offset = null, $limit = null) {
            global $wpdb;
            $table_name = 'chat_news_news';
            if ($limit) {
                $messages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC LIMIT $offset,$limit");
            }else {
                $messages = $wpdb->get_results("SELECT * FROM $table_name");
            }

            return $messages;
        }
    }
}
