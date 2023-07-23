## Intro

**[DoSSIM](https://dossims.online)** (Doming's Sales and Inventory Management) is an advanced software solution tailored for Doming's Steel Trading, a company with 15 branches specializing in hardware and roofing products. Leveraging trend data analytics, DoSSIM allows for sales pattern observation and product restocking analysis, using AI algorithms to make data-driven decisions and predict sales trends accurately.

DoSSIM's standout feature is its progressive web application functionality, enabling easy installation on mobile devices without the need for app stores. This accessibility empowers sales representatives and inventory managers to access real-time data and perform essential tasks on the go, enhancing efficiency and customer service. The system ensures top-notch security through encryption and multi-factor authentication to safeguard sensitive information.

With streamlined operations and automated restocking, DoSSIM empowers Doming's Steel Trading to excel in a competitive market. Its user-friendly interface and customer-focused approach make it a pivotal asset for the company's success, fostering growth and innovation in the industry.

# **Dossims Setup**
### **Requirements**

* [Laradock](https://laradock.io/getting-started/) 
* [Git Bash](https://git-scm.com/downloads)

<!-- <i id="dossims"></i> -->
### **Dossims Configuration**

1. Install [Git Bash](https://git-scm.com/downloads).
2. Open terminal on the folder where you want to put your project folder.
3. Run command `git clone https://github.com/scarcetti/Dossims.git`.
4. Open the project folder on your preferred IDE. Open `.env` file.
5. Change the value of `APP_URL` to `APP_URL=http://localhost:80`
6. Change the following code:
```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=dossims
DB_USERNAME=default
DB_PASSWORD=secret
```


### **Docker configuration**

This documentation **assumes** that you followed the setup documentation of **[Laradock](https://laradock.io/getting-started/)**

***Note:** Proceed to step 1 if you already have a Docker installed*

1. Install [Docker Compose](https://docs.docker.com/compose/install/)
2. Open your Laradock folder on your preferred IDE. Open `.env` file of the Laradock.
3. Change the value of your `PHP_VERSION` to  `PHP_VERSION=8.0`
4. Find and open `default.conf`. 
5. Change the `root` directory of the server to `root /var/www/dossims/public;`
>If there are other projects on your Laradock, copy the entire block of 
```
server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    //code here

    server_name localhost;
    root /var/www/dossims/public;
    
    //code here
}
```
>then change the `root` directory of the server to `root /var/www/dossims/public;`

**Note**:  make sure the value of `listen` matches the [APP_URL](#dossims-configuration) port

### **Postgres configuration**

1. Open the terminal from the root directory of your Laradock folder.
2. Run command `docker compose up -d nginx pgadmin`.
3. Run command `docker ps`.
4. Copy the `CONTAINER_ID` of `laradock-postgres`.
5. Run `docker cp [database_file] [CONTAINER_ID]:.`.
>*example:* `docker cp dossims_200223.psql b0f2e36a12c6:.`
6. Run command `docker-compose exec postgres bash` or `winpty docker-compose exec workspace bash`
7. Run command `psql -U default [DB NAME] < [BACKUP FILE]`
>*example:* `psql -U default dossimsdb < dossims_200223.psql`

### **Other configuration**

1. Open the terminal from the root directory of your Laradock folder.
2. Run command `docker compose exec workspace bash`.
3. Run command `cd dossims`.
4. Run command `composer install`.
5. Run command `php artisan key:generate`
6. Run command `php artisan optimize:clear`
7. Run command `composer dump-autoload`
