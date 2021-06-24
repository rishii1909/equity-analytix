# equity-analytix-frontend

### project setup
`yarn install`

### compiles and hot-reloads for development
- execute in an empty directory:  
`git clone git@git.sibers.com:sibers/equity-analytix-docker.git`  
 `cd equity-analytix-docker`
- run Docker
- run `./init.sh`
- go to localhost:8081
- log in as admin
- activate the Equity Analityx messenger plugin in the "Plugins" section
- go to "Appearance" section and drag "EA Chat" from the list of widgets to the "Blog Sidebar" 
- create a new user in the "Users" section 
- log in as user
- get user token by entering "window.user_session_id" into the console (note: the token for the admin and for the user is different)
- substitute the received token into appConfig.js in "equity-analytix-frontend" directory
- `yarn serve`



http://localhost:8081 - wordpress  
http://localhost:8082 - api  
http://localhost:8083 - frontend   

### backend setup
- go to "equity-analytix-docker" container
- `docker-compose exec equity-php-fpm bash` 
- in bash: `cd equity-analytix-backend-messenger`
- `php bin/console doctrine:migrations:migrate` 
- `php bin/console doctrine:fixtures:load`  
 
(details: equity-analytix-docker/README.md)
	
	
- change websocket url in frontend code for admin (ChatAdmin.vue) and user (ChatUser.vue): `ws://localhost:8080/chat` 
- in appConfig.js set `urlBaseToSounds = 'http://localhost:8084'`  
- run websocket server:
	- `docker-compose exec equity-php-fpm bash`
	- `cd equity-analytix-backend-messenger`
	- `php bin/console run:websocket-server`
- after each change in the code, restart the websocket server


### compiles and minifies for production
`yarn build`

### problem solving
Problem 1  
	- johnpbloch/wordpress-core-installer is locked to version 1.0.2 and an update of this package was not requested.
	- johnpbloch/wordpress-core-installer 1.0.2 requires composer-plugin-api ^1.0 -> found composer-plugin-api[2.0.0] but it does not match the constraint.  
	  Problem 2  
	- cweagans/composer-patches is locked to version 1.6.7 and an update of this package was not requested.
	- cweagans/composer-patches 1.6.7 requires composer-plugin-api ^1.0 -> found composer-plugin-api[2.0.0] but it does not match the constraint.  
	  Problem 3  
	- johnpbloch/wordpress-core-installer 1.0.2 requires composer-plugin-api ^1.0 -> found composer-plugin-api[2.0.0] but it does not match the constraint.
	- johnpbloch/wordpress 4.9.4 requires johnpbloch/wordpress-core-installer ^1.0 -> satisfiable by johnpbloch/wordpress-core-installer[1.0.2].
	- johnpbloch/wordpress is locked to version 4.9.4 and an update of this package was not requested.
	
SOLUTION:   
`docker-compose exec equity-php-fpm bash`  
`composer self-update 1.10.21`  
`init.sh`

