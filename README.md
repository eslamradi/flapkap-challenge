<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Flapkap Backend Challenge

This is the submission repo of Flapkap Backend Challenge built using laravel php framework version 9

## Dependencies

This project is set up to be built with docker instead of installing any software you don't need on your machine, you should have [`docker`](https://docs.docker.com/get-docker/) and [`docker-compose`](https://docs.docker.com/compose/) installed on your system.

## Run the application

Kindly follow the next steps in order to be able to run the application: 

> by default ports 8000, 4306 are not used by any service on your machine, if you run any services on these ports kindly stop them

1. clone and cd to the root path of this repository.

2. if you're using a windows based system copy the `.env.example` into `.env` manually or using the command :

    ```copy .env.example .env```

    OR if you're using a unix/linux based system: 
    
    ```cp .env.example .env```
3. build and run the docker containers defined at the `docker-compose.yml` file:
    
    ```docker-compose build && docker-compose up -d```

4. generate application key 

    ```docker-compose exec app php artisan key:generate```

    Now the application is available for use on http://localhost:8000

## stack used

- PHP ^8.1
- MySQL ^8.0
- Nginx default
- Composer ^2.0