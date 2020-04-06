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
Route::get('/faq', 'ChartController@Faq')->name('Faq');
Route::get('/refer_friend', 'ChartController@ReferFriend')->name('ReferFriend');
Route::get('/get_support', 'ChartController@GetSupport')->name('GetSupport');
Route::get('/cookies_policy', 'ChartController@cookies_policy')->name('cookies_policy');
Route::get('/disclaimer', 'ChartController@disclaimer')->name('disclaimer');
Route::get('/terms_of_use', 'ChartController@terms_of_use')->name('terms_of_use');
Route::get('/referal_agreement', 'ChartController@referal_agreement')->name('referal_agreement');
Route::get('/privacy_policy', 'ChartController@privacy_policy')->name('privacy_policy');

Route::get('/RssFeed', 'ChartController@AddBlogFeedUrl')->name('AddBlogFeedUrl');
Route::get('/news/{id}/{title}', 'ChartController@newsRead')->name('NewsRead');

//Google 2fa
Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration')->name('completeRegistration');
Route::post('/2fa', function () {
	// return redirect(URL()->previous());
    return redirect(route('userProfile'));
	
})->name('2fa')->middleware('2fa');

/*Route::view('/maptest', 'map_test');*/
Route::get('/viewBlog/{id}', 'ChartController@ViewBlog')->name('ViewBlog');
Auth::routes(['verify' => true]);
Route::middleware(['admin'])->group(function () {
	Route::prefix('admin')->group(function () {
		Route::get('/user/new', 'HomeController@AddNewUser')->name('AddNewUser');
		Route::post('/user/new', 'HomeController@PostNewUser')->name('PostNewUser');
		Route::get('/user/list', 'HomeController@UserList')->name('UserList');
		Route::get('/user/view/{id}', 'HomeController@ViewUser')->name('ViewUser');
		Route::get('/user/iplog', 'HomeController@IpLog')->name('IpLog');
		
		Route::get('/user/edit/{id}', 'HomeController@UserEdit')->name('UserEdit');
		Route::post('/user/edit/{id}', 'HomeController@UpdateUser')->name('UpdateUser');
		Route::get('/user/delete/{id}', 'HomeController@DeleteUser')->name('DeleteUser');
		Route::get('/home', 'HomeController@index')->name('home');
		Route::get('/user/functionalities', 'HomeController@UserFunctionalities')->name('UserFunctionalities');
		Route::post('/user/addfunctionalities', 'HomeController@AddFunctionalities')->name('AddFunctionalities');
		Route::get('/logs', 'HomeController@AlertLog')->name('AlertLog');
		Route::get('/payment_details', 'HomeController@PaymentDetails')->name('PaymentDetails');
		Route::post('/user/changeSubscription/', 'HomeController@ChangeSubscription')->name('ChangeSubscription');
		Route::post('/user/changeSubscriptionPeriod/', 'HomeController@ChangeSubscriptionPeriod')->name('ChangeSubscriptionPeriod');
		Route::get('/subscription_amount', 'HomeController@SubscriptionAmount')->name('SubscriptionAmount');
		Route::post('/subscription_amount/add', 'HomeController@AddSubscriptionAmount')->name('AddSubscriptionAmount');
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
			/*Route::get('/RssFeed', 'HomeController@AddBlogFeedUrl')->name('AddBlogFeedUrl');*/
		});
		Route::get('/downloadexcel/{type}', 'HomeController@downloadExcel')->name('downloadExcel');
	});
});

