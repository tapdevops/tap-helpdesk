<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
return view('layouts.index');
});
*/


Route::get('/', function(){
  return redirect('solution');
});

Route::resource('/dashboard', 'DashboardsController', ['only' => ['index']]);
Route::get('dashboards/mtsdaily', ['as' => 'dashboards.mtsdaily', 'uses' => 'DashboardsController@mtsdaily']);
Route::get('dashboards/mtrdaily', ['as' => 'dashboards.mtrdaily', 'uses' => 'DashboardsController@mtrdaily']);

Route::resource('/monitoring', 'MonitoringController', ['only' => ['index']]);
Route::post('monitoring/getToday', ['as' => 'monitoring.gettoday', 'uses' => 'MonitoringController@getToday']);
Route::post('monitoring/getTomorrow', ['as' => 'monitoring.gettomorrow', 'uses' => 'MonitoringController@getTomorrow']);
Route::post('monitoring/getSubwo', ['as' => 'monitoring.getsubwo', 'uses' => 'MonitoringController@getSubwo']);
Route::post('monitoring/getOutstanding', ['as' => 'monitoring.getoutstanding', 'uses' => 'MonitoringController@getOutstanding']);
Route::post('monitoring/getCorrective', ['as' => 'monitoring.getcorrective', 'uses' => 'MonitoringController@getCorrective']);

Route::get('/monitoring-workshop', 'MonitoringController@page_one')->name('monitoring-workshop');
Route::get('/monitoring/1', 'MonitoringController@page_one')->name('page-one');
Route::get('/monitoring/2', 'MonitoringController@page_two')->name('page-two');

Route::get('/monitoring-workshop/{id_company}', 'MonitoringController@monitoring_workshop')->name('monitoring-workshop');
Route::post('monitoring/getTodayWorkshop/{id_company}', ['as' => 'monitoring.gettodayworkshop', 'uses' => 'MonitoringController@getTodayWorkshop']);
Route::post('monitoring/getTomorrowWorkshop/{id_company}', ['as' => 'monitoring.gettomorrowworkshop', 'uses' => 'MonitoringController@getTomorrowWorkshop']);
Route::post('monitoring/getCorrectiveWorkshop/{id_company}', ['as' => 'monitoring.getcorrectiveworkshop', 'uses' => 'MonitoringController@getCorrectiveWorkshop']);

/* 290419@IT */
Route::get('/monitoring-workshop-login/{id_company}', 'MonitoringController@monitoring_workshop_login')->name('monitoring-workshop-login');
Route::post('/monitoring-workshop-dologin/{id_company}', 'MonitoringController@monitoring_workshop_dologin')->name('monitoring-workshop-dologin');
Route::get('/monitoring-workshop-logout/{id_company}', 'MonitoringController@monitoring_workshop_logout')->name('monitoring-workshop-logout');

/* 300419@IT */
Route::get('/monitoring-workshop-download-today/{id_company}', 'MonitoringController@monitoring_workshop_download_today')->name('monitoring-workshop-download-today');
Route::get('/monitoring-workshop-download-tomorrow/{id_company}', 'MonitoringController@monitoring_workshop_download_tomorrow')->name('monitoring-workshop-download-tomorrow');
Route::get('/monitoring-workshop-download-corrective/{id_company}', 'MonitoringController@monitoring_workshop_download_corrective')->name('monitoring-workshop-download-corrective');

Route::get('login', function(){
  return redirect('solution');
});

Route::match(['get', 'post'],'logout', function(){
  return redirect('/solution/logout');
});

Route::get('monitoring-ticket-1', 'DashboardsController@monitoring_ticket')->name('monitoring-ticket-1');
Route::get('monitoring-ticket-2', 'DashboardsController@monitoring_ticket_page_two')->name('monitoring-ticket-2');
Route::get('monitoring-ticket-3', 'DashboardsController@monitoring_ticket_page_three')->name('monitoring-ticket-3');
Route::get('monitoring-ticket', 'DashboardsController@monitoring_ticket_page_one')->name('monitoring-ticket');


### IRS - tr_hv_production_daily (mb Juki - 180219)
Route::get('/production_daily_cron', 'Tr_hv_production_dailyController@production_daily_cron')->name('production_daily_cron');
Route::get('/tr_hv_production_daily', 'Tr_hv_production_dailyController@index')->name('tr_hv_production_daily');
//Route::get('/tr_hv_production_daily_method2', 'Tr_hv_production_dailyController@method_2')->name('tr_hv_production_daily_method2/');
Route::get('/sqvi', 'SqviController@cron')->name('sqvi');
Route::get('/sqvi_date/{ddmmyyyy}', 'Sqvi_dateController@cron')->name('sqvi_date/{ddmmyyyy}');
Route::get('/crop_harvest/{comp_ba}', 'Crop_harvestController@cron')->name('crop_harvest/{comp_ba}');
Route::get('/crop_harvest_date/{ddmmyyyy}/{comp_ba}', 'Crop_harvest_dateController@cron')->name('crop_harvest_date/{ddmmyyyy}/{comp_ba}');
Route::get('/zpay_view_rawat/{comp_ba}', 'Zpay_view_rawatController@cron')->name('zpay_view_rawat/{comp_ba}');
Route::get('/zpay_view_rawat_date/{ddmmyyyy}/{comp_ba}', 'Zpay_view_rawat_dateController@cron')->name('zpay_view_rawat_date/{ddmmyyyy}/{comp_ba}');
Route::get('/janjang_kirim', 'Janjang_kirimController@cron')->name('janjang_kirim');
### END IRS - tr_hv_production_daily (mb Juki - 180219)

### Peta Inspeksi
Route::get('/inspeksiw', 'InspeksiWeekController@index')->name('inspeksiw');
###