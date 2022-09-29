#  Sandbox for Laravel Package

This is a container to run a Sandbox for Laravel Package

# Steps to run it

- clone the project.
```bash
  git clone https://github.com/Jclavo/sandbox-laravel-package
```

**DOCKER**

1. Start docker service (optional)
```bash
  sudo service docker start
``` 
2. Build container
```bash
  docker-compose build
```
3. **(optional)** If you are running the first time, network needs to be created before start container.
```bash
  docker network create db-postgres-net
``` 

4. Check that Laravel container command section looks like
```bash
    command: php artisan serve --host 0.0.0.0
    # command:  tail -f /dev/null
```

5. Start container
```bash
  docker-compose up
  docker-compose up -d (silent mode)
```

**PACKAGE**

1. clone the package as submodule

```bash
  git submodule add [`git-repository`] packages/[vendor-name]/[package-name]
```
2. Add package's provider on `app.php` from your project

```php

    'providers' => [
        ...
        /*
        * Package Service Providers...
        */
        [vendor-name]/[package-name]\Providers\[provider-name]::class,
        ...
    ]
```

3. Add package's alias on `src/composer.json` from your project

```json
  "autoload": {
        "psr-4": {
            ...
            "[vendor-name]\\[package-name]\\": "packages/[vendor-name]/[package-name]/src/",
            "[vendor-name]\\[package-name]\\Database\\Factories\\": "packages/[vendor-name]/[package-name]/database/factories/"
        }
    },
```

**BACKEND**

1. Due to it is a submodule, check that you are in the right branch.
2. Create `.env` file base on `.env.example`

3. uncomment command to keep run container and comment the other on `docker-compose.yml` file
```bash
    #command: php artisan serve --host 0.0.0.0
    command:  tail -f /dev/null
```

4. Start container (complete steps are explained in the last section)
```bash
  docker-compose up
```

5. Access container by ssh.
```bash
    docker exec -it profiles-sandbox-laravel-package /bin/ash
```
6. Install dependencies, it means folver /vendor will be created.
```bash
    composer install
```

7. Run migrations and seeders.
```bash
    php artisan migrate --seed
```

8. Stop container
```bash
  docker-compose down
```
9. Check that Laravel container command section looks like
```bash
    command: php artisan serve --host 0.0.0.0
    # command:  tail -f /dev/null
```
10. Start container 
```bash
  docker-compose up
```

11. To check that everything is ok access to url.

```bash
    http://0.0.0.0:8160
```

12. To check that API is working ok access to url.

```bash
  http://0.0.0.0:8160/api/users
```

**DATABASE**

1. Check folder `/postgres/data` exist, if delete it and create again else only create it `(before start container)`.
2. Check if migrations on laravel were run.
3. Using your favorite ID connect to DB.
    | ID        | VALUE         | 
    | :-------- | :--------     | 
    | host      | `localhost`   | 
    | dbname    | `laravel_sandbox_package`| 
    | dbuser    | `postgres`    | 
    | dbpass    | `password`    | 
    | port      | `5440`        | 


# Commons problems

**Laravel's container**

- If there is a problem about permissions with `laravel.log`, delete that file.
- If there are problems about permissions with folder `storage/framework/views`, delete it.

## Author

- [JClavo]