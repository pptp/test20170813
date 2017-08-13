Test currency exchange api
==========================

Установка:


1. Установим Composer Asset Plugin (необходим для Yii2):

```
composer global require "fxp/composer-asset-plugin:^1.2.0"
```


2. Клонируем проект

```
git clone https://github.com/pptp/test20170813.git .
```


3. Устанавливаем зависимости

```
composer install
```


4. Настраиваем nginx.

Нам нужно два виртуальных хоста для АПИ и для бекенда.
Примерный файл настроек:

```
server {
    listen 80;

    root ${path}/${application}/web;
    index index.php;

    server_name ${host};

    location / {
            try_files $uri /index.html /index.php$is_args$args;
    }

    client_max_body_size 200m;

    location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
            include snippets/fastcgi-php.conf;
    }
}

```

, где:
${path} - путь куда вы склонировали репозиторий
${host} - виртуальный хост, который вы хотите использовать для проекта
${application} - это или "api" или "backend". Так, как данное приложение имеет две точки входа, то оба хоста надо включить

Подключаем настройки, перезагружаем сервере, не забываем прописать указанные хосты в ```/etc/hosts```


5. Настройка базы данных

Копируем пример файла настроек базы данных (находясь в папке с проектом):

```
cp ./common/config/db.dist.php ./common/config/db.php
```

И редактируем там поля:
```
'dsn' => 'mysql:host=localhost;dbname=databasename',
'username' => '',
'password' => '',
```
указываем хост сервера базы данных, имя базы данных, которую будем использовать в проекте, пользователя и пароль.


6. Накатываем миграции

Заходим в директорию проекта и выполняем 
```
./yii migrate
```

Yii подразумевает загитигноренные локальные настройки для каждого деплоя, генерируемые при помощи команды ```./init```, однако для упрощения, сделал этот шаг не необходимым

7. Возможно появление ошибок Permission Denied при запуске приложения. Самый быстрый способ пофиксить, выполнить:
```
chown www-data -R .
```
В директории проекта.