Route::middleware(['user'])->group(function () {
	Route::prefix('user')->group(function () {
		Route::get('/', 'UserController@profile')->name('userProfile');
		Route::get('/profile2', 'UserController@profile2')->name('userProfile2'); /*cryptocompare*/

		Route::prefix('subscription')->group(function () {
			Route::get('signal_settings', 'UserController@SignalSettings')->name('SignalSettings');
			Route::post('OnSignalSettings', 'UserController@OnSignalSettings')->name('OnSignalSettings');
			Route::post('OffSignalSettings', 'UserController@OffSignalSettings')->name('OffSignalSettings');
			Route::get('rsi', 'UserController@GetRsiSubscription')->name('GetRsiSubscription');
			Route::post('rsi', 'UserController@PostRsiSubscription')->name('PostRsiSubscription');
			
			Route::get('sma', 'UserController@GetSmaSubscription')->name('GetSmaSubscription');
			Route::post('sma', 'UserController@PostSmaSubscription')->name('PostSmaSubscription');
		});
		Route::post('/save-theme', 'UserController@SaveTheme')->name('SaveTheme');

		Route::get('/smart-trade', 'UserController@smartTrade')->name('smartTrade');
		Route::get('/portfolio', 'UserController@portfolio')->name('portfolio');
		Route::get('/screenerpro', 'UserController@screenerpro')->name('screenerpro');

		Route::get('/alerts', 'UserController@alerts')->name('alerts');
		
		Route::post('/widget', 'UserController@addRemoveWidgets')->name('addRemoveWidgets');
		Route::post('/widget/save', 'UserController@SaveWidgets')->name('SaveWidgets');
		Route::get('/settings', 'UserController@settings')->name('settings');
		Route::post('/settings', 'UserController@PostuserSettings')->name('PostuserSettings');
		Route::get('/enable/2fa/{secret}', 'UserController@enable2fa')->name('enable2fa');
		
		
		Route::get('/subscribe', 'SubscriptionController@create')->name('UserSubcribe');
		Route::post('/makepayment', 'PaymentController@makePayment')->name('makePayment');
		Route::post('/payment', 'PaymentController@payment')->name('payment');
		Route::post('/payment-status', 'PaymentController@getPaymentStatus')->name('paymentStatus');
		Route::get('/payment-view', 'PaymentController@viewPayDetails')->name('paymentView');
		Route::post('/payment-save', 'PaymentController@savePaymentDetails')->name('paymentSave');
		Route::get('/payment_details', 'UserController@UserPaymentDetails')->name('UserPaymentDetails');
		Route::get('/feed', 'UserController@Feed')->name('UserFeed');
		Route::get('/feed/settings', 'UserController@FeedSettings')->name('FeedSettings');
		Route::get('/feed/add/new/{id}', 'UserController@AddUserFeed')->name('AddUserFeed');
		Route::post('/feed/add/ajax', 'UserController@AddUserFeedAjax')->name('AddUserFeedAjax');
		Route::post('/feed/ajax', 'UserController@FeedAjax')->name('FeedAjax');
		Route::post('/feed/time/ajax', 'UserController@FeedAjaxTime')->name('FeedAjaxTime');
		Route::get('/feed/tags/ajax', 'UserController@FeedTagsAjax')->name('FeedTagsAjax');
		Route::post('/feed/ajax/test', 'UserController@FeedAjaxTest')->name('FeedAjaxTest');
		Route::post('/feed/ajax/preloader', 'UserController@PreLoader')->name('PreLoader');
		Route::post('/feed/notification/sound/ajax', 'UserController@NotificationSoundAjax')->name('NotificationSoundAjax');
		Route::post('/feed/ajax/modal', 'UserController@FeedSettingModal')->name('FeedSettingModal');
		Route::post('/feed/ajax/modal/save', 'UserController@SaveFeedSetting')->name('SaveFeedSetting');
		Route::post('/feed/ajax/test1', 'UserController@FeedAjaxTestBlank')->name('FeedAjaxTestBlank');
		Route::post('/feed/ajax/userlink', 'UserController@SaveUserLink')->name('SaveUserLink');
		Route::post('/feed/ajax/language', 'UserController@FeedAjaxFetchLanguage')->name('FeedAjaxFetchLanguage');
		Route::post('/feed/ajax/description', 'UserController@FeedAjaxFetchDescription')->name('FeedAjaxFetchDescription');
		Route::post('/dashboard/ajax/live_dashboard', 'UserController@AjaxliveDashboard')->name('AjaxliveDashboard');
		Route::get('/test', 'TestController@test')->name('test');
		Route::post('/feed/ajax/DesableFeedCategory', 'UserController@DesableFeedCategory')->name('DesableFeedCategory');
		Route::get('/billing-address', 'UserController@BillingAddress')->name('BillingAddress');
		Route::get('/billing-address/AddAddress', 'UserController@AddAddress')->name('AddAddress');
		Route::post('/billing-address/AddAddresspost', 'UserController@AddAddressPost')->name('AddAddressPost');
		Route::get('/billing-address/delete{id}', 'UserController@DeleteAddress')->name('DeleteAddress');
		Route::get('/billing-address/edit{id}', 'UserController@EditAddress')->name('EditAddress');
		Route::post('/billing-address/editpost', 'UserController@EditAddressPost')->name('EditAddressPost');
		Route::post('/allfeed', 'UserController@GetAllFeed')->name('GetAllFeed');
		

		








		
		
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
