## EA messenger plugin

Equity Analytix messenger plugin is Wordpress plugin created for handling special messenger functionality. 
After installation, it enables EA Messenger admin dashboard
and EA messenger.

**Requirements**
- Redis: >= 6.0.6

**Installation process**

First run:
```bash
composer install
```

Then, define redis server host and port number in config file. 
Open web/wp-config.php file and add following:
```bash
define('REDIS_HOST', 'Here paste Redis host');
define('REDIS_PORT', Here paste Redis port number);
```

Then, in admin dashboard activate Equity Analytix Messenger plugin.
Plugin is ready to use.


**Authorize.net Subscription**

It is assumed, that users subscribe to EA messenger service.
EA messenger plugin operates with Authorize.net system to handle subscription payments.
In order to pass transactions define Authorize.net merchant 'API LOGIN ID' and merchant 'TRANSACTION KEY' taken from Authorize.net in config file.
Add following to web/wp-config.php file.
```bash
define('MERCHANT_LOGIN_ID', 'Here goes API LOGIN ID');
define('MERCHANT_TRANSACTION_KEY', 'Here goes TRANSACTION KEY');
```