#PHP Hybrid SPA

####Description
This project is meant to be used as a hybrid AJAX application for PHP. It is based on SLIM php and Twig template engine.

####Features
* Twig Templates
* Grunt task runner to compile client code
* Paths are determined by a json file that is read both by grunt and SLIM.
* Coming Soon - Frontend skeleton application to get you started.


####Apache Set Up

#####Hosts File
Add this to your hosts file

```127.0.0.1       local.hybrid-php.com```

#####VHost Config

```
<Directory "absolute_path_to_project/app">
    Options Indexes FollowSymLinks
    AllowOverride all
    Order allow,deny
    Allow from all
    Require all granted
</Directory>

<VirtualHost 127.0.0.1:80>
    ServerName local.hybrid-php.com
    DocumentRoot "absolute_path_to_project/app"
</VirtualHost>

```



####Initialization
* npm install
    * You may encounter problems with imagemin because its lame on Windows... and Mavericks?
* bower install


####Commands

* grunt setup - runs all necessary tasks to build the app and then runs the dev task

* grunt or grunt dev - Build for development
* grunt dist - Build for production

