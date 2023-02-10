## Wallet Management

Okay, so this app is about online wallet management. You can add your wallets, credit cards and or 
cash, then fill it with your expenses or income to track your balanace.
##### Used packages
- Default Laravel auth system (with some customization)
- Laravel Socialite package to make register and login via facebook or google
- VueJS as frontend service for dashboard page (with some additional libraries)

##### Notes
I do not validate front end, so I keep my time and energy to focus on back-end part, but I 
know that it is important thing. There are some UI issues (I'm not professional front-end guy)
sorry for that. Also I'm not sending confirmation email while registering with social.

##### How to install and use
- First clone the repo and go to the cloned directory
- Run `composer install`
- Run `npm install`
- Run `npm run dev` to get compiled js
- Copy credentials from .env.example to your .env file (you have to create facebook app and google app) and also you need to configure mail using your mailtrap or other service
- Config database for both .env and .env.testing
- Run `php artisan migrate` and `php artisan migrate --env=testing`
- Run server `php artisan serve`
- Run tests `./vendor/bin/phpunit tests/Http`
- Enjoy the application :pizza: 

Thank you for your time!
