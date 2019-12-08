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
    
    //RUTAS PARA PRODUCTO
    Route::get('/producto', 'ProductoController@listar'); //metodos generales
    Route::post('/producto/agregar', 'ProductoController@agregar');
    Route::put('/producto/editar', 'ProductoController@editar');
    Route::put('/producto/setEstado', 'ProductoController@setEstado');
    Route::get('/producto/selectSuperProducto', 'ProductoController@selectSuperProducto'); //metodos secundarios
    Route::get('/producto/selectSize', 'ProductoController@selectSize'); 
    Route::get('/producto/selectColor', 'ProductoController@selectColor'); 
    Route::get('/producto/selectMaterial', 'ProductoController@selectMaterial'); 

    //RUTAS PARA FUNCIONES COMUNES
    Route::get('/libreria/getProductoFiltrado', 'LibreriaController@getProductoFiltrado');
    Route::get('/libreria/getPersona', 'LibreriaController@getPersona');


    Route::group(['middleware' => ['Administrador']], function (){
        
        //Rutas para USUARIO
        Route::get('/usuario', 'UsuarioController@listar'); //metodos generales
        Route::post('/usuario/agregar', 'UsuarioController@agregar');
        Route::put('/usuario/editar', 'UsuarioController@editar');
        Route::put('/usuario/activar', 'UsuarioController@activar');
        Route::put('/usuario/desactivar', 'UsuarioController@desactivar');
        Route::get('/usuario/comprobar', 'UsuarioController@comprobar'); //metodos secundarios
        Route::get('/usuario/selectRol', 'UsuarioController@selectRol');
        
        //RUTAS PARA MATERIALES
        Route::get('/material', 'MaterialController@listar'); //métodos generales
        Route::post('/material/agregar', 'MaterialController@agregar');
        Route::put('/material/editar', 'MaterialController@editar');
        Route::put('/material/activar', 'MaterialController@activar');
        Route::put('/material/desactivar', 'MaterialController@desactivar');
        Route::get('/material/selectUnidad', 'MaterialController@selectUnidad'); //metodos secundarios

        //RUTAS PARA PRODUCCIONES
        Route::get('/produccion', 'ProduccionController@listar'); //métodos generales
        Route::post('/produccion/agregar', 'ProduccionController@agregar');
        // Route::get('/produccion/getProductoFiltrado', 'ProduccionController@getProductoFiltrado'); //metodos secundarios
        Route::get('/produccion/selectAlmacen', 'ProduccionController@selectAlmacen'); //metodos secundarios
        Route::put('/produccion/finalizar', 'ProduccionController@finalizar'); //metodos secundarios

        //RUTAS PARA SUPERPRODUCTO
        Route::get('/superproducto', 'SuperProductoController@listar'); //metodos generales
        Route::post('/superproducto/agregar', 'SuperProductoController@agregar');
        Route::put('/superproducto/editar', 'SuperProductoController@editar');
        Route::put('/superproducto/setEstado', 'SuperProductoController@setEstado');

        //RUTAS PARA PRODUCTO
        Route::get('/producto', 'ProductoController@listar'); //metodos generales
        Route::post('/producto/agregar', 'ProductoController@agregar');
        Route::put('/producto/editar', 'ProductoController@editar');
        Route::put('/producto/setEstado', 'ProductoController@setEstado');
        Route::get('/producto/selectSuperProducto', 'ProductoController@selectSuperProducto'); //metodos secundarios
        Route::get('/producto/selectSize', 'ProductoController@selectSize'); 
        Route::get('/producto/selectColor', 'ProductoController@selectColor'); 
        Route::get('/producto/selectMaterial', 'ProductoController@selectMaterial'); 

        //RUTAS PARA DATA
        Route::get('/data/selectSize', 'DataController@selectSize'); 
        Route::get('/data/selectColor', 'DataController@selectColor'); 
        
        //RUTAS PARA ABASTO
        Route::get('/abasto', 'AbastoController@listar'); //metodos generales
        

    });
    Route::group(['middleware' => ['Puesto']], function (){


    });
    Route::group(['middleware' => ['Almacen']], function (){

        // RUTAS PARA PRODUCCIONES
        Route::get('/produccion', 'ProduccionController@listar'); //métodos generales
        Route::post('/produccion/agregar', 'ProduccionController@agregar');
        Route::get('/produccion/getProductoFiltrado', 'ProduccionController@getProductoFiltrado'); //metodos secundarios
        Route::get('/produccion/selectAlmacen', 'ProduccionController@selectAlmacen'); //metodos secundarios
        Route::put('/produccion/finalizar', 'ProduccionController@finalizar'); //metodos secundarios

        
    });
    

});
