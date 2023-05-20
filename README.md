# Dockerizing News App Laravel

1. Create a Dockerfile: Create a file named Dockerfile (without any file extension) in the root directory of Laravel app. Open the file in a text editor and add the following content:

```
Use an official PHP runtime as the base image
FROM php:8.1-apache

Set the working directory inside the container
WORKDIR /var/www/html

Install system dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip

Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

Copy composer.lock and composer.json to the working directory
COPY composer.lock composer.json ./

Install app dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts

Copy the app's source code to the working directory
COPY . .

Generate the Laravel application key
RUN php artisan key:generate

Set the storage and cache directory permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

Expose port 80
EXPOSE 80

Specify the command to run app
CMD ["apache2-foreground"]
```
2. Create a .dockerignore file: Create a file named .dockerignore in the root directory of Laravel app. Open the file in a text editor and add the following content:

```
/vendor
/node_modules
/public
```
3. Build the Docker image: Open a terminal or command prompt and navigate to the root directory of Laravel app (where the Dockerfile is located). Run the following command to build the Docker image:

>docker build -t news-app-laravel .

This command builds the Docker image using the Dockerfile and tags it with the name news-app-laravel. You can change the tag name to something else if desired.

4. Run the Docker container: After the Docker image is built, you can run it as a container using the following command:

>docker run -p 80:80 news-app-laravel

5. Access the Laravel app: With the Docker container running, you can access Laravel app by opening a web browser and navigating to http://localhost. You should see Laravel app running inside the Docker container.

Done ...

