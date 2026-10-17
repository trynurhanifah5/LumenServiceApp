<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
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

// 1. Endpoint Posts (CRUD lengkap menggunakan PostsController)
$router->get('posts', 'PostsController@index');
$router->post('posts', 'PostsController@store');
$router->get('posts/{id}', 'PostsController@show');
$router->put('posts/{id}', 'PostsController@update');
$router->delete('posts/{id}', 'PostsController@destroy');

// 2. Endpoint GET /users (Menggunakan data dummy)
$router->get('/users', function () use ($users) {
    return response()->json($users);
});

// 3. Endpoint GET /users/{userId} (Menggunakan data dummy berdasarkan ID)
$router->get('/users/{userId}', function ($userId) use ($users) {
    // Cari user berdasarkan ID
    $filteredUser = null;
    foreach ($users as $user) {
        if ($user['id'] == $userId) {
            $filteredUser = $user;
            break;
        }
    }

    // Jika user ditemukan, kembalikan data; jika tidak, berikan pesan error 404
    if ($filteredUser) {
        return response()->json($filteredUser);
    } else {
        return response()->json([
            "message" => "User tidak ditemukan"
        ], 404);
    }
});