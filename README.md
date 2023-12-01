# WeaveWatch

## installation
1. Download PHP [Link](https://windows.php.net/downloads/releases/php-8.2.13-nts-Win32-vs16-x64.zip)
    - Add PHP to windows path [Link](https://www.forevolve.com/en/articles/2016/10/27/how-to-add-your-php-runtime-directory-to-your-windows-10-path-environment-variable/)
2. Download and install MySQL database [Link](https://dev.mysql.com/get/Downloads/MySQLInstaller/mysql-installer-community-8.0.35.0.msi)
    - Create a database with the name you like
3. In the .env file, put your database configuration, username, password and database name.
4. Now inside the project folder, run `php artisan migrate:fresh --seed`
5. Run `php artisan serve`
6. Open [127.0.0.1:8000](http://127.0.0.1:8000)
7. Enter email and password
    - Email: "ramy@test.com"
    - Password: "0000"