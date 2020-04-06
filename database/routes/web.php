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

Route::get('/', 'ChartController@Front')->name('Front');

/*Route::view('/maptest', 'map_test');*/

Route::middleware(['admin'])->group(function () {
	Route::prefix('admin')->group(function () {
		Route::get('/user/new', 'HomeController@AddNewUser')->name('AddNewUser');
		Route::post('/user/new', 'HomeController@PostNewUser')->name('PostNewUser');
		Route::get('/user/list', 'HomeController@UserList')->name('UserList');
		Route::get('/user/edit/{id}', 'HomeController@UserEdit')->name('UserEdit');
		Route::post('/user/edit/{id}', 'HomeController@UpdateUser')->name('UpdateUser');
		Route::get('/user/delete/{id}', 'HomeController@DeleteUser')->name('DeleteUser');
		Route::get('/home', 'HomeController@index')->name('home');
		Route::get('/logs', 'HomeController@AlertLog')->name('AlertLog');
		Route::get('/log_table', 'HomeController@AlertLogTable')->name('AlertLogTable');
		Route::get('/feed', 'HomeController@Feed')->name('Feed');
		Route::get('/feed/add/', 'HomeController@AddFeed')->name('AddFeed');
		Route::post('/feed/post', 'HomeController@PostFeed')->name('PostFeed');
		Route::get('/feed/delete/{id}', 'HomeController@DeleteFeed')->name('DeleteFeed');
		Route::get('/feed/edit/{id}', 'HomeController@EditFeed')->name('EditFeed');
		Route::post('/feed/update', 'HomeController@UpdateFeed')->name('UpdateFeed');
		Route::prefix('blog')->group(function () {
			Route::get('/', 'HomeController@ListBlog')->name('listBlog');
			Route::get('/add', 'HomeController@AddBlog')->name('AddBlog');
			Route::post('/add', 'HomeController@AddBlogPost')->name('AddBlogPost');
			Route::get('/update/{id}', 'HomeController@UpdateBlog')->name('UpdateBlog');
			Route::post('/update', 'HomeController@UpdateBlogPost')->name('UpdateBlogPost');

			Route::post('/delete', 'HomeController@BlogDelete')->name('BlogDelete');
		});
		Route::get('/downloadexcel/{type}', 'HomeController@downloadExcel')->name('downloadExcel');
		
		
	});
});
Route::middleware(['user'])->group(function () {
	Route::prefix('user')->group(function () {
		Route::get('/', 'UserController@profile')->name('userProfile');
		Route::prefix('subscription')->group(function () {
			Route::get('rsi', 'UserController@GetRsiSubscription')->name('GetRsiSubscription');
			Route::post('rsi', 'UserController@PostRsiSubscription')->name('PostRsiSubscription');
			
			Route::get('sma', 'UserController@GetSmaSubscription')->name('GetSmaSubscription');
			Route::post('sma', 'UserController@PostSmaSubscription')->name('PostSmaSubscription');
		});
		Route::get('/feed', 'UserController@Feed')->name('UserFeed');
		Route::get('/feed/settings', 'UserController@FeedSettings')->name('FeedSettings');
		Route::get('/feed/add/new/{id}', 'UserController@AddUserFeed')->name('AddUserFeed');
		Route::post('/feed/add/ajax', 'UserController@AddUserFeedAjax')->name('AddUserFeedAjax');
		Route::post('/feed/ajax', 'UserController@FeedAjax')->name('FeedAjax');
		Route::post('/feed/ajax/test', 'UserController@FeedAjaxTest')->name('FeedAjaxTest');
		Route::post('/feed/notification/sound/ajax', 'UserController@NotificationSoundAjax')->name('NotificationSoundAjax');
		
	});
});
Route::post('/generate/password', 'HomeController@GeneratePassword')->name('GeneratePassword');	
Route::post('/get/time_frames', 'HomeController@GetTimeFrames')->name('GetTimeFrames');	
Route::get('/symbol/{symbol}/{interval}', 'ChartController@ShowChart')->name('ShowChart');
Route::get('/test_chart', 'ChartController@TestChart')->name('TestChart');
Route::get('/test', 'ChartController@get_rsi')->name('getRsi');
Route::post('candlesticks', 'ChartController@CandleSticks')->name('CandleSticks');
Route::get('/find_low_price', 'ChartController@find_low_price')->name('FindLowPrice');
Route::get('/hrDivergence/{symbol}/{interval}', 'ChartController@hrDivergence')->name('hrDivergence');
Route::get('/movingAverage/{symbol}/{interval}', 'SmaController@movingAverageChart')->name('movingAverageChart');
Route::get('/movingAverage', 'SmaController@movingAverage')->name('movingAverageTest');
Route::post('/movingAvarege/CandleSticks', 'SmaController@MaCandleSticks')->name('MaCandleSticks');


Auth::routes();

Route::post('/bot_commands', 'BotController@bot_commands')->name('botCommands');

Route::get('/getID', 'BotController@getTelegramId');

