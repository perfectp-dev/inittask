# Вводное задание

## Вывод списка заказов с возможностью фильтрации данных

### Сборка

Для сборки нужны локально установленные git, docker, docker-compose

1. клонирование репозитория

   переходим в выбранную директорию, затем:
```bash
git clone https://github.com/perfectp-dev/inittask.git .
```

2. Настраиваем.

копируем `.env.example` в `.env`

в `.env`:

    - NGINX_PORT, PHPMYADMIN_PORT - настройки портов.
    - CONTAINER_NGINX, CONTAINER_PHP, CONTAINER_MYSQL, CONTAINER_PHPMYADMIN - имена контейнеров 
    - MYSQL_DATABASE, DB_USER, DB_PASSWORD, DB_ROOT_PASSWORD -  настройки БД

3. далее, для сборки образов надо запустить `build-image.sh`
4. после успешной сборки запустите контейнеры с помощю `run.sh`
5. затем соберите проект запустив `exec-composer-install.sh`
6. разрешите запись в папку runtime запустив `chmod-runtime.sh`
7. выполнить миграции - `run-migrate.sh`

После этих действий сервис должен быть доступен по адресу http://localhost:8083/orders/

Если изменяли порт в NGINX_PORT используйте его вместо 8083