требование к среде: yarn npm mysql-8
Команды для запуска проекта
~~~
git clone 
composer install
composer setup
yarn install
npm run dev
~~~
создайте DATABASE в базе данных и сконфигурируйте базу данных в .env
настроить cron
~~~
0 6 * * * /path/bin/console currency:load
~~~
