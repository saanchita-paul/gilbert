#!/bin/bash
composer install --prefer-dist
php artisan migrate
php artisan optimize:clear
php artisan queue:restart
php artisan optimize

if [[ $1 != "-b" ]];
then
    yarn
    yarn prod
else
    printf "\n\033[0;33mSkipping frontend installation\n\033[0m"
fi
printf "\033[0;32m\n\nDeployment complete\n\033[0m"



