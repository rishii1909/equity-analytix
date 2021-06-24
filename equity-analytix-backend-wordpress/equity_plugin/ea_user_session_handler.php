<?php

function saveTokenToRedis($user_login, $user) {
    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT );
    $exist = $redis->get('ea_'.md5($user->ID));

	if (!$exist) {
		try {
			$redis->setex(
				'ea_' . md5($user->ID),
				172800,
				json_encode([
					'id' => $user->ID,
					'role' => $user->roles,
					'user_login' => $user->user_login
				]));

		} catch (Exception $exception) {
			$_SESSION['ea_redis_error'] = 'Redis connection error: ' . $exception->getMessage();
			return;
		}
	}
}

add_action( 'wp_login', 'saveTokenToRedis', 1, 2);

function is_site_admin(){
	return in_array('administrator',  wp_get_current_user()->roles);
}
