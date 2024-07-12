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

Route::get('/', function () {
	// return view('auth.login');
	if (Auth::check()) {
		// If the user is authenticated, redirect to the dashboard
		return redirect('/home');
	}

	// If the user is not authenticated, show the login page
	return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dashboard', function () {
	return view('layouts.master');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('categories', 'CategoryController');
	Route::get('/apiCategories', 'CategoryController@apiCategories')->name('api.categories');
	Route::get('/exportCategoriesAll', 'CategoryController@exportCategoriesAll')->name('exportPDF.categoriesAll');
	Route::get('/exportCategoriesAllExcel', 'CategoryController@exportExcel')->name('exportExcel.categoriesAll');

	Route::resource('customers', 'CustomerController');
	Route::get('/apiCustomers', 'CustomerController@apiCustomers')->name('api.customers');
	Route::post('/importCustomers', 'CustomerController@ImportExcel')->name('import.customers');
	Route::get('/exportCustomersAll', 'CustomerController@exportCustomersAll')->name('exportPDF.customersAll');
	Route::get('/exportCustomersAllExcel', 'CustomerController@exportExcel')->name('exportExcel.customersAll');

	Route::resource('sales', 'SaleController');
	Route::get('/apiSales', 'SaleController@apiSales')->name('api.sales');
	Route::post('/importSales', 'SaleController@ImportExcel')->name('import.sales');
	Route::get('/exportSalesAll', 'SaleController@exportSalesAll')->name('exportPDF.salesAll');
	Route::get('/exportSalesAllExcel', 'SaleController@exportExcel')->name('exportExcel.salesAll');

	Route::resource('Analytics', 'AnalyticsController');
	Route::get('/apiAnalytics', 'AnalyticsController@apiAnalytics')->name('api.Analytics');
	Route::post('/importAnalytics', 'AnalyticsController@ImportPDF')->name('import.Analytics');
	Route::get('/exportAnalyticsPDF/{id}', 'AnalyticsController@exportAnalyticsAll')->name('exportPDF.AnalyticsAll');
	Route::get('/exportAnalyticsAllExcel', 'AnalyticsController@exportExcel')->name('exportExcel.AnalyticsAll');

	Route::resource('cards', 'cardController');
	Route::get('/apicards', 'cardController@apicards')->name('api.cards');
	Route::post('/saveworkingdays', 'cardController@saveworkingdays')->name('saveworkingdays');
	Route::get('/getworkingdays', 'cardController@getworkingdays')->name('getworkingdays');

	Route::resource('cardsOut', 'CardRegisterController');
	Route::get('/apicardsOut', 'CardRegisterController@apicardsOut')->name('api.cardsOut');
	Route::get('/exportcardKeluarAll', 'CardRegisterController@exportcardKeluarAll')->name('exportPDF.cardKeluarAll');
	Route::get('/exportcardKeluarAllExcel', 'CardRegisterController@exportExcel')->name('exportExcel.cardKeluarAll');
	Route::get('/exportcardKeluar/{id}', 'CardRegisterController@exportcardKeluar')->name('exportPDF.cardKeluar');

	Route::resource('cardsIn', 'cardMasukController');
	Route::get('/apicardsIn', 'cardMasukController@apicardsIn')->name('api.cardsIn');
	Route::get('/exportcardMasukAll', 'cardMasukController@exportcardMasukAll')->name('exportPDF.cardMasukAll');
	Route::get('/exportcardMasukAllExcel', 'cardMasukController@exportExcel')->name('exportExcel.cardMasukAll');
	Route::get('/exportcardMasuk/{id}', 'cardMasukController@exportcardMasuk')->name('exportPDF.cardMasuk');

	Route::resource('user', 'UserController');
	Route::get('/apiUser', 'UserController@apiUsers')->name('api.users');
});
