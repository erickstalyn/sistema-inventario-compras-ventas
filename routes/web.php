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

    //RUTAS PARA FUNCIONES COMUNES
    Route::get('/producto/getProductoFiltrado', 'ProductoController@getProductoFiltrado');
    Route::get('/persona/getPersona', 'PersonaController@getPersona');
    Route::get('/centro/selectAlmacen', 'CentroController@selectAlmacen');

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
        Route::get('/material/selectMaterial', 'MaterialController@selectMaterial'); 

        //RUTAS PARA PRODUCCIONES
        Route::get('/produccion', 'ProduccionController@listar'); //métodos generales
        Route::post('/produccion/agregar', 'ProduccionController@agregar');
        
        // Route::get('/produccion/selectAlmacen', 'ProduccionController@selectAlmacen'); //metodos secundarios
        Route::put('/produccion/finalizar', 'ProduccionController@finalizar'); //metodos secundarios

        //RUTAS PARA SUPERPRODUCTO
        Route::get('/superproducto', 'SuperProductoController@listar'); //metodos generales
        Route::post('/superproducto/agregar', 'SuperProductoController@agregar');
        Route::put('/superproducto/editar', 'SuperProductoController@editar');
        Route::get('/superproducto/selectSuperProducto', 'SuperProductoController@selectSuperProducto'); //metodos secundarios

        //RUTAS PARA PRODUCTO
        Route::get('/producto', 'ProductoController@listar'); //metodos generales
        Route::post('/producto/agregar', 'ProductoController@agregar');
        Route::put('/producto/editar', 'ProductoController@editar');
        Route::get('/producto/listaProducto', 'ProductoController@listaProducto');  //metodos secundarios

        //RUTAS PARA PRODUCTO MATERIAL
        Route::get('/productomaterial/agregar', 'ProductoMaterialController@agregar');
        Route::get('/productomaterial/listaProductoMaterial', 'ProductoMaterialController@listaProductoMaterial');

        //RUTAS PARA DATA
        Route::get('/data/selectSize', 'DataController@selectSize'); 
        Route::get('/data/selectColor', 'DataController@selectColor'); 
        
        //RUTAS PARA ABASTO
        Route::get('/abasto', 'AbastoController@listar'); //metodos generales
        Route::post('/abasto/agregar', 'AbastoController@agregar');
        Route::get('/data/selectUnidad', 'DataController@selectUnidad'); //metodos secundarios
        

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
