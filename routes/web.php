<?php



// Route::get('/main', function () {
//     return view('contenidoPrincipal.contenidoPrincipal');
// })->name('main');

// Route::get('/', 'Auth\LoginController@showLoginForm');

// Route::post('/login', 'Auth\LoginController@login')->name('login');


// Route::get('/home', 'HomeController@index')->name('home');

//Route::get('', 'AdminController@index');

//Pruebas
//Route::get('/mostrar', 'PruebasController@mostrar')->name('mostrar');



Route::group(['middleware' => ['guest']], function (){
    // Route::get('/login', 'Seguridad\LoginController@index')->name('login');
    Route::get('/', 'Seguridad\LoginController@index')->name('login');

    Route::post('/login', 'Seguridad\LoginController@login')->name('login_post');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', 'Seguridad\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenidoPrincipal.contenidoPrincipal');
    })->name('main');

    Route::group(['middleware' => ['Administrador']], function (){
        
        //Rutas para USUARIO
        Route::get('/usuario', 'UsuarioController@listar'); //metodos generales
        Route::post('/usuario/agregar', 'UsuarioController@agregar');
        Route::put('/usuario/editar', 'UsuarioController@editar');
        Route::put('/usuario/activar', 'UsuarioController@activar');
        Route::put('/usuario/desactivar', 'UsuarioController@desactivar');
        Route::get('/usuario/comprobar', 'UsuarioController@comprobar'); //metodos secundarios
        Route::get('/usuario/selectRol', 'UsuarioController@selectRol');
        
        //RUTAS PARA SUPERPRODUCTO
        Route::get('/superproducto', 'SuperProductoController@listar'); //metodos generales
        Route::post('/superproducto/agregar', 'SuperProductoController@agregar');
        Route::put('/superproducto/editar', 'SuperProductoController@editar');
        Route::put('/superproducto/setEstado', 'SuperProductoController@setEstado');

        //RUTAS PARA PRODUCTO
        Route::get('/producto', 'ProductoController@listar'); //metodos generales
        Route::post('/producto/agregar', 'ProductoController@agregar');
        Route::put('/producto/editar', 'ProductoController@editar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::get('/producto/selectCategoria', 'ProductoController@selectCategoria'); //metodos secundarios
    });
    Route::group(['middleware' => ['Puesto']], function (){

    });
    Route::group(['middleware' => ['Almacen']], function (){

    });
    

});
