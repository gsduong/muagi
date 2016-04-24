<?php

Route::group(['middleware' => 'web'], function () {
	Route::get('login', 'Auth\AuthController@getLogin');
	Route::post('login', 'Auth\AuthController@postLogin');

	Route::get('logout', [
		'as' => 'auth.logout',
		'uses' => 'Auth\AuthController@getLogout',
	]);

	if (settings('reg_enabled')) {
		Route::get('register', 'Auth\AuthController@getRegister');
		Route::post('register', 'Auth\AuthController@postRegister');
		Route::get('register/confirmation/{token}', [
			'as' => 'register.confirm-email',
			'uses' => 'Auth\AuthController@confirmEmail',
		]);
	}

	if (settings('forgot_password')) {
		Route::get('password/remind', 'Auth\PasswordController@forgotPassword');
		Route::post('password/remind', 'Auth\PasswordController@sendPasswordReminder');
		Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
		Route::post('password/reset', 'Auth\PasswordController@postReset');
	}

	if (settings('2fa.enabled')) {
		Route::get('auth/two-factor-authentication', [
			'as' => 'auth.token',
			'uses' => 'Auth\AuthController@getToken',
		]);

		Route::post('auth/two-factor-authentication', [
			'as' => 'auth.token.validate',
			'uses' => 'Auth\AuthController@postToken',
		]);
	}

	Route::get('auth/{provider}/login', [
		'as' => 'social.login',
		'uses' => 'Auth\SocialAuthController@redirectToProvider',
		'middleware' => 'social.login',
	]);

	Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

	Route::get('auth/twitter/email', 'Auth\SocialAuthController@getTwitterEmail');
	Route::post('auth/twitter/email', 'Auth\SocialAuthController@postTwitterEmail');

});

