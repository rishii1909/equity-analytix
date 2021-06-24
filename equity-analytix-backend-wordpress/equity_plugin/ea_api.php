<?php

require_once 'ea_messages_DB.php';

function ea_api_get_messages() {
	$messages_obj = new EA_Messages_DB();
	$messages = $messages_obj->listMessages();

	return $messages;
}
