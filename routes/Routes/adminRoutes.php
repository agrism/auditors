<?php
Route::group(
	[
		'namespace' => 'Admin', 'middleware' => ['forAdmin'],
		'prefix' => 'admin', 'as' => 'admin.',
	], function () {


	Route::get("/", ['as' => 'home', 'uses' => 'CompanyController@index']);

	Route::resource('companies', 'CompanyController');


	Route::get(
		'companies/{companyId}/structuralunits', [
		'as' => 'company.structuralunits.index',
		'uses' => 'StructuralunitController@index',
	]
	);
	Route::get(
		'companies/{companyId}/structuralunits/create', [
		'as' => 'company.structuralunits.create',
		'uses' => 'StructuralunitController@create',
	]
	);
	Route::post(
		'companies/{companyId}/structuralunits/store', [
		'as' => 'company.structuralunits.store',
		'uses' => 'StructuralunitController@store',
	]
	);
	Route::get(
		'companies/{companyId}/structuralunits/edit/{id}', [
		'as' => 'company.structuralunits.edit',
		'uses' => 'StructuralunitController@edit',
	]
	);
	Route::put(
		'companies/{companyId}/structuralunits/update/{id}', [
		'as' => 'company.structuralunits.update',
		'uses' => 'StructuralunitController@update',
	]
	);
	Route::get(
		'companies/{companyId}/structuralunits/destroy/{id}', [
		'as' => 'company.structuralunits.destroy',
		'uses' => 'StructuralunitController@destroy',
	]
	);

	Route::resource(
		'users/companies', 'UserBindCompanyController',
		['names' => 'users.companies']
	);
	Route::resource(
		'companies/users', 'CompanyBindUserController',
		['names' => 'companies.users']
	);
//    Route::resource('companies/users', 'CompanyBindUserController');
	Route::resource('users', 'UserController');
	//

	Route::resource(
		'roles/permissions', 'RolesBindPermissionController',
		['names' => 'roles.permissions']
	);
	Route::resource(
		'permissions/roles', 'PermissionBindRoleController',
		['names' => 'permissions.roles']
	);

	Route::resource('roles', '\App\Http\Controllers\Admin\RoleController');
	Route::resource(
		'permissions', '\App\Http\Controllers\Admin\PermissionController'
	);
	Route::resource(
		'users', '\App\Http\Controllers\Admin\UserController',
		['only' => ['index', 'show']]
	);

	Route::group(
		['prefix' => 'users'], function () {
		Route::post(
			'assign-to-partner', [
			'as' => 'user.assignToPartner',
			'uses' => '\App\Http\Controllers\Admin\UserController@assignToPartner',
		]
		);

		Route::group(
			['prefix' => 'partners'], function () {
			Route::get(
				'set-roles', [
				'as' => 'users.assignrole',
				'uses' => 'UserController@assignRoleToUser',
			]
			);
			Route::get(
				'get-roles', [
				'as' => 'users.assignrole.save',
				'uses' => 'UserController@assignRoleToUserSave',
			]
			);

		}
		);
	}
	);

	Route::resource('invoices', 'InvoiceController');
	Route::resource('invoices', 'InvoiceController');

	Route::get(
		'export', ['as' => 'export', 'uses' => 'ExportController@export']
	);
	Route::get('npi', ['as' => 'npi', 'uses' => 'NpiController@create']);
	Route::post(
		'npi', ['as' => 'npi.handle', 'uses' => 'NpiController@handle']
	);


	Route::get(
		'test', function () {
		$model = App\Test::where('PartnerName', 'LIKE', 'a%')->get();

//        dd(App\Test::find(5));

		dd($model);
	}
	);
}
);