Route::group(['middleware' => 'web'], function () {
	Route::localizedGroup(function () {

		Route::get('/', 'Frontend\HomeController@home')->name('frontend.index');
		Route::group(['namespace' => 'Dashboard', 'prefix' => 'dashboard'], function () {
			Route::get('/', [
				'as' => 'dashboard',
				'uses' => 'DashboardController@index',
			]);

			Route::get('profile', [
				'as' => 'profile',
				'uses' => 'ProfileController@index',
			]);

			Route::get('profile/activity', [
				'as' => 'profile.activity',
				'uses' => 'ProfileController@activity',
			]);

			Route::put('profile/details/update', [
				'as' => 'profile.update.details',
				'uses' => 'ProfileController@updateDetails',
			]);

			Route::post('profile/avatar/update', [
				'as' => 'profile.update.avatar',
				'uses' => 'ProfileController@updateAvatar',
			]);

			Route::post('profile/avatar/update/external', [
				'as' => 'profile.update.avatar-external',
				'uses' => 'ProfileController@updateAvatarExternal',
			]);

			Route::put('profile/login-details/update', [
				'as' => 'profile.update.login-details',
				'uses' => 'ProfileController@updateLoginDetails',
			]);

			Route::put('profile/social-networks/update', [
				'as' => 'profile.update.social-networks',
				'uses' => 'ProfileController@updateSocialNetworks',
			]);

			Route::post('profile/two-factor/enable', [
				'as' => 'profile.two-factor.enable',
				'uses' => 'ProfileController@enableTwoFactorAuth',
			]);

			Route::post('profile/two-factor/disable', [
				'as' => 'profile.two-factor.disable',
				'uses' => 'ProfileController@disableTwoFactorAuth',
			]);

			Route::get('profile/sessions', [
				'as' => 'profile.sessions',
				'uses' => 'ProfileController@sessions',
			]);

			Route::delete('profile/sessions/{session}/invalidate', [
				'as' => 'profile.sessions.invalidate',
				'uses' => 'ProfileController@invalidateSession',
			]);

			Route::get('user', [
				'as' => 'user.list',
				'uses' => 'UsersController@index',
			]);

			Route::get('user/create', [
				'as' => 'user.create',
				'uses' => 'UsersController@create',
			]);

			Route::post('user/create', [
				'as' => 'user.store',
				'uses' => 'UsersController@store',
			]);

			Route::get('user/{user}/show', [
				'as' => 'user.show',
				'uses' => 'UsersController@view',
			]);

			Route::get('user/{user}/edit', [
				'as' => 'user.edit',
				'uses' => 'UsersController@edit',
			]);

			Route::put('user/{user}/update/details', [
				'as' => 'user.update.details',
				'uses' => 'UsersController@updateDetails',
			]);

			Route::put('user/{user}/update/login-details', [
				'as' => 'user.update.login-details',
				'uses' => 'UsersController@updateLoginDetails',
			]);

			Route::delete('user/{user}/delete', [
				'as' => 'user.delete',
				'uses' => 'UsersController@delete',
			]);

			Route::post('user/{user}/update/avatar', [
				'as' => 'user.update.avatar',
				'uses' => 'UsersController@updateAvatar',
			]);

			Route::post('user/{user}/update/avatar/external', [
				'as' => 'user.update.avatar.external',
				'uses' => 'UsersController@updateAvatarExternal',
			]);

			Route::post('user/{user}/update/social-networks', [
				'as' => 'user.update.socials',
				'uses' => 'UsersController@updateSocialNetworks',
			]);

			Route::get('user/{user}/sessions', [
				'as' => 'user.sessions',
				'uses' => 'UsersController@sessions',
			]);

			Route::delete('user/{user}/sessions/{session}/invalidate', [
				'as' => 'user.sessions.invalidate',
				'uses' => 'UsersController@invalidateSession',
			]);

			Route::post('user/{user}/two-factor/enable', [
				'as' => 'user.two-factor.enable',
				'uses' => 'UsersController@enableTwoFactorAuth',
			]);

			Route::post('user/{user}/two-factor/disable', [
				'as' => 'user.two-factor.disable',
				'uses' => 'UsersController@disableTwoFactorAuth',
			]);

			Route::get('role', [
				'as' => 'role.index',
				'uses' => 'RolesController@index',
			]);

			Route::get('role/create', [
				'as' => 'role.create',
				'uses' => 'RolesController@create',
			]);

			Route::post('role/store', [
				'as' => 'role.store',
				'uses' => 'RolesController@store',
			]);

			Route::get('role/{role}/edit', [
				'as' => 'role.edit',
				'uses' => 'RolesController@edit',
			]);

			Route::put('role/{role}/update', [
				'as' => 'role.update',
				'uses' => 'RolesController@update',
			]);

			Route::delete('role/{role}/delete', [
				'as' => 'role.delete',
				'uses' => 'RolesController@delete',
			]);

			Route::post('permission/save', [
				'as' => 'dashboard.permission.save',
				'uses' => 'PermissionsController@saveRolePermissions',
			]);

			Route::resource('permission', 'PermissionsController');

			Route::get('settings', [
				'as' => 'settings.general',
				'uses' => 'SettingsController@general',
				'middleware' => 'permission:settings.general',
			]);

			Route::post('settings/general', [
				'as' => 'settings.general.update',
				'uses' => 'SettingsController@update',
				'middleware' => 'permission:settings.general',
			]);

			Route::get('settings/auth', [
				'as' => 'settings.auth',
				'uses' => 'SettingsController@auth',
				'middleware' => 'permission:settings.auth',
			]);

			Route::post('settings/auth', [
				'as' => 'settings.auth.update',
				'uses' => 'SettingsController@update',
				'middleware' => 'permission:settings.auth',
			]);

			if (env('AUTHY_KEY')) {
				Route::post('settings/auth/2fa/enable', [
					'as' => 'settings.auth.2fa.enable',
					'uses' => 'SettingsController@enableTwoFactor',
					'middleware' => 'permission:settings.auth',
				]);

				Route::post('settings/auth/2fa/disable', [
					'as' => 'settings.auth.2fa.disable',
					'uses' => 'SettingsController@disableTwoFactor',
					'middleware' => 'permission:settings.auth',
				]);
			}

			Route::post('settings/auth/registration/captcha/enable', [
				'as' => 'settings.registration.captcha.enable',
				'uses' => 'SettingsController@enableCaptcha',
				'middleware' => 'permission:settings.auth',
			]);

			Route::post('settings/auth/registration/captcha/disable', [
				'as' => 'settings.registration.captcha.disable',
				'uses' => 'SettingsController@disableCaptcha',
				'middleware' => 'permission:settings.auth',
			]);

			Route::get('settings/notifications', [
				'as' => 'settings.notifications',
				'uses' => 'SettingsController@notifications',
				'middleware' => 'permission:settings.notifications',
			]);

			Route::post('settings/notifications', [
				'as' => 'settings.notifications.update',
				'uses' => 'SettingsController@update',
				'middleware' => 'permission:settings.notifications',
			]);

			Route::get('activity', [
				'as' => 'activity.index',
				'uses' => 'ActivityController@index',
			]);

			Route::get('activity/user/{user}/log', [
				'as' => 'activity.user',
				'uses' => 'ActivityController@userActivity',
			]);

			Route::group([
				'prefix' => 'log-viewer',
				'middleware' => 'role:Admin',
			], function () {
				Route::get('/', [
					'as' => 'log-viewer::dashboard',
					'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@index',
				]);
				Route::group([
					'prefix' => 'logs',
				], function () {
					Route::get('/', [
						'as' => 'log-viewer::logs.list',
						'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@listLogs',
					]);
					Route::delete('delete', [
						'as' => 'log-viewer::logs.delete',
						'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@delete',
					]);
				});
				Route::group([
					'prefix' => '{date}',
				], function () {
					Route::get('/', [
						'as' => 'log-viewer::logs.show',
						'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@show',
					]);
					Route::get('download', [
						'as' => 'log-viewer::logs.download',
						'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@download',
					]);
					Route::get('{level}', [
						'as' => 'log-viewer::logs.filter',
						'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@showByLevel',
					]);
				});
			});
		});
	});

});


