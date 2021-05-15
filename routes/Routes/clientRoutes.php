<?php


Route::group(
    [
        'middleware' => ['forClient'],
        'prefix' => 'client',
        'as' => 'client.',
    ], function () {

    Route::get('invoice-list', [\App\Http\Controllers\Client\InvoiceListController::class, 'index'])->middleware(['forClient']);



    Route::group([
        'namespace' => 'App\\Http\\Controllers\\Client'
    ], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'CompanyController@index']);
        // Route::get('show/{id}', ['as'=>'clients.show', 'uses'=>'ClientController@show']);\

        Route::group(
            ['prefix' => 'companies', 'as' => 'companies.'], function () {
            Route::resource('bank', 'CompanyBankController');
            Route::resource('settings', 'CompanySettingsController', ['only' => ['index', 'store']]);

        });

        Route::resource(
            'companies', 'CompanyController',
            ['only' => ['index', 'show', 'edit', 'update']]
        );

        Route::resource('partners', 'PartnerController');
        Route::get(
            '/partners/{id}/delete',
            ['as' => 'partners.delete', 'uses' => 'PartnerController@destroy']
        );

        Route::get(
            'invoice/{id}/lock/',
            ['as' => 'invoices.lock', 'uses' => 'InvoiceController@lockInvoice']
        );
        Route::get(
            'invoice/{id}/unlock/',
            ['as' => 'invoices.unlock', 'uses' => 'InvoiceController@unlockInvoice']
        );
        Route::get(
            'invoice/{id}/copy/',
            ['as' => 'invoices.copy', 'uses' => 'InvoiceController@copyInvoice']
        );
        Route::resource('invoices', 'InvoiceController');

        Route::get(
            'getLastFiveInvoices', [
                'as' => 'invoices.getLastFiveInvoices',
                'uses' => 'InvoiceController@getLastFiveInvoices',
            ]
        );
        Route::get(
            'getCurrentInvoice/{id}', [
                'as' => 'invoices.getCurrentInvoice',
                'uses' => 'InvoiceController@getCurrentInvoice',
            ]
        );
        Route::get(
            'updateInvoiceNumber/{id}', [
                'as' => 'invoices.updateInvoiceNumber',
                'uses' => 'InvoiceController@updateInvoiceNumber',
            ]
        );

        Route::resource('personal-incomes', 'PersonalIncomeController');


        Route::get('user', [
            'as' => 'user.edit',
            'uses' => 'UserController@edit',
        ]);
        Route::put('user', [
            'as' => 'user.update',
            'uses' => 'UserController@update',
        ]);
    });

}
);
