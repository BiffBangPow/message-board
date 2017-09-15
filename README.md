# Message Board

## Installation
1. Checkout from Git: `git clone https://github.com/BiffBangPow/message-board.git`
2. Install all third party dependancies with composer `composer install --ignore-platform-reqs`
3. Build Application Environment with Docker Compose `docker-compose up -d`
4. Build the database schema `docker-compose exec php vendor/bin/doctrine orm:schema-tool:update --force`
5. Visit the application running in your browser http://localhost:3987/
6. Optionally, run the dev fixtures to load dummy data into the database
`docker-compose exec php php bin/load-dev-fixtures.php`

## To Run the Tests
`docker-compose exec php php vendor/bin/phpunit`