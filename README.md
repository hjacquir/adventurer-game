# Game : the adventurer

## Description

An adventurer was venturing into a dangerous world, 
making its way through the dark woods.

## Use case

* It is about modeling the movements of an adventurer on a map.
* The map is modeled using characters in a text file in UTF-8 format.

Example 
```
###    ######    ###
###      ##      ###
##     ##  ##     ##
#      ##  ##      #
##                ##

Legends :
# impenetrable wood
[] (space character): box where the adventurer can move
```

Requirements :

* The adventurer cannot go beyond the edges of the map.
* The adventurer cannot go to spaces occupied by impenetrable woods.

## Usage

### Load file map into the database

In this first step we record in database only the possible route from the map

#### Extract Latitude Line from map file

* Enter in your shell (`make shell`) and launch the command : `bin/console app:extract-latitude [MAP_FILE_NAME.txt]` with the map file path
to verbose all log message add the options : `-vvv` 
like `bin/console app:extract-latitude map.txt -vvv`
(The file is parsed line by line and each line is sent to 
RMQ queue : `latitude_line`)
* Connect to RMQ : http://localhost:15672/ with guest/guest

#### Transform Latitude Line to Gps Coordinates

* Enter in your shell (`make shell`) and consume message 
sent before : `bin/console messenger:consume latitude_line -vvv`
* Each latitude line is mapped to a gps coordinate with latitude and longitude. 
(The gps coordinate is sent to RMQ queue : `gps_coordinates`)

#### Load Gps Coordinates into Database

* Enter in your shell (`make shell`) and consume message sent before : 
`bin/console messenger:consume gps_coordinates -vvv`
(Each gps coordinates are loaded into the pgsql database)

* To connect on the database with adminer :
  * url : http://localhost:8080
  * credentials : postgres - user - password - adventurer

### Playing game

* Enter in your shell (`make shell`) and launch the command : 
`bin/console app:play-game [INITIAL_COORDINATES] [MOVING_SEQUENCE] -vvv`

You have three behaviours :
* The initial coordinates do not exist
* The initial coordinates exist but the adventurer can not move
* The initial coordinates exist and the adventurer can move

Example of command : `bin/console app:play-game 0,3 SSEW -vvv`

## Stack

* PHP 7.4 with Xdebug
* Symfony 4.4 with Profiler Pack (symfony/profiler-pack)
* RabbitMQ 3.6
* Adminer 4.7
* PostgreSQL 13
* Redis

### Connect to RMQ

* url : http://localhost:15672
* id/mdp : guest/guest

### Connect to Adminer

* url : http://localhost:8080/
* server : postgres
* username : user
* password: password
* database : adventurer

### Connect to redis container

`docker exec -it adventurer-redis sh` and `redis-cli`

## Use development environment :computer:

You only need `make`, `docker` and `docker-compose` installed to start the development environment.

### Start the development environment

The following command will start the development environment.
You can access to the application at http://127.0.0.1:8000/ :

```bash
make start
```

### Access to a shell in the PHP container

```bash
make shell
```

### Tests tools

You can run PHPUnit with the following command:
```bash
# Run the unit test suite
make unit-test

# Run the functionnal test suite
make func-test
```

### Stop the development environment

You can stop the development environment running this command:
```bash
make stop
```

### Clean the development environment

You can clean the development environment (docker images, vendor, ...) running this command:
```bash
make clean
```

### Makefile targets

You can get available targets by running:
```bash
make
```

```bash
build                          Build the docker stack
pull                           Pulling docker images
shell                          Enter in the PHP container
start                          Start the docker stack
stop                           Stop the docker stack
clean                          Clean the docker stack
vendor                         Install composer dependencies
unit-test                      Run PhpUnit unit testsuite
func-test                      Run PhpUnit functionnal testsuite
```

### Code style

The project follows the PSR12 standard.
