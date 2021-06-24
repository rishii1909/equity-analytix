<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=utf-8>
        <meta http-equiv=X-UA-Compatible content="IE=edge">
        <meta name=viewport content="width=device-width,initial-scale=1">
        <link rel=icon href=<?php echo EA_CHAT_DOMAIN ?>/favicon.ico>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel=stylesheet>
        <title>equity-analytix-frontend</title>
        <link href=<?php echo EA_CHAT_DOMAIN ?>/css/app.css rel=preload as=style>
        <link href=<?php echo EA_CHAT_DOMAIN ?>/css/chunk-vendors.css rel=preload as=style>
        <link href=<?php echo EA_CHAT_DOMAIN ?>/js/app.js rel=preload as=script>
        <link href=<?php echo EA_CHAT_DOMAIN ?>/js/chunk-vendors.js rel=preload as=script>
        <link href=<?php echo EA_CHAT_DOMAIN ?>/css/chunk-vendors.css rel=stylesheet>
        <link href=<?php echo EA_CHAT_DOMAIN ?>/css/app.css rel=stylesheet>
        <style>
            #app {
                position: fixed;
                bottom: 2%;
                width: 100%;
                z-index: 9999;
            }
        </style>
    </head>
    <body>
        <noscript></noscript>
        <div id=app></div>
        <script>var user_session_id = '<?php echo $user_session_id;?>';</script>
        <script src=<?php echo EA_CHAT_DOMAIN ?>/js/chunk-vendors.js></script>
        <script src=<?php echo EA_CHAT_DOMAIN ?>/js/app.js></script>
    </body>
</html>
