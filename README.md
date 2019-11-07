# BOLDIODE
### A Simple MVC based on PHP MVC Structure from Scratch by WildCodeSchool

## Description

This repository is a simple PHP MVC structure from scratch.
It uses some cool vendors/libraries such as Twig, Grumphp and Mailer.

The aim of this website is to manage an Hostel (add/modify/delete rooms - introduce hostel to public - contact hostel team by email sendingform).
The owners of Boldiode Hostel in Quiberon are François and Cécile. They asked us to create this tool form their hostel. 

## Steps

1. Clone the repo from Github.
2. Run `composer install`.
3. Create *config/db.php* from *config/db.php.dist* file and add your DB parameters. Don't delete the *.dist* file, it must be kept.
```php
define('APP_DB_HOST', 'your_db_host');
define('APP_DB_NAME', 'your_db_name');
define('APP_DB_USER', 'your_db_user_wich_is_not_root');
define('APP_DB_PWD', 'your_db_password');
define('GMAIL_USER', 'user@gmail.com'); 
define('GMAIL_PWD', 'password');
```

4. Import `bdd.sql` in your SQL server,
5. Run the internal PHP webserver with `php -S localhost:8000 -t public/`. The option `-t` with `public` as parameter means your localhost will target the `/public` folder.
6. Go to `localhost:8000` with your favorite browser.
7. Try to log on admin page (a first admin is created in `bdd.sql`) or just consult the website.

### Windows Users

If you develop on Windows, you should edit you git configuration to change your end of line rules with this command :

`git config --global core.autocrlf true`

## URLs availables

* Home page at [localhost:8000/](localhost:8000/)
* Rooms list at [localhost:8000/room/show](localhost:8000/room/show)
* Rooms details will be available at [localhost:8000/item/index/show/:id](localhost:8000/item/show/2) - for next version
* Administration Login Page at [http://localhost:8000/admin/login](localhost:8000/admin/login)
* Add a new admin at [localhost:8000/admin/addAdmin](localhost:8000/admin/addAdmin)
* Room edit list at [localhost:8000/admin/editlist/](localhost:8000/admin/editlist/)
* Room edit or delete at [localhost:8000/admin/edit/:id](localhost:8000/admin/edit/1)
* Room add at [localhost:8000/admin/add](localhost:8000/admin/add)
* Contact Informations and access [localhost:8000/Contact/sendMail](localhost:8000/Contact/SendMail)


## Project team : 
Benjamin Jaud [@armoredbrain](https://github.com/Armoredbrain) / 
Julien Guibert [@kimor44](https://github.com/kimor44) / 
Martin Cazamajor [@martincazamajor](https://github.com/MartinCazamajor) / 
Alexandre Barré [@barre-alex44](https://github.com/barre-alex44) / 
Delphine Belet [@apsuma](https://github.com/apsuma)
Wild Code School Nantes 2019, October-November. 