Route::get('/testing', 'ChartController@Testing')->name('Testing');
//Route::get('/feeds', 'MainController@Feed')->name('PublicFeed');



////
Route::get('/onehoursymbol/{symbol}/{interval}', 'ChartController@ShowChartOneHour')->name('ShowChartOneHour');

//bot section
Route::post('/bigchief_bot_commands', 'BigchiefBotController@bot_commands')->name('bigchiefbotCommands');
Route::get('/bigchief_reponsetest', 'BigchiefBotController@reponsetest')->name('reponsetest');

Route::post('/botcandlesticks', 'BigchiefBotController@botcandlesticks')->name('botcandlesticks');
Route::get('/heikinashiChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@heikinashiChart')->name('heikinashiChart');
//Heikin ashi chart
Route::post('/heikinashicandlesticks', 'BigchiefBotController@heikinashicandlesticks')->name('heikinashicandlesticks');
Route::get('/botsimpleChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@botsimpleChart')->name('botsimpleChart');
//Guppy Chart
Route::post('/guppycandlesticks', 'BigchiefBotController@guppycandlesticks')->name('guppycandlesticks');
Route::get('/guppyChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@guppyChart')->name('guppyChart');
//Bot bollinger band Chart
Route::post('/botBollingerCandleSticks', 'BigchiefBotController@botBollingerCandleSticks')->name('botBollingerCandleSticks');
Route::get('/botBollingerbandChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@botBollingerbandChart')->name('botBollingerbandChart');
//Bot Keltner Channels Chart
Route::post('/keltnerCandleSticks', 'BigchiefBotController@keltnerCandleSticks')->name('keltnerCandleSticks');
Route::get('/keltnerChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@keltnerChart')->name('keltnerChart');
//Bot Parabolic sar Chart
Route::post('/psarcandlesticks', 'BigchiefBotController@psarcandlesticks')->name('psarcandlesticks');
Route::get('/psarChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@psarChart')->name('psarChart');
//Bot Fibonacci Retracement Chart
Route::post('/fibonaccicandlesticks', 'BigchiefBotController@fibonaccicandlesticks')->name('fibonaccicandlesticks');
Route::get('/fibonacciChart/{symbol}/{interval}/{limit}', 'BigchiefBotController@fibonacciChart')->name('fibonacciChart');

////Bigchief Horizontal divergence
Route::get('/hrDivergence', 'HrdivergenceController@hrDivergence')->name('hrDivergence');
Route::post('/getcandlesticks', 'HrdivergenceController@CandleSticks')->name('getcandlesticks');
Route::get('/hrDivergenceChart/{symbol}/{interval}', 'HrdivergenceController@hrDivergenceChart')->name('hrDivergenceChart');

////Bigchief Moving average
Route::get('/movingaverage', 'MovingaverageController@movingaverage')->name('movingaverage');
Route::post('/movingAvaregeCandleSticks', 'MovingaverageController@movingAvaregeCandleSticks')->name('movingAvaregeCandleSticks');
Route::get('/movingaverageChart/{symbol}/{interval}', 'MovingaverageController@movingaverageChart')->name('movingaverageChart');

////Bigchief Bollingerband
Route::get('/bollingerband', 'BollingerController@bollingerband')->name('bollingerband');
Route::post('/bollingerCandleSticks', 'BollingerController@bollingerCandleSticks')->name('bollingerCandleSticks');
Route::get('/bollingerChart/{symbol}/{interval}', 'BollingerController@bollingerChart')->name('bollingerChart');

////Bigchief SMMA
Route::get('/smma', 'SmmaController@smma')->name('smma');
Route::post('/smmaCandleSticks', 'SmmaController@smmaCandleSticks')->name('smmaCandleSticks');
Route::get('/smmaChart/{symbol}/{interval}', 'SmmaController@smmaChart')->name('smmaChart');

////Bigchief Horizontal Resistance
Route::get('/resistance', 'ResistanceController@resistance')->name('resistance');
Route::post('/resistanceCandlesticks', 'ResistanceController@resistanceCandlesticks')->name('resistanceCandlesticks');
Route::get('/resistanceChart/{symbol}/{interval}', 'ResistanceController@resistanceChart')->name('resistanceChart');

////Bigchief RSI
Route::get('/rsi', 'RsiController@rsi')->name('rsi');
Route::post('/rsicandlesticks', 'RsiController@rsicandlesticks')->name('rsicandlesticks');
Route::get('/rsiChart/{symbol}/{interval}', 'RsiController@rsiChart')->name('rsiChart');

////Bigchief RSI pred
Route::get('/rsipredChart/{symbol}/{interval}', 'RsiPrediction@rsipredChart')->name('rsipredChart');
Route::get('/rsiPrediction/{symbol}/{interval}', 'RsiPrediction@rsiPrediction')->name('rsiPrediction');
Route::post('rsipredcandlesticks', 'RsiPrediction@CandleSticks')->name('rsipredcandlesticks');

Route::get('/binanceApicall', 'CommonController@binanceApicall')->name('binanceApicall');
