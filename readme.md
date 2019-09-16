<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Challenge End Points

1. List all the Budget Request availables on DB:
- URL: /budget_requests/{email?}
- Method: get

2. Create a new Budget Request:
- URL: /budget_requests
- Method: post

3. Modify an existing Budget Request:
- URL: /budget_requests/{id}
- Method: put

4. Publish an existing Budget Request:
- URL: /budget_requests/publish/{id}
- Method: put

5. Discard an existing Budget Request:
- URL: /budget_requests/discard/{id}
- Method: put

6. Suggest a category based on Budget Request's description:
- URL: /budget_requests/suggest_category/{id}
- Method: get

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
