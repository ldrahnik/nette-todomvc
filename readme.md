Nette • TodoMVC
--------------

Skeleton used [web-project](http://github.com/nette/web-project). Functionality based on [todomvc.com](http://todomvc.com/).

##Install##

``` bash
  git clone https://github.com/ldrahnik/nette-todomvc.git
  cd nette-todomvc
  composer install
```

Create database manually and set up access in `config.local.neon`. For example:

``` neon
doctrine:
	user: root
	password:
	dbname: todomvc
	metadata:
		App: %appDir%
```

``` bash
php www/index.php orm:schema-tool:create
```