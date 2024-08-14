Здравствуйте! Проект на Докере, поэтому нужно просто:

  1. Его клонировать
  2. Зайти в корень с командной строки
  3. Ввести 'docker-compose up -d'
  4. Зайти в контейнер php ('docker exec -it php bash') и установить Композером зависимости - 'composer install'
  5. Зайти в БД (можно через PHPMyAdmin, он есть в сборке docker-compose.yml)
  6. Там создать БД 'cinema_db' (utf8mb4_general_ci)
  7. В неё загрузить выгрузку MySQL, которая лежит в корне (cinema_db.sql)
  8. Если потребуется - подставить свои данные в файл .env (внутри project-core)
  9. Profit!!!

Я гитом ничего особо не скрывал, стандартный пароль от БД (стоит в docker-compose.yml): yesenin, пользователь БД: root.
В папке 1-screens-image-doc лежат скриншоты, структура файлов и дополнительная информация.

Маленький ООП MVC движок на PHP, с RESTful маршрутизатором и БД (MySQL + phpMyAdmin). Контроллеры в '\project-core\app\Controllers', модели в '\project-core\app\Models', представления в '\project-core\views'.

Решил написать на чистом PHP. Хоть у меня и есть свой собственный сайт (с которым я, к сожалению, ничего не делал уже почти год) на Laravel (Laravel 9 + AlpineJS).

Вот он: www.borozepp.ru
<br>
Вот его репозиторий: https://github.com/Zeppgoespro/frodo-project-laravel

Я решил, что мне нужно сперва хорошенько "шлифануться". Для чего я смотрю курс на youtube, читаю "Laravel. Полное руководство. 3-е издание" (Laravel 10) Мэтта Стаффера и потираю руки, поглядывая на совсем свежий курс по Laravel 11 от Laracast.
