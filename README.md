# Choose Your Own Adventure

# Install
```
php -r "echo 'MOJESRODOWISKO';" > .environment
php -r "copy('application/config/environment/example.php', 'application/config/environment/MOJESRODOWISKO.php');"
php -r "mkdir('tmp'); mkdir('logs');"
composer install
tools/doctrine migrations:migrate
```