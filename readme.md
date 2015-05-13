Nette • TodoMVC
--------------

Skeleton used [web-project](http://github.com/nette/web-project). Functionality based on [todomvc.com](http://todomvc.com/).

Features:
---------

- tasks order (drag & drop)

##Quickstart##

- Clone project

- `composer install`

- Create database manually and set up access in `config.local.neon`. For example:

``` neon
doctrine:
	user: root
	password:
	dbname: todomvc
	metadata:
		App: %appDir%
```

- `php www/index.php orm:schema-tool:create`

- `php www/index.php app:create-user <username> <password>`

- Enjoy :)
