<?php

Route::group(['middleware' => 'web', 'prefix' => 'solution', 'namespace' => 'Modules\Solution\Http\Controllers'], function()
{

    Route::get('/login', 'LoginController@index');
    Route::post('/login', 'LoginController@doLogin');
    Route::match(['get', 'post'],'/logout', 'LoginController@logout');
    
    /**
     * Menampilkan list menu issue yang dapat di-resolve menggunakan applikasi ini.
     */
    Route::get('/', 'SolutionController@index');
    Route::get('/frontend/{param}', 'SolutionController@frontend');

    /**
     * User management
     */
    Route::get('/user', 'UsermanagementController@index');
    Route::post('/userlist', 'UsermanagementController@grid');
    Route::post('/ldapsearch', 'UsermanagementController@search');
    Route::post('/user/save', 'UsermanagementController@save');
    Route::post('/getUserRole', 'UsermanagementController@getUserRole')->name('get_role');
    Route::get('/role', 'RolemanagementController@index');
    Route::post('/role/save', 'RolemanagementController@save')->name('save_role');
    Route::post('/role/list', 'RolemanagementController@grid');
    Route::post('/role/getAccess', 'RolemanagementController@getRoleAccess')->name('get_menu_access');


    Route::get('/pdm-list', 'PdmlistController@index');
    Route::post('/pdm-list/list', 'PdmlistController@grid');
    Route::post('/bpmsearch', 'BpmController@search');
    Route::get('/bpm', 'BpmController@index');

    /**
     * General issue data locator
     * Untuk menemukan data berdasarkan parameter yang diinput
     */
    Route::post('/data', 'SolutionController@grid');
    Route::post('/data/{param}', 'SolutionController@grid');
    Route::get('/issue/{param}', 'SolutionController@issue')->name('run_solution');
    Route::post('/resolve', 'SolutionController@execute');

    /**
     * Routing untuk menambahkan management issue dan parameter-parameternya
     */
    Route::get('/resolution', 'ResolutionController@index')->name('issue_list');
    Route::get('/resolution/create', 'ResolutionController@create');
    Route::post('/resolution/save', 'ResolutionController@save');
    Route::post('/resolution/update', 'ResolutionController@issueDetail')->name('update_issue');
    Route::post('/resolution/delete', 'ResolutionController@delete')->name('delete_issue');

    /**
     * Reports
     */
    Route::get('/report', 'ReportController@index');
    Route::get('/report/{execution_id}', 'ReportController@detail');
    Route::post('/report', 'ReportController@index');
    Route::post('/report/list', 'ReportController@grid');

});
