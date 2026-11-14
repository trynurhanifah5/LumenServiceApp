<?php

use App\Http\Controllers\PostsController;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

// Data dummy pengguna
$users = [
    [
        "id" => 1,
        "name" => "Sumatrana",
        "email" => "sumatrana@gmail.com",
        "address" => "Padang",
        "gender" => "Laki-laki"
    ],
    [
        "id" => 2,
        "name" => "Jawarianto",
        "email" => "jawarianto@gmail.com",
        "address" => "Cimahi",
        "gender" => "Laki-laki"
    ],
    [
        "id" => 3,
        "name" => "Kalimantanio",
        "email" => "kalimantanio@gmail.com",
        "address" => "Samarinda",
        "gender" => "Laki-laki"
    ],
    [
        "id" => 4,
        "name" => "Sulawesiani",
        "email" => "sulawesiani@gmail.com",
        "address" => "Makasar",
        "gender" => "Perempuan"
    ],
    [
        "id" => 5,
        "name" => "Papuani",
        "email" => "papuani@gmail.com",
        "address" => "Jayapura",
        "gender" => "Perempuan"
    ]
];

// 0. Endpoint Root / (Cek Status Service)
$router->get('/', function () {
    return response()->json([
        "service_name" => "PHP Service App",
        "status" => "Running"
    ]);
});

// 1. Endpoint Posts (RESTful API CRUD menggunakan PostsController khusus Lumen)
$router->get('/posts', 'PostsController@index');
$router->post('/posts', 'PostsController@store');
$router->get('/posts/{id}', 'PostsController@show');
$router->put('/posts/{id}', 'PostsController@update');
$router->delete('/posts/{id}', 'PostsController@destroy');

// 2. Endpoint GET /users (Menggunakan data dummy)
$router->get('/users', function () use ($users) {
    return response()->json($users);
});

// 3. Endpoint GET /users/{userId} (Menggunakan data dummy berdasarkan ID)
$router->get('/users/{userId}', function ($userId) use ($users) {
    $filteredUser = null;
    foreach ($users as $user) {
        if ($user['id'] == $userId) {
            $filteredUser = $user;
            break;
        }
    }

    if ($filteredUser) {
        return response()->json($filteredUser);
    } else {
        return response()->json([
            "message" => "User tidak ditemukan"
        ], 404);
    }
});

// authentication
$router->group(['prefix' => 'auth'], function () use ($router) {
    // Matches "/auth/register"
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});

// Post
Route::group(['middleware' => ['auth']], function ($router) {
    $router->get('/posts', 'PostsController@index');
    $router->post('/posts', 'PostsController@store');
    $router->get('/posts/{id}', 'PostsController@update');
    $router->put('/posts/{id}', 'PostsController@update');
    $router->delete('/posts/{id}', 'PostsController@destroy');
});