# Laravel-Blog-Website

A straightforward blog page written in PHP/Laravel.

![main](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/8221ef38-b163-4482-9864-e86c2a6df1da)

## Table of Contents

-   [General Info](#general-info)
-   [Technologies](#technologies)
-   [Setup](#setup)
-   [Incoming Features](#incoming-features)
-   [Acknowledgements](#acknowledgements)
-   [Contact](#contact)

## General Info

The website was built with PHP, MySQL, and Laravel. It allows you to go through every post that is on the main page. You can add comments. You have complete control while logged in as an administrator, including the ability to create, edit, and delete posts, users, and comments, as well as manage roles. There are two positions available: administrator and writer. Default permissions for Writer are: adding or editing owned posts; deleting comments in your posts. Furthermore, you can save posts, continue writing later, and then publish them. Also, you are able to send emails to users after updating their accounts.

Furthermore, the website is fully responsive.

I tried to add the best protection I could to this website. I'm referring to prohibitions against deleting other people's posts besides the "Admin" account, deleting roles owned by others or the "Admin" role, deleting yourself, and even editing another person's account.

![post](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/a460068f-e71e-4896-a433-b84594f87533)

## Technologies

-   Laravel 9.45.1
-   Blade
-   PHP 8.1.7
-   MySQL 8.0.29
-   HTML 5
-   CSS 3
-   JavaScript
-   SweetAlert 2
-   FontAwesome 6.5.1

## Setup

To run this project you will need to install PHP, MySQL, [Composer](https://getcomposer.org/download/), [NPM](https://www.npmjs.com/package/npm) on your local machine.

If you have everything, you can run these commands:

```
# Clone this respository
> git clone https://github.com/Mati822456/Laravel-Blog-Website.git

# Go into the folder
> cd Laravel-Blog-Website

# Install dependencies from lock file
> composer install

# Install packages from package.json
> npm install

# Compile assets
> npm run build
```

`Create or copy the .env file and configure it. e.g., db_username, db_password, db_database`
</br>
`You will need to configure SMTP in order to send emails.`

```
# Generate APP_KEY
> php artisan key:generate

# Run migrations if you have created database
> php artisan migrate

# Run seeder to create Permissions, Admin and Writer users and 10 random posts
> php artisan db:seed

# Start server
> php artisan serve

# Access using
http://localhost:8000
```

Now you can login using created accounts:

```
Role: Admin
Email: admin@db.com
Password: admin1234

Role: Writer
Email: writer@db.com
Password: writer1234
```

![dashboard](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/c3d756c1-fda3-4a91-93a6-102acbc32103)
![dashboard_posts](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/a4b8fc70-4fed-4785-ad78-ee13e8a0d663)
![posts_create](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/c7e3ce79-7df2-48ea-92ff-b76fdbbbf0e7)

## Incoming Features

-   ~~version control of each post~~
-   ~~probably tiles on the home page~~
-   ~~categories~~
-   ~~pinned post~~
-   ~~dark mode~~
-   more filtering
-   ~~better tiles in history~~
-   ~~changelog for posts~~
-   ~~remove jQuery~~

## Acknowledgements

Thanks <a href="https://www.flaticon.com/free-icons/user" title="user icons">User icons created by kmg design - Flaticon</a> for the user profile icon</br>
Thanks <a href="https://www.flaticon.com/free-icons/email" title="email icons">Email icons created by Freepik - Flaticon</a> for the envelope icon on the contact page</br>
Thanks <a href="https://www.flaticon.com/free-icons/blog" title="blog icons">Blog icons created by zero_wing - Flaticon</a> for the blog icon as favicon</br>

## Contact

Feel free to contact me via email mateusz.zaborski1@gmail.com. :D
