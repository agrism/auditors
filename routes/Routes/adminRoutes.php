<?php

Route::get('admin/login/{secret}', [
	'as' => 'admin.loginAsClient',
	'uses' => 'Admin\\LoginAsUserController@login',
]);

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
	});

	Route::resource('invoices', 'InvoiceController');
	Route::resource('invoices', 'InvoiceController');

	Route::get(
		'export', ['as' => 'export', 'uses' => 'ExportController@export']
	);
	Route::get('npi', ['as' => 'npi', 'uses' => 'NpiController@create']);
	Route::post(
		'npi', ['as' => 'npi.handle', 'uses' => 'NpiController@handle']
	);
	Route::get('working-hours', ['as' => 'working-hours', 'uses' => 'WorkingHoursController@index']);
	Route::post(
		'working-hours', ['as' => 'working-hours.handle', 'uses' => 'WorkingHoursController@handle']
	);

	Route::get(
		'prepare-login-as-user/{id}', ['as' => 'prepare-login-as-user', 'uses' => 'LoginAsUserController@prepareLogin']
	);

	Route::get(
		'test', function () {
		$model = App\Test::where('PartnerName', 'LIKE', 'a%')->get();

//        dd(App\Test::find(5));

		dd($model);
	});

	Route::get('import-public-holidays', function () {

		$ch = curl_init('https://date.nager.at/api/v2/PublicHolidays/2023/LV');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		$output = curl_exec($ch);
		$output = json_decode($output);

		// close curl resource to free up system resources
		curl_close($ch);

		foreach ($output as $date) {
			$d = \App\Calendar::where('date', $date->date)->first();

			if (!$d) {
				$d = new \App\Calendar;
				$d->date = $date->date;
			}

			$d->name_local = $date->localName;
			$d->name = $date->name;
			$d->type = $date->type == 'Public' ? 'holiday' : 'regular';
			$d->working_hours = 0;

			$d->save();
		}
	});
});
