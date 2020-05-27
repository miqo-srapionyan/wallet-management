## Wallet Management

Okay, so this app is about online wallet managemnt. You can add your wallets credit cars and or 
cash, then fill it with your expences or income to track your balanace.
##### Used packages
- Default laravel auth system (with some customization)
- Laravel Socialite package to make register and login vide facebook or google
- VueJS as frontend service for dashboard page (with some additinal libraries)

##### Notes
I do not validate front end, so i keep my time and energy to focus on back-end part, but i 
know that it is important thing. There are some UI issues (i am not professional front-end guy)
sorry for that.

##### How to install and use
- First clone the repo and go to the cloned directory
- Run `composer install`
- Run `npm install`
- Run `npm run dev` to get compiled js
- Copy credentials from .env.example to your .env file (i keep it on example so you dont need to open facebook app or google app) but you need to configure mail using your mailtrap or other service
- Config database for both .env and .env.testing
- Run server `php artisan serve`
- Run tests `./vendor/bin/phpunit tests/Http`
- Enjoy the application :pizza: 

Thank you for your time!