Route::get('/', function(){
	return view('homepage');
});

Route::group(['prefix' => 'api/v1'], function(){
	Route::get('products', function (){
		return Response::json(App\Products::all());
	});

	Route::get('channels', function(){
		return Response::json(App\Channels::all());
	});

	Route::get('broadcast', function(){
		$clock = new App\ExternalClasses\MyClock();
		$today = $clock->get_today_date_GMT_7("Y-m-d");
		$current_time_hh_mm_GMT_7 = $clock->get_current_time_GMT_7("H:i");
		$current_unix_time_UTC = $clock->get_unix_time_UTC_from_GMT_7($current_time_hh_mm_GMT_7, $today);
		$pattern = $today.'%';
		$products = DB::table('products')->where('created_at', 'LIKE', $pattern)->get();
		$array = array();

		function item_type($start_time, $end_time, $current_time){
			if ($current_time < $start_time) {
				return 1;
			}
			elseif ($current_time >= $start_time && $current_time <= $end_time) {
				return 0;
			}
			else return -1;
		}
		foreach ($products as $product) {
			if (($item_type = item_type($product->start_time, $product->end_time, $current_unix_time_UTC)) != -1) {
				array_push($array, new App\ExternalClasses\Item($product, $today, $item_type));
			}
		}

		return Response::json($array);
	});

});

