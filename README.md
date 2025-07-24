# Laravel Subscription Platform API

##  Installation

### 1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/subscription-api.git
cd subscription-api


composer install

cp .env.example .env




Edit .env with your local MySQL and Mail configuration:


php artisan key:generate

php artisan migrate --seed

php artisan queue:work

php artisan serve