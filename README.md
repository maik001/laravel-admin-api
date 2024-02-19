## Dependencies
- Docker
- Docker Compose

## Configuration to do after clone the project and start container
cp .env.example .env && \
php artisan key:generate && \
php artisan migrate && \
php artisan passport:install && \
php artisan db:seed
