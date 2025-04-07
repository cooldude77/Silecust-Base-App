This is the sample Silecust Web App. 
This is based on Symfony Framework, so you would need basic knowledge of the framework like setup/config etc
You can download it , run migration and it works out of the box. You can derive
your app based on this.  
Requirements: Ubuntu 20.04 / mariadb/ apache2
Run the following commands and see the application working 

Create .env files and set
```
DATABASE_URL="mysql://<username>:<userpassword>@127.0.0.1:3306/silecust_web_shop?charset=utf8mb4"
###> symfony/mailer ###
MAILER_DSN=null://null
###< symfony/mailer ###
```

Then run these commands

```
php bin/console doctrine:database:create  
php bin/console doctrine:migrations:migrate  
php bin/console silecust:user:super:create

php bin/console importmap:require tom-select/dist/css/tom-select.default.css
```

More details coming soon