<?php
Route::group(['middleware' => ['guest']], function (){
    // Route::get('/login', 'Seguridad\LoginController@index')->name('login');
    Route::get('/', 'Seguridad\LoginController@index')->name('login');

    Route::post('/login', 'Seguridad\LoginController@login')->name('login_post');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', 'Seguridad\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('ContenidoPrincipal.contenidoPrincipal');
    })->name('main');

    //RUTAS PARA FUNCIONES COMUNES
    Route::get('/producto/getProductoFiltrado', 'ProductoController@getProductoFiltrado');
    Route::get('/persona/getPersona', 'PersonaController@getPersona');
    Route::get('/centro/listProductos', 'CentroController@listProductos'); //metodos generales
    Route::get('/centro/selectCentroEnvio', 'CentroController@selectCentroEnvio');
    //RUTAS DE ENVIOS
    Route::get('/envioRecibido', 'EnvioController@listarEnvioRecibido'); //metodos generales
    Route::get('/envioRealizado', 'EnvioController@listarEnvioRealizado');
    Route::post('/envioRealizado/agregar', 'EnvioController@agregar');
    Route::put('/envioRealizado/reenviar', 'EnvioController@reenviar');//metodo secundario
    Route::put('/envioRecibido/setEstado', 'EnvioController@setEstado'); //metodos secundarios

    Route::get('/abasto/getDetalles', 'AbastoController@getDetalles');
    Route::get('/envioRealizado/getDetalles', 'EnvioController@getDetalles');

    //RUTAS PARA LOS DETALLE_PRODUCTO
    Route::get('/detalle_producto/getDetalle_productoFiltrado', 'Detalle_productoController@getDetalle_productoFiltrado');
    Route::get('/detalle_producto/listProductos', 'Detalle_productoController@listProductos');
    
    //RUTAS PARA VENTAS
    Route::get('/venta', 'VentaController@listar');
    Route::get('/venta/getVentaWithAll', 'VentaController@getVentaWithAll');
    Route::post('/venta/agregar', 'VentaController@agregar');
    Route::put('/venta/editar', 'VentaController@editar');

    //RUTAS PARA DETALLES DE VENTAS
    Route::get('/detalle_venta/list', 'DetalleVentaController@list');
    Route::get('/detalle_venta/listVer', 'DetalleVentaController@listVer');
    Route::get('/detalle_venta/listEditar', 'DetalleVentaController@listEditar');

    //RUTAS PARA ABASTO DE PRODUCTO EXTERNOS
    Route::get('/abasto/list', 'AbastoController@list');
    Route::post('/abasto/pay', 'AbastoController@pay');

    //RUTAS PARA CAJA
    Route::get('/caja/state', 'CajaController@state');
    Route::get('/caja/get', 'CajaController@get');

    //RUTAS PARA PAGOS
    Route::get('/pago/listVenta', 'PagoController@listVenta');
    Route::post('/pago/agregar', 'PagoController@agregar');

    //RUTAS PARA VALES
    Route::get('/vale/get', 'ValeController@get');
    Route::get('/vale', 'ValeController@listar');

    //RUTAS PARA PRODUCCIONES
    Route::get('/produccion', 'ProduccionController@listar'); //métodos generales
    Route::get('/produccion/getDetalles', 'ProduccionController@getDetalles');
    //RUTA PARA NOTIFICACIONES
    Route::post('/notification/get', 'NotificationController@get');

    //RUTA PARA PDF
    Route::get('/venta/generatePdfSpecific', 'VentaController@generatePdfSpecific');

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

        //RUTAS DETALLES DE PRODUCTOS
        Route::put('/detalle_producto/editar', 'Detalle_productoController@editar');
        //RUTAS DE ESTADISTICAS
        Route::get('/estadistica/getEVentas', 'DashboardController@getEVentas');

        //RUTAS DE PDF
        Route::get('/material/generatePdf', 'MaterialController@generatePdf');
        Route::get('/superproducto/generatePdf', 'SuperProductoController@generatePdf');
        Route::get('/producto/generatePdf', 'ProductoController@generatePdf');
        Route::get('/abasto/generatePdf', 'AbastoController@generatePdf');
        Route::get('/abasto/generatePdfSpecific', 'AbastoController@generatePdfSpecific');

    });
    Route::group(['middleware' => ['Puesto']], function (){
        
        
    });
    Route::group(['middleware' => ['Almacen']], function (){
        
        // RUTAS PARA PRODUCCIONES
        Route::post('/produccion/agregar', 'ProduccionController@agregar');
        Route::put('/produccion/editar', 'ProduccionController@editar');
        Route::put('/produccion/anular', 'ProduccionController@anular');
        Route::get('/produccion/getProductoFiltrado', 'ProduccionController@getProductoFiltrado'); //metodos secundarios
        
        Route::put('/produccion/finalizar', 'ProduccionController@finalizar'); //metodos secundarios

        //Rutas de ENVIOS REALIZADOS
        Route::put('/envioRealizado/anular', 'EnvioController@anular');

        //Ruta de reparación de detalles de producto
        Route::put('/detalle_producto/reparar', 'Detalle_productoController@reparar');

    });

});
