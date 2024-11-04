# Challenge

Me enfoqué en diseñar 3 capas: Api, Data y Tennis. Cada una compuesta por sus clases y sus test unitarios.
La instalación de la mayoría de los componentes se realizó con composer.

# DB
Usé docker para crear la base de datos en mysql con el siguiente comando

`docker run --hostname=7741dbde4b22 --env=MYSQL_ROOT_PASSWORD=root --env=MYSQL_DATABASE=mydb --env=PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin --env=GOSU_VERSION=1.17 --env=MYSQL_MAJOR=innovation --env=MYSQL_VERSION=9.1.0-1.el9 --env=MYSQL_SHELL_VERSION=9.1.0-1.el9 --volume=/var/lib/mysql --network=bridge --workdir=/ -p 3306:3306 --restart=no --runtime=runc -d mysql:latest`

en `Data/db/db.sql` se encuentran los scripts con los que creé las tablas.

Para el acceso se utiliza LessQL

# API
La Api se basa en el microframework limonade.
Para interactuar con la api podemos usar la collection para postman guardada en `postman/Geopagos.postman_collection.json`.
Algunos llevan ids que se corresponden con los de la db.
La request principal se llama FAST TOURNAMENT, recibe un json con players, un name y un gender.
Luego para buscar dentro de los torneos podemos usar SEARCH
Ademas contamos con ADD y DELETE para torneos y players y REGISTER PLAYER IN TOURNAMENT para registrar un jugador por id o por sus propiedades en el torneo.

# Unit Testing
Se aplicó test unitario para las capas de Data y de Tennis

```
vendor/bin/phpunit --testdox --display-deprecations Data/test/TennisRegistrationTest.php
vendor/bin/phpunit --testdox --display-deprecations Data/test/TennisPlayerTest.php   
vendor/bin/phpunit --testdox --display-deprecations Data/test/TennisTournamentTest.php     

vendor/bin/phpunit --testdox --display-deprecations Tennis/test/TennisMatchTest.php  
vendor/bin/phpunit --testdox --display-deprecations Tennis/test/TennisTournamentTest.php  
```

Me hubiese gustado completar el testing unitario de la capa de Api, pero solo alcancé a incluir un ejemplo que valida el status sobre el torneo inicializado a partir de la lista (json)

`vendor/bin/phpunit --testdox --display-deprecations Api/test/TennisTournamentTest.php`


Muchas Gracias!

