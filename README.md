# Delivery - Project

This project is a delivery for products, foods and services.
The project was create and developed by myself.
The project contains administration tools of products and ecommerce web page.

Technologies used:
1. FrameWork Laravel. Version: > 7.
1. PHP Language to back end. Version PHP: PHP/7.2.34.
1. Vue.js to front end more html 5 and css 3. Version Vue: 2.6.12.
1. MariaDB to data base. Version: 10.4.14-MariaDB.

##### Website: [https://www.mercadocampobom.com/](https://www.mercadocampobom.com/)
##### Dashboard: [https://www.mercadocampobom.com/dashboard](https://www.mercadocampobom.com/dashboard)

## Installation

1. Clone the repo and `cd` into it.
1. `composer install`.
1. Rename or copy `.env.example` file to `.env` comand `copy .env.example .env`.
1. Alter if will you want the `APP_NAME` have name whatever you want.
1. Set your `APP_URL` in your `.env` file. This is needed for Vue to correctly resolve asset URLs.
1. `php artisan key:generate`.
1. Set your database credentials in your `.env` file.
1. Set your FaceBook Social Media credentials in your `.env` file. Specifically `FACEBOOK_CLIENT_ID` and `FACEBOOK_CLIENT_SECRET`.
1. Set your Google Social Media credentials in your `.env` file. Specifically `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET`.
1. Set your PayPal credentials in your `.env` file if you want to use PayPal. Specifically `PAYPAL_MODE` and `PAYPAL_CLIENT_ID_LOCAL` and `PAYPAL_SECRET_LOCAL`.
1. `php artisan project_delivery:install`. This will migrate the database and run any seeders necessary.
1. `npm install`.
1. `npm run dev`.
1. `php artisan storage:link` for link storage directory to public.
1. `php artisan serve` or use Laravel Valet or Laravel Homestead.
1. Visit `localhost:8000` in your browser.

## Starting from a particular point

1. Clone the repo and `cd` into it.
1. Follow the rest of the steps above. Instead of `php artisan project_delivery:install`, migrate and seed the normal way with `php artisan migrate --seed`.
