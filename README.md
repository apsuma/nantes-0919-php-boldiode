# BOLDIODE
### Discover and manage an hotel in Quiberon.  
A students project at WildCodeSchool (Nantes).

## Description

The aim of this website is to manage an hotel (add/modify/highlight/delete rooms - introduce hotel and his owners to the public - contact hotel team by email sendingform).
The owners of Boldiode Hotel in Quiberon are François and Cecile. They asked us to create this tool for their hotel.



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

### Public access of this website :

* Home page at [http://localhost:8000/](http://localhost:8000/)
* Rooms list at [http://localhost:8000/room/show](http://localhost:8000/room/show)
* History of the hostel at [http://localhost:8000/History/index](http://localhost:8000/History/index)
* Contact information and access at [http://localhost:8000/Contact/sendMail](http://localhost:8000/Contact/sendMail) 

### For administration of this website :
* Administration Login Page at [http://localhost:8000/admin/login](http://localhost:8000/admin/login)
* Add a new admin at [http://localhost:8000/admin/addAdmin](http://localhost:8000/admin/addAdmin)
* Room edit list, highlight room to frontPage and delete at [http://localhost:8000/admin/editlist/](http://localhost:8000/admin/editlist/)
* Room edit and delete at [http://localhost:8000/admin/edit/:id](http://localhost:8000/admin/edit/1)
* Room add at [http://localhost:8000/admin/add](http://localhost:8000/admin/add)
* Theme list add, edit and delete at [http://localhost:8000/admin/editlistTheme](http://localhost:8000/admin/editListPrice)
* View list add, edit and delete at [http://localhost:8000/admin/editlistView](http://localhost:8000/admin/editlistView)
* Price list add, edit and delete at [http://localhost:8000/admin/editListPrice](http://localhost:8000/admin/editListPrice)

### Picture manager
You can add / delete pictures within the administration part of this website. 
You can upload any picture (.jpeg, .jpg, .gif  and .png format accepted - 950 ko size max for each file).
You can upload more than one picture at the same time.
All your uploaded files are in `/public/uploads/images` folder.

## Dev informations
This repository is a based on a simple PHP MVC structure from scratch created by WildCodeSchool.
It uses some cool vendors/libraries such as `Twig`, `Grumphp` and `Mailer`.

### Languages
 `HTML5`, `CSS3`, `PHP7.2`, `Mysql`.
 
### Project Methodology
We used SCRUM method for all this project realization with `Trello` and `Slack` tools, in 4 sprints (6 weeks).
 
## Project team : 

##### Martin Cazamajor [@martincazamajor](https://github.com/MartinCazamajor) 
##### Julien Guibert [@kimor44](https://github.com/kimor44)
##### Benjamin Jaud [@armoredbrain](https://github.com/Armoredbrain)
##### Alexandre Barré [@barre-alex44](https://github.com/barre-alex44)
##### Delphine Belet [@apsuma](https://github.com/apsuma) 
##### Wild Code School Nantes 2019, October-November. 