Route::group(['prefix' => 'cron'], function(){
	Route::get('products', function(){
		$clock = new App\ExternalClasses\MyClock();
		
		$today = $clock->get_today_date_GMT_7("Y-m-d");
		$nextDay = $clock->get_nextday_date_GMT_7("Y-m-d");

		/* For SCJ channel*/
		$baseURL = 'http://www.scj.vn';
		$API_URL = "http://www.scj.vn/index.php?option=com_broadcasting&task=getEvent&lang=vi&type=day&start=".$today."&end=".$nextDay;
		$json = file_get_contents($API_URL);
		$responses = json_decode($json);
		$start_date = $today;
		$end_date = $nextDay;
		$array = array();
		foreach ($responses as $product) {

			$available_time = $product->scjtime;
			$channel_id = 1;
			$title = $product->product_name;
			$image_link = $product->ori_url;
			$video_link = "rtmp://vtsstr6.sctv.vn/colive";
			$product_link = $baseURL.($product->scjurl);
			$description = "Null";
			$old_price = $product->basic_price;
			$new_price = $product->marketprice;
			list($gmt7_start_time, $gmt7_end_time) = explode("-", $available_time);
			$start_time = $clock->get_unix_time_UTC_from_GMT_7($gmt7_start_time, $start_date);
			$end_time = $clock->get_unix_time_UTC_from_GMT_7($gmt7_end_time, $start_date);

			$item = App\Products::firstOrCreate(['title' => $title, 'available_time' => $available_time, 'channel_id' => $channel_id, 'image_link' => $image_link, 'video_link' => $video_link, 'product_link' => $product_link, 'description' => $description, 'old_price' => $old_price, 'new_price' => $new_price, 'start_time' => $start_time, 'end_time' => $end_time]);
			array_push($array, $item);
		}
		return Response::json($array);
	});

	Route::get('test', function(){
		$clock = new App\ExternalClasses\MyClock();
		
		$today = $clock->get_today_date_GMT_7("Y-m-d");
		$nextDay = $clock->get_nextday_date_GMT_7("Y-m-d");

		/* For Lotte channel*/
		$lotte_baseURL = 'http://lottedatviet.vn/index.do';
		$lotte_API_URL = "http://lottedatviet.vn/display/tvScheduleList.json";
		$lotte_json = file_get_contents($lotte_API_URL);
		$lotte_responses = json_decode($lotte_json);
		$start_date = $today;
		$end_date = $nextDay;
		$lotte_products = array();
		foreach ($lotte_responses->goodsList as $element) {
			array_push($lotte_products, ['title' => $element->goodsName]);
		}

		echo(json_encode($lotte_products));
		// $client = new Goutte\Client();
		// $crawler = $client->request('GET', $url_to_crawl);

		// $channel_id = 2;
		// $video_link = "rtmp://vtsstr6.sctv.vn/colive/";

		// if($crawler->filterXPath($domSelector.'/span[@class="broad_time_bg"]/text()')->count()){
		// 	$currentTimeString = $crawler->filterXPath($domSelector.'/span[@class="broad_time_bg"]/text()')->text();
		// 	list($start_time, $end_time) = explode("-", $currentTimeString);
		// 	$available_time = $currentTimeString;
		// 	$item_link = $baseURL.($crawler->filterXPath($domSelector.'//h3[@class="broadname"]/a/@href')->text());
		// 	$title = $crawler->filterXPath($domSelector.'//h3[@class="broadname"]/a')->text();
		// 	$image_link = $crawler->filterXPath($domSelector.'/div[contains(@class, "broad_img") and contains(@class, "fleft")]/a/img/@data-original')->text();
		// 	//crawl price, description
		// 	$item_client = new Goutte\Client();
		// 	$item_crawler = $item_client->request('GET', $item_link);
		// 	$old_price = ($item_crawler->filterXPath('//div[@class="detailBox"]//span[contains(@class, "price_regular")]/b/text()')->count() == 0) ? "" : explode("đ", $item_crawler->filterXPath('//div[@class="detailBox"]//span[contains(@class, "price_regular")]/b/text()')->text())[0];
			 
		// 	$price_string = $item_crawler->filterXPath('//div[@class="detailBox"]//span[contains(@class, "price") and contains(@class, "col2")]/text()[1]')->text();
		// 	$new_price = explode("đ", $price_string)[0];
		// 	$description = "Description";
		// 	array_push($array, new Item("live", $title, $item_link, $image_link, $start_time, $end_time, $available_time, $channel_id, $video_link, $description, $new_price, $old_price));
		// }

	});

	Route::get('all', function(){ // crawl all products to save in database
		/* For Lotte */
		$lotte_baseURL = "http://lottedatviet.vn/index.do";


		/* For SCJ */
		$scj_baseURL = "http://www.scj.vn";
		$scj_client = new Goutte\Client();
		$scj_crawler = $scj_client->request('GET', $scj_baseURL);
		$scj_category_domselector = '//*[contains(@class, "ilevel_0") or contains(@class, "ilevel_1")]/a/@href';
		$cat_links = array();
		$cat_links = $scj_crawler->filterXPath($scj_category_domselector)->each(function($node, $i){
			return ['url' => "http://www.scj.vn".($node->text())];
		});
		echo json_encode($cat_links);
	});
});