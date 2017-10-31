# Frequently Asked Questions
This will add faq questions and answers to your laravel project.
The questions have a 'total views', 'total helpful' and 'total not helpful' counters.

## Installation
Update your project's `composer.json` file.

```bash
composer require bpocallaghan/faq
```

## Usage

Register the routes in the `routes/vendor.php` file.
- Website
```bash
Route::group(['prefix' => 'faq', 'namespace' => 'FAQ\Controllers\Website'], function () {
    Route::get('', 'FAQController@index');
    Route::post('/question/{faq}/{type?}', 'FAQController@incrementClick');
});
```
- Admin
```bash
Route::group(['namespace' => 'FAQ\Controllers\Admin'], function () {
    Route::resource('/faqs/categories', 'CategoriesController');
    Route::get('faqs/order', 'OrderController@index');
    Route::post('faqs/order', 'OrderController@updateOrder');
    Route::resource('/faqs', 'FAQsController');
});
```

## Commands
```bash
php artisan faq:publish
```
This will copy the `database/seeds` and `database/migrations` to your application.
Remember to add `$this->call(FAQTableSeeder::class);` in the `DatabaseSeeder.php`

```bash
php artisan faq:publish --files=all
```
This will copy the `model, views and controller` to their respective directories. 
Please note when you execute the above command. You need to update your `routes`.
- Website
```bash 
Route::get('/faq', 'FAQController@index');
Route::post('/faq/question/{faq}/{type?}', 'FAQController@incrementClick');
```
- Admin
```bash
Route::group(['namespace' => 'FAQ'], function () {
    Route::resource('/faqs/categories', 'CategoriesController');
    Route::get('faqs/order', 'OrderController@index');
    Route::post('faqs/order', 'OrderController@updateOrder');
    Route::resource('/faqs', 'FaqsController');
});
```

## Demo
Package is being used at [Laravel Admin Starter](https://github.com/bpocallaghan/laravel-admin-starter) project.

### TODO
- add the navigation seeder information (to create the navigation/urls)