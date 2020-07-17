<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


	Route::get('/declaration/pdf/{id}', 'DeclarationController@pdf');

Route::middleware('client')->group(function () {
	Route::post('/user_invitation', 'ApiUserController@setSecretInvitation');
	Route::post('/solicitud_industrial', 'ApiUserController@solicitudIndustrial');

	Route::post('/actualizar_empresa','CompanyController@update'); 
	Route::post('/actualizar_establecimiento','EstablishmentController@update');
	Route::post('/actualizar_usuario','UserControllerwaste@update');
});

Route::middleware('auth:api')->group(function () {
	Route::get('/set_user', 'ApiUserController@setUserVue');
	Route::get('/requisitions', 'RequisitionController@index');
	Route::post('/requisition/approve', 'RequisitionController@approve');
    Route::post('/requisition/reject', 'RequisitionController@reject');

    Route::get('/user', 'UserController@data');
    
    Route::get('/establishment', 'EstablishmentController@data');
    Route::get('/establishment/all/{type}', 'EstablishmentController@AllData');

    Route::get('/establishment/forid/{id}', 'EstablishmentController@forid');
    Route::get('/establishments/{company_id}', 'EstablishmentController@index');
    Route::post('/establishments/search', 'EstablishmentController@search');
    Route::get('/establishment/export', 'EstablishmentController@export');
    

    Route::get('/company', 'CompanyController@data');
    Route::get('/companies', 'CompanyController@index');
    Route::get('/companies/search', 'CompanyController@search');
    Route::get('/company/forid/{id}', 'CompanyController@forid');
    Route::get('/company/search_sii/{rut}', 'CompanyController@searchSii');



	Route::get('/lerchapter', 'LerChapterController@data');
	Route::get('/lerchapter/forid/{id}', 'LerChapterController@forid');
	

	Route::get('/lersubchapter/{lerchapter_id}', 'LerSubChapterController@data');
	Route::get('/lerwaste/{lersubchapter_id}', 'LerWasteController@data');
	Route::get('/lerwaste/forid/{id}', 'LerWasteController@forid');
		
	Route::get('/managetype/forid/{id}', 'ManageTypeController@forid');
	Route::get('/processtype/forid/{id}', 'ProcessTypeController@forid');


	Route::get('/managetype', 'ManageTypeController@data');
	Route::get('/processtype', 'ProcessTypeController@data');

	Route::get('/recolectiontype', 'RecolectionTypeController@data');
	Route::get('/unit', 'UnitController@data');
	Route::get('/unit/forid/{id}', 'UnitController@forid');

	Route::get('/carriers', 'CarrierController@data');



	Route::get('/carriers/notregistered', 'CarrierController@notRegistered');
	Route::get('/carrier/forid/{id}', 'CarrierController@forid');
	Route::get('/carriers/search', 'CarrierController@search');

	Route::post('/carrier/savenotregistered', 'CarrierController@saveNotRegistered');
	Route::get('/carrier/forid/{id}', 'CarrierController@forid');
	Route::get('/carriers/search', 'CarrierController@search');




	Route::get('/carrier/forid/{id}', 'CarrierController@forid');
	Route::get('/carriers/search', 'CarrierController@search');

	Route::get('/vehicletype', 'VehicleTypeController@data');
	Route::get('/vehicle/{carrier_id}', 'VehicleController@data');

	Route::get('/countries', 'CountryController@index');
	Route::get('/country/forid/{id}', 'CountryController@forid');

	Route::get('/subdere_cost/forid/{declaration_id}', 'SubdereCostController@forid');


    Route::get('/declarations', 'DeclarationController@index');
    Route::get('/declarations/admin', 'DeclarationController@indexAdmin');

    Route::get('/declarations/forreceiver/{receiver_id}', 'DeclarationController@forreceiver');
    Route::get('/declarations/forreceivergennn/{receiver_id}', 'DeclarationController@forreceiverGenNN');



    Route::get('/declaration/find/{declaration_id}', 'DeclarationController@find');

    Route::post('/declaration/create', 'DeclarationController@create');
	Route::post('/declaration/store', 'DeclarationController@store');
	Route::post('/declaration/sinmovimiento', 'DeclarationController@sinMovimento');
	Route::post('/declaration/delete/{declaration_id}', 'DeclarationController@delete');
	Route::post('/declaration/savetraceability','DeclarationController@savetraceability');

	Route::post('/declaration/changestatus','DeclarationController@changeStatus');

	Route::post('/declaration/upload','DeclarationController@upload');
	Route::get('/declaration/export', 'DeclarationController@export');


	Route::get('/notification/mail','UserController@sendMail');


	Route::post('/declaration/enviar/{declaration_id}', 'DeclarationController@changeStatusEnviada');

    Route::get('/waste_details/{declaration_id}', 'WasteDetailController@index');
    Route::post('/waste_details/forreceiver', 'WasteDetailController@forreceiver');
    Route::post('/waste_detail/updatediscrepancy','WasteDetailController@updateDiscrepancy');

    Route::get('/vehicle', 'VehicleController@index');
	Route::post('/vehicle/store', 'VehicleController@store');
	Route::post('/vehicle/delete/{id}', 'VehicleController@delete');

    Route::get('/carrier', 'CarrierController@index');
	Route::post('/carrier/store', 'CarrierController@store');
	Route::post('/carrier/delete/{id}', 'CarrierController@delete');

    Route::get('/lerchapter', 'LerChapterController@index');
	Route::post('/lerchapter/store', 'LerChapterController@store');
	Route::post('/lerchapter/delete/{id}', 'LerChapterController@delete');

    Route::get('/lersubchapter', 'LerSubChapterController@index');
	Route::post('/lersubchapter/store', 'LerSubChapterController@store');
	Route::post('/lersubchapter/delete/{id}', 'LerSubChapterController@delete');

    Route::get('/lerwaste', 'LerWasteController@index');
	Route::post('/lerwaste/store', 'LerWasteController@store');
	Route::post('/lerwaste/delete/{id}', 'LerWasteController@delete');

    Route::get('/wastetype', 'WasteTypeController@index');
	Route::post('/wastetype/data', 'WasteTypeController@data');

    Route::get('/activeline', 'ActiveLineController@index');
	Route::post('/activeline/data', 'ActiveLineController@data');

	Route::get('/period', 'PeriodController@index');
	Route::post('/period/store', 'PeriodController@store');
	Route::post('/period/delete/{id}', 'PeriodController@delete');

 	Route::get('/mail/sendrejectdeclaration/{declaration_id}', 'MailController@emailRejectedDeclaration');

});
