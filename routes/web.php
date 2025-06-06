<?php

App::setLocale('es');

Route::group(['prefix' => 'webhooks'], function () {
    Route::post('/', 'App\HomeController@webhooks')->name('webhooks');
    Route::get('/auth', 'App\HomeController@webhooks_auth')->name('webhooks.auth');
    Route::get('/auth/platform', 'App\HomeController@webhooks_auth_platform')->name('webhooks.auth_platform');
    Route::get('/auth/generateToken', 'App\HomeController@webhooks_auth_generateToken')->name('webhooks.auth_generateToken');

    Route::post('/tiktok', 'App\HomeController@tiktok')->name('webhooks.tiktok');
    Route::post('/make', 'App\HomeController@make')->name('webhooks.make');
    Route::get('/tiktokads', 'App\HomeController@tiktokads')->name('webhooks.tiktokads');
    Route::post('/maketiktokads', 'App\HomeController@maketiktokads')->name('webhooks.maketiktokads');
});

Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/', 'Auth\HomeController@index')->name('user.home');

    Route::group(['roles' => ['Administrador', 'Asesor', 'Perdidos', 'Reingresos', 'Cajero', 'Provincia']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::post('/', 'Auth\HomeController@index')->name('user.home.post');
            Route::group(['prefix' => 'cliente'], function () {
                Route::get('/partialViewExport', 'Auth\ClienteController@partialViewExport')->name('user.client.partialViewExport');
                Route::get('/exportExcel/{fechaInicio}/{fechaFinal}/{estado}/{vendedor}/{modalidad}/{carrera}/{turno}', 'Auth\ClienteController@exportExcel')->name('user.client.exportExcel');
                Route::get('/notifications', 'Auth\ClienteController@notifications')->name('user.client.notifications');
                Route::get('/notifications-tracking', 'Auth\ClienteController@notificationsTracking')->name('user.client.notifications-tracking');
                Route::get('/see-observation/{id}', 'Auth\ClienteController@seeObservation')->name('user.client.see-observation');
                Route::get('/see-observation-additional/{id}', 'Auth\ClienteController@seeObservationAdditional')->name('user.client.see-observation-additional');
                Route::post('/store-see-observation', 'Auth\ClienteController@storeSeeObservation')->name('user.client.store-see-observation');
                Route::post('/store-see-observation-additional', 'Auth\ClienteController@storeSeeObservationAdditional')->name('user.client.store-see-observation-additional');
            });
        });
    });

    Route::group(['roles' => ['Administrador', 'Asesor', 'Perdidos', 'Reingresos', 'Provincia']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::group(['prefix' => 'cliente'], function () {
                Route::get('/partialView', 'Auth\ClienteController@partialView')->name('user.client.create');
                Route::get('/partialViewSeguimiento/{id}', 'Auth\ClienteController@partialViewSeguimiento')->name('user.client.createSeguimiento');
                Route::get('/partialViewMatriculado/{id}', 'Auth\ClienteController@partialViewMatriculado')->name('user.client.updateMatriculado');
                Route::get('/search_course/{id}', 'Auth\ClienteController@search_course')->name('user.client.search_course');

                Route::post('/store', 'Auth\ClienteController@store')->name('user.client.store');
                Route::post('/updateDatosContacto', 'Auth\ClienteController@updateDatosContacto')->name('user.client.updateDatosContacto');
                Route::post('/updateDatosCliente', 'Auth\ClienteController@updateDatosCliente')->name('user.client.updateDatosCliente');
                Route::post('/uploadBoxImages', 'Auth\ClienteController@uploadBoxImages')->name('user.client.uploadBoxImages');
                Route::post('/uploadAdditionalBoxImages', 'Auth\ClienteController@uploadAdditionalBoxImages')->name('user.client.uploadAdditionalBoxImages');
                Route::post('/getDataClient', 'Auth\ClienteController@getDataClient')->name('user.client.getDataClient');
                Route::post('/storeSeguimiento', 'Auth\ClienteController@storeSeguimiento')->name('user.client.storeSeguimiento');
                Route::post('/storeSeguimientoAdicional', 'Auth\ClienteController@storeSeguimientoAdicional')->name('user.client.storeSeguimientoAdicional');
                Route::post('/list_filter_seguimiento', 'Auth\ClienteController@list_filter_seguimiento')->name('user.client.list_filter');
                Route::post('/list_filter_seguimiento_adicional', 'Auth\ClienteController@list_filter_seguimiento_adicional')->name('user.client.list_filter_seguimiento_adicional');
                Route::post('/updateMatriculado', 'Auth\ClienteController@updateMatriculado')->name('user.client.updateMatriculado');
            });
        });
    });

    /*  RUTA API DNI */
    Route::get('/buscar_reniec/{data}', 'Auth\ClienteController@consultar_reniec')->name('buscar_reniec');

    Route::group(['prefix' => 'reporte', 'roles' => ['Administrador', 'Asesor', 'Perdidos']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/', 'Auth\ReporteController@index')->name('user.reporte');
            Route::post('/', 'Auth\ReporteController@filtro')->name('user.reporte.filtro');

            Route::get('/reporte-admin', 'Auth\ReporteController@reportAdmin')->name('user.reporte-admin');
            Route::get('/edit-client', 'Auth\ReporteController@editClient')->name('user.edit-client');
            Route::get('/edit-client-unit/{id}', 'Auth\ReporteController@editClientUnit')->name('user.edit-client-unit');
            Route::get('/edit-client-adicional-unit/{id}', 'Auth\ReporteController@editAdicionalClientUnit')->name('user.edit-client-adicional-unit');
            Route::get('/store-edit-client-unit', 'Auth\ReporteController@storeEditClientUnit')->name('user.store-edit-client-unit');
            Route::get('/store-edit-client-adicional-unit', 'Auth\ReporteController@storeEditClientAdicionalUnit')->name('user.store-edit-client-adicional-unit');
            Route::post('/store-search-client', 'Auth\ReporteController@storeSearchClient')->name('user.store-search-client');
            Route::post('/store-reporte-admin', 'Auth\ReporteController@storeReportAdmin')->name('user.store-reporte-admin');

            Route::get('/vendedores', 'Auth\ReporteController@vendedores')->name('user.reporte.vendedores');
            Route::post('/filtro_vendedores', 'Auth\ReporteController@filtro_vendedores')->name('user.reporte.filtro_vendedores');
        });
    });



    Route::get('/seguimiento', 'Auth\SeguimientoController@index')->name('seguimiento.index');
    Route::get('/seguimiento/usuarios/', 'Auth\SeguimientoController@usuarios')->name('seguimiento.usuarios');


    Route::group(['prefix' => 'cliente'], function () {
        Route::post('/store', 'Auth\ClienteController@store')->name('user.client.store');
    });

    Route::group(['prefix' => 'distrito'], function () {
        Route::get('/filtroDistrito/{id}', 'Auth\DistritoController@filtroDistrito')->name('user.filtroDistrito');
    });

    Route::group(['prefix' => 'cliente', 'roles' => ['Call']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/', 'Auth\ClienteController@createView')->name('user.client.createView');
        });
    });

    Route::group(['prefix' => 'cliente', 'roles' => ['Administrador']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/partialViewImport', 'Auth\ClienteController@partialViewImport')->name('user.client.partialViewImport');
            Route::post('/importExcel', 'Auth\ClienteController@importExcel')->name('user.client.importExcel');
            Route::get('/resumenDiario', 'Auth\ClienteController@resumenDiario')->name('user.client.resumenDiario');
        });
        Route::post('/delete', 'Auth\ClienteController@delete')->name('user.client.delete');
    });

    Route::group(['prefix' => 'usuario', 'roles' => ['Administrador']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/', 'Auth\UsuarioController@index')->name('user.user');
            Route::get('/list_all', 'Auth\UsuarioController@list_all')->name('user.user.list_all');
            Route::get('/partialView/{id}', 'Auth\UsuarioController@partialView')->name('user.user.create');
            Route::post('/store', 'Auth\UsuarioController@store')->name('user.user.store');
            Route::post('/delete', 'Auth\UsuarioController@delete')->name('user.user.delete');
            Route::post('/reasignar', 'Auth\UsuarioController@reasignar')->name('user.user.reasignar');
        });
    });

    Route::group(['prefix' => 'modalidad', 'roles' => ['Administrador']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/', 'Auth\ModalidadController@index')->name('user.modalidad');
            Route::get('/list_all', 'Auth\ModalidadController@list_all')->name('user.modalidad.list_all');
            Route::get('/partialView/{id}', 'Auth\ModalidadController@partialView')->name('user.modalidad.create');
            Route::post('/store', 'Auth\ModalidadController@store')->name('user.modalidad.store');
            Route::post('/delete', 'Auth\ModalidadController@delete')->name('user.modalidad.delete');
        });
    });

    Route::group(['prefix' => 'curso', 'roles' => ['Administrador']], function () {
        Route::get('/filtroCurso/{id}', 'Auth\CarreraController@filtroCurso')->name('user.filtroCarrera');
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/', 'Auth\CarreraController@index')->name('user.carrera');
            Route::get('/list_all', 'Auth\CarreraController@list_all')->name('user.carrera.list_all');
            Route::get('/partialView/{id}', 'Auth\CarreraController@partialView')->name('user.carrera.create');
            Route::post('/store', 'Auth\CarreraController@store')->name('user.carrera.store');
            Route::post('/delete', 'Auth\CarreraController@delete')->name('user.carrera.delete');
        });
    });

    Route::group(['prefix' => 'reportehistorial', 'roles' => ['Administrador']], function () {
        Route::middleware('auth.route.access')->group(function () {
            Route::get('/', 'Auth\HistorialController@index')->name('user.reportehistorial');
            Route::get('/list_all', 'Auth\HistorialController@list_all')->name('user.reportehistorial.list_all');
        });
    });

    Route::group(['prefix' => 'estado', 'roles' => ['Administrador']], function () {
        Route::get('/filtroEstado/{id}', 'Auth\EstadoController@filtroEstado')->name('user.filtroEstado');
    });

    Route::group(['prefix' => 'local', 'roles' => ['Administrador']], function () {
        Route::get('/filtroLocal/{id}', 'Auth\LocalController@filtroLocal')->name('user.filtroLocal');
    });

    Route::group(['prefix' => 'horario', 'roles' => ['Administrador']], function () {
        Route::get('/filtroHorario/{turno_id}/{carrera_id}/{tipo}', 'Auth\HorarioController@filtroHorario')->name('user.filtroHorario');
        Route::get('/filtroCarrera/{modalidad_id}/', 'Auth\HorarioController@filtroCarrera')->name('user.filtroCarrera');
    });

    Route::get('/changePassword/partialView', 'Auth\LoginController@partialView_change_password')->name('auth.login.partialView_change_password');
    Route::post('/changePassword', 'Auth\LoginController@change_password')->name('auth.login.change_password');
});

// Login Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register.post');
