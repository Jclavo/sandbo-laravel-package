#  Sandbox for Laravel Package

This is a container to run a Sandbox for Laravel Package

# Steps to run it

- clone the project.
```bash
  git clone https://github.com/Jclavo/sandbox-laravel-package
```

**DOCKER**

- Start docker service (optional)
```bash
  sudo service docker start
``` 
- Build container
```bash
  docker-compose build
```
- **(optional)** If you are running the first time, network needs to be created before start container.
```bash
  docker network create db-postgres-net
``` 
- Start container
```bash
  docker-compose up
  docker-compose up -d (silent mode)
```

**BACKEND**

- Due to it is a submodule, check that you are in the right branch.
- Copy `.env.*` files (the standalone .env only will have the environment's name)
- Access container by ssh.
```bash
    docker exec -it profiles-sandbox-laravel-package /bin/ash
```
- Run migrations and seeders.
```bash
    php artisan migrate --seed
```
- To check that everything is ok access to url.

```bash
    http://0.0.0.0:8160/
```

- To check that API is working ok access to url.

```bash
  http://0.0.0.0:8160/api/users
```

**DATABASE**

- Check folder `/postgres/data` exist, if delete it and create again else only create it `(before start container)`.
- Check if migrations on laravel were run.
- Using your favorite ID connect to DB.
    | ID        | VALUE         | 
    | :-------- | :--------     | 
    | host      | `localhost`   | 
    | dbname    | `laravel_api_template`| 
    | dbuser    | `postgres`    | 
    | dbpass    | `password`    | 
    | port      | `5440`        | 


# Commons problems

**Laravel's container**

- If there is a problem about permissions with `laravel.log`, delete that file.
- If there are problems about permissions with folder `storage/framework/views`, delete it.

## Author

- [@jclavo](https://github.com/Jclavo)