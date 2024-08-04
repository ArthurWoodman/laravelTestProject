CREATE DATABASE `test_laravel` /*!40100 DEFAULT CHARACTER SET latin1 */ /*!80016 DEFAULT ENCRYPTION='N' */;

$ php artisan migrate:fresh
// https://filamentphp.com/docs/3.x/panels/installation
$ composer require filament/filament:"^3.2" -W
$ php artisan filament:install --panels
$ php artisan make:filament-user
// user ezepovarthur@gmail.com | Art485050  || http://localhost:8000/admin
$ php artisan make:model Article -m
$ php artisan make:filament-resource Article

// running server+npm
$ php artisan serve
$ npm run dev

$ php artisan migrate:rollback
$ php artisan migrate

$ php artisan make:controller ArticleController --invokable

$ composer require laravel/breeze --
$ php artisan breeze:install

$ php artisan migrate:fresh --seed // creates test@test.com | qwerty0

http://localhost:8000/register  // for creating a new user

$ npm i react-router-dom
$ npm install react-redux
$ npm install @reduxjs/toolkit

API:
http://localhost:8000/api/v1/articles  // with pagination
http://localhost:8000/api/v1/articles/1

$ php artisan make:request StoreArticleRequest

CURL:
(PUBLIC) $ curl -v -L -X POST 'http://localhost:8000/get-tokens' -H 'Accept:application/json' -d "email=test@test.com&password=qwerty0"
{
    "adminToken":"1|8XHhGPFuroFimHCFQtqHBU9GjzqCxcb6ekmwpcKq3da2daa9",
    "updateToken":"2|519zKfKM78JXSAdF92j5wJHYpLtPoEmraTPb4Pu31bb02a4f",
    "basicToken":"3|oBUj7bU7cLxQaqoGrDO2vLL4TbUc8VBsOGbicMNP374c069b"
}
(PUBLIC) $ curl -v -L -X GET 'http://localhost:8000/api/v1/articles' -H 'Accept:application/json'
(PUBLIC) $ curl -v -L -X GET 'http://localhost:8000/api/v1/articles/1' -H 'Accept:application/json'
(AUTH) $ curl -v -L -X POST 'http://localhost:8000/api/v1/articles' -H 'Accept:application/json' -H 'Authorization: Bearer 2|qDEM5ahgJClVxd3lkr2E4G9FzfiS8LTSVgQn8ilabeb5e809' -d "id=51&title=00009999Test123&body=test12333 123&publication_date=2024-01-01&slug=test-123"
(AUTH) $ curl -v -L -X PUT 'http://localhost:8000/api/v1/articles/53' -H 'Accept:application/json' -H 'Authorization: Bearer 5|1YCQpE2fnYfo0IkJtkXHETeg6rdNwkK5FG7ipU7r6ae3f1ec' -d "body=test12333 16767676776 23&publication_date=2024-05-05&slug=test-123"
(AUTH) $ curl -v -L -X PATCH 'http://localhost:8000/api/v1/articles/53' -H 'Accept:application/json' -H 'Authorization: Bearer 5|1YCQpE2fnYfo0IkJtkXHETeg6rdNwkK5FG7ipU7r6ae3f1ec' -d "body=test 100 101 102&publication_date=2024-11-11&slug=test-123456"

curl -v -L -X GET 'http://localhost:8000/api/v1/articles' -H 'Accept:application/json' -H 'Authorization: Bearer 6|OSiCqn12iIK1SObZs2MnQnJQFgjZfMserRyHuvOiee85c408'






<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
