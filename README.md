# Requirments

Docker

## Setup doker containers

*.env.laradock.example is modified with necessary changes for easy building*

Building doker containers

```
cd PATH_TO_PROJECT/emp-back-end
```

```
git submodule update
```

```
cp .env.laradock.example laradock/.env
```

```
cd laradock
```

Change DOCKER_HOST_IP if needed, find local docker IP address:
```
ip addr show | grep "\binet\b.*\bdocker0\b" | awk '{print $2}' | cut -d '/' -f 1
```

Build doker containers
```
docker-compose up -d nginx php-fpm mysql phpmyadmin
```

## Setup project

*.env.example is modified with necessary changes for easy building*
```
cd PATH_TO_PROJECT/emp-back-end
```

Log into workspace container
```
docker exec -it --user=laradock emp-back-end_workspace_1 bash
```

```
cp .env.example .env
```

```
composer install --ignore-platform-reqs
```

Migrate database
```
php vendor/bin/phinx migrate -c ./config/phinx.php
```

PHP My admin: http://localhost:8081/
