Nette • TodoMVC
--------------

Skeleton used [web-project](http://github.com/nette/web-project). Functionality based on [todomvc.com](http://todomvc.com/).

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
