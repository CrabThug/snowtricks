# snowtricks

[![Maintainability](https://api.codeclimate.com/v1/badges/0927282ba7972272f032/maintainability)](https://codeclimate.com/github/CrabThug/snowtricks/maintainability)


requirement globaly : composer, yarn, symfony

clone the repository

```
composer install
bin/console d:d:c
bin/console make:migration
bin/console d:m:m -n
(if u want fixtures)
bin/console d:f:l -n
```

modifier le fichier env en prod

```
symfony server:start -d
```


