# Sample Silecust Web App.

The Silecust web app is a bundle to Symfony project. You can use your web app to add Silecust bundle as a part of app or
use this app as base app to start your own installation journey

**Note:**
This app is NOT production ready

## Requirements

You would need basic knowledge of the framework like setup/config etc
You can download it , run migration and it works out of the box. You can derive
your app based on this.

`Requirements: Ubuntu 20.04 / mariadb/ apache2
`

### Perform these steps first

- #### Clone the repo

- #### Create .env files and set

Example (env.dev.local for dev env and env.test.local for test environment)

```
DATABASE_URL="mysql://<username>:<userpassword>@127.0.0.1:3306/silecust_web_shop?charset=utf8mb4"
###> symfony/mailer ###
MAILER_DSN=null://null
###< symfony/mailer ###
```

- #### Run these commands in your installation directory

```
php bin/console doctrine:database:create  
php bin/console doctrine:migrations:migrate  
php bin/console silecust:user:super:create

php bin/console importmap:require tom-select/dist/css/tom-select.default.css
```

- #### (Optional) Run test cases (using PHPUNIt or PHPStorm)

The test cases are copied from [Silecust/web-shop bundle](https://github.com/cooldude77/SilECust-WebShop) and will
also (should) work fine here

- ### Visit the installed site

On the web browser the address should look something like

**http(s)://localhost/<your-installation-dir>/public/index.php**