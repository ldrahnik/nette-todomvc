Nette • TodoMVC
--------------

Functionality based on [todomvc.com](http://todomvc.com/).

Features:
---------

- users
- tasks order (drag & drop)

##Quickstart##

- Clone project

- Create database manually and set up access in `config.local.neon`. For example:

``` neon
doctrine:
	user: root
	password:
	dbname: madeo.todo
	metadata:
		App: %appDir%
```

- `php www/index.php orm:schema-tool:create`

- `php www/index.php app:create-user <username> <password>`

- Enjoy :)
