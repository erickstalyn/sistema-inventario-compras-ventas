<?php



// Route::get('/main', function () {
//     return view('contenidoPrincipal.contenidoPrincipal');
// })->name('main');

// Route::get('/', 'Auth\LoginController@showLoginForm');

// Route::post('/login', 'Auth\LoginController@login')->name('login');


// Route::get('/home', 'HomeController@index')->name('home');

//Route::get('', 'AdminController@index');

Route::get('/login', 'Seguridad\LoginController@index')->name('login');
Route::post('/login', 'Seguridad\LoginController@login')->name('login_post');
Route::get('/logout', 'Seguridad\LoginController@logout')->name('logout');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/main', function () {
        // con ello muestras toda la informacion del usuario logueado
        // dd(auth());
        return view('contenidoPrincipal.contenidoPrincipal');
    })->name('main');

    // Route::get('/home', 'HomeController@index')->name('home');

});
