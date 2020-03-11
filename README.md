требование к среде: yarn npm mysql-8

Команды для запуска проекта
~~~
git clone git@github.com:cheremhovo1990/test_mchs_09_2020.git .
composer setup
composer install
yarn install
npm run dev
~~~
создайте DATABASE в базе данных и сконфигурируйте базу данных в .env
~~~
./bin/console doctrine:migrations:migrate    
~~~

настроить cron
~~~
0 6 * * * /path/bin/console currency:load
~~~
