# Laravel-Blog-Website

A straightforward blog page written in PHP/Laravel.

![main](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/5a197c23-7ff0-4726-8361-52b06235350f)

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

![post](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/009bd73d-e7b1-4504-af2b-8ece5a1f7b4a)

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
![dashboard_posts](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/e434b5ad-4dc4-43f4-a4d9-fd9b1fb53d67)
![posts_create](https://github.com/Mati822456/Laravel-Blog-Website/assets/103435077/77146227-071b-4385-9634-df5a676ded5a)

## Incoming Features

| Name                             | Status               | Date added |
|----------------------------------|----------------------|------------|
| Version control of each post     | &#9745; Finished     | 2023-05-10 |
| Tiles on the home page           | &#9745; Finished     | 2023-12-03 |
| Improve post creation/editing UI | &#9745; Finished     | 2023-12-06 |
| Categories                       | &#9745; Finished     | 2023-12-09 |
| Reading time                     | &#9745; Finished     | 2023-12-21 |
| Pinned post                      | &#9745; Finished     | 2024-01-01 |
| Better tiles in history          | &#9745; Finished     | 2024-01-04 |
| Changelog for posts              | &#9745; Finished     | 2024-01-04 |
| History post comparison          | &#9745; Finished     | 2024-01-10 |
| Remove jQuery                    | &#9745; Finished     | 2024-01-11 |
| Dark mode                        | &#9745; Finished     | 2024-01-15 |
| More filtering                   | &#9745; Finished     | 2024-01-18 |
| Auto-save editing post           | &#9744; To do        | -          |
| Super-role                       | &#9744; To do        | -          |
| Observer on main page            | &#9744; To do        | -          |

## Acknowledgements

Thanks <a href="https://www.flaticon.com/free-icons/user" title="user icons">User icons created by kmg design - Flaticon</a> for the user profile icon</br>
Thanks <a href="https://www.flaticon.com/free-icons/email" title="email icons">Email icons created by Freepik - Flaticon</a> for the envelope icon on the contact page</br>
Thanks <a href="https://www.flaticon.com/free-icons/blog" title="blog icons">Blog icons created by zero_wing - Flaticon</a> for the blog icon as favicon</br>

## Contact

Feel free to contact me via email mateusz.zaborski1@gmail.com. :D
