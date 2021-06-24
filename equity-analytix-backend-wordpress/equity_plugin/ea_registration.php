<?php

require_once 'ea_subscription.php';

add_action('register_form', 'ea_register_form');
add_filter('registration_errors', 'ea_user_registration_error_method', 10, 1);

add_action('login_head', 'ea_login_image');
add_filter('login_form', 'ea_login_add_input');
add_filter('login_message', 'ea_login_form_top_message');

function ea_login_add_input()
{
    echo '<input class="input" type="hidden" name="" value="">';
}

function ea_login_form_top_message()
{
    echo '<a href="index.php?route_name=equity_plugin_index">Back to Home page</a>';
}

function ea_login_image()
{
    echo "
	<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js\"></script>
	<script>
		function jq_credit_card_field_custom() {
		    let cc_sustom_element = $('#custCreditField');
		    console.log('from jq some stuff');
		    let foo = $(cc_sustom_element).val().split(\" \").join(\"\"); // remove hyphens
		    if (foo.length > 0) {
		        foo = foo.match(new RegExp('.{1,4}', 'g')).join(\" \");
		    }
		    $(cc_sustom_element).val(foo);
		}
	</script>
	<style>
	  body.login #login h1 a {
	    background: url('https://picsum.photos/seed/picsum/200/300') no-repeat scroll center top transparent;
	    height: 213px;
	    width: 313px;
	  }
	</style>
  ";
}

function ea_register_form()
{
    global $customize_error_validation;
    $customize_error_validation = new WP_Error;

    ea_custom_registration_form_function();
}

function ea_custom_registration_form_function()
{
    global $first_name, $last_name, $password;

    $first_name = sanitize_text_field(@$_POST['first_name']);
    $last_name  = sanitize_text_field(@$_POST['last_name']);
    $password   = hash("sha256", $_POST['password']);

    ea_custom_registration_form(
        $first_name,
        $last_name,
        $password
    );
}

function ea_custom_registration_form($first_name, $last_name, $password)
{
    echo '
    <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
    
    <label for="first_name">First Name<br />
    <input class="input" type="text" name="first_name" value="'.(isset($_POST['first_name']) ? $first_name : null).'">
    
    <label for="last_name">Last Name<br />
    <input class="input" type="text" name="last_name" value="'.(isset($_POST['last_name']) ? $last_name : null).'">
    
    <label for="password">Password<br />
    <input class="input" type="password" name="password" value="'.(isset($_POST['password']) ? $password : null).'">
    
    </form>
    ';
}

function ea_user_registration_error_method($errors)
{

    if (empty($_POST['first_name']) || (!empty($_POST['first_name']) && trim(
                $_POST['first_name']
            ) == '')) {
        $errors->add(
            'first_name_error',
            __('<strong>ERROR</strong>: Enter Your First Name.')
        );
    }

    if (empty($_POST['last_name']) || (!empty($_POST['last_name']) && trim(
                $_POST['last_name']
            ) == '')) {
        $errors->add(
            'last_name_error',
            __('<strong>ERROR</strong>: Enter Your Last Name.')
        );
    }

    if (empty($_POST['password']) || (!empty($_POST['password']) && trim(
                $_POST['password']
            ) == '')) {
        $errors->add(
            'password_error',
            __(
                '<strong>ERROR</strong>: Enter Your Password.'
            )
        );
    }

    return $errors;
}


/**
 * @param $user_login
 * @param $user
 *
 * @throws Exception
 */
function dublicate_user_data_for_chat($user_login, $user)
{
    $url       = EA_CHAT_API . '/api/user/create';
    $authToken = 'ea_'.md5($user->ID);

    $userData             = [];
    $userData['userId']   = $user->ID;
    $userData['role']     = $user->roles[0];
    $userData['userName'] = $user_login;

    $headers = [
        'Authorization' =>  $authToken,
        'Content-Type'  => 'application/json',
    ];

    $client  = new WP_Http;
    $request = $client->request(
        $url,
        [
            'method'  => 'POST',
            'headers' => $headers,
            'body'    =>json_encode($userData),
        ]
    );

    if (0 < count($request->errors)) {
        echo "User not create";
    }
}
add_action('wp_login', 'dublicate_user_data_for_chat', 999, 2);

