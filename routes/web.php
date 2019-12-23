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
    Route::get('/centro/listProductos', 'CentroController@listProductos'); //metodos generales
    //RUTAS DE ENVIOS
    Route::get('/envioRecibido', 'EnvioController@listarEnvioRecibido'); //metodos generales
    Route::get('/envioRealizado', 'EnvioController@listarEnvioRealizado');
    Route::post('/envioRealizado/agregar', 'EnvioController@agregar');
    Route::put('/envioRealizado/reenviar', 'EnvioController@reenviar');//metodo secundario
    Route::put('/envioRecibido/setEstado', 'EnvioController@setEstado'); //metodos secundarios

    Route::get('/abasto/getDetalles', 'AbastoController@getDetalles');
    Route::get('/envioRealizado/getDetalles', 'EnvioController@getDetalles');

    //RUTAS PARA VENTAS
    Route::get('/venta', 'VentaController@listar');
    Route::post('/venta/agregar', 'VentaController@agregar');

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
        Route::post('/productomaterial/agregar', 'ProductoMaterialController@agregar');
        Route::get('/productomaterial/listaProductoMaterial', 'ProductoMaterialController@listaProductoMaterial');

        //RUTAS PARA DATA
        Route::get('/data/selectSize', 'DataController@selectSize'); 
        Route::get('/data/selectColor', 'DataController@selectColor'); 
        
        //RUTAS PARA ABASTO
        Route::get('/abasto', 'AbastoController@listar'); //metodos generales
        Route::post('/abasto/agregar', 'AbastoController@agregar');
        Route::get('/abasto/getPagos', 'AbastoController@getPagos');
        Route::put('/abasto/anular', 'AbastoController@anular');
        Route::get('/data/selectUnidad', 'DataController@selectUnidad'); //metodos secundarios

        Route::get('/centro/selectCentro', 'CentroController@selectCentro');

        //RUTAS DE PAGOS
        Route::post('/pago/agregar', 'PagoController@agregar');
        //RUTAS DETALLES DE PRODUCTOS
        Route::put('/detalle_producto/editar', 'Detalle_productoController@editar');
        

    });
    Route::group(['middleware' => ['Puesto']], function (){
        
        //RUTAS PARA LOS DETALLE_PRODUCTO
        Route::get('/detalle_producto/listProductos', 'Detalle_productoController@listProductos');
        
    });
    Route::group(['middleware' => ['Almacen']], function (){
        
        // RUTAS PARA PRODUCCIONES
        Route::get('/produccion', 'ProduccionController@listar'); //métodos generales
        Route::post('/produccion/agregar', 'ProduccionController@agregar');
        Route::put('/produccion/editar', 'ProduccionController@editar');
        Route::put('/produccion/anular', 'ProduccionController@anular');
        Route::get('/produccion/getProductoFiltrado', 'ProduccionController@getProductoFiltrado'); //metodos secundarios
        Route::get('/produccion/getDetalles', 'ProduccionController@getDetalles');
        Route::put('/produccion/finalizar', 'ProduccionController@finalizar'); //metodos secundarios

        Route::get('/detalle_producto/getDetalle_productoFiltrado', 'Detalle_productoController@getDetalle_productoFiltrado');
        Route::get('/centro/selectCentroEnvio', 'CentroController@selectCentroEnvio');

        //Rutas de ENVIOS REALIZADOS
        // Route::get('/envioRealizado/getDetalles', 'EnvioController@getDetalles');
        Route::put('/envioRealizado/anular', 'EnvioController@anular');

        // Route::get('/abasto/getDetalles', 'AbastoController@getDetalles');
    });

});
