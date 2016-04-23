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

		class Item{
			var $item_type;
			var $title;
			var $product_link;
			var $image_link;
			var $available_time;
			var $channel_id;
			var $video_link;
			var $gmt7_start_time;
			var $gmt7_end_time;
			var $new_price;
			var $old_price;
			var $description;
			var $start_date;
			var $end_date;
			var $start_time;
			var $end_time;
			function Item($item_type, $title, $link, $img, $gmt7_start_time, $gmt7_end_time, $start_time, $end_time, $available_time, $channel_id, $video_link, $description, $new_price, $old_price, $start_date, $end_date){
				$this->item_type = $item_type;
				$this->title = $title;
				$this->product_link = $link;
				$this->image_link = $img;
				$this->channel_id = $channel_id;
				$this->video_link = $video_link;
				$this->available_time = $available_time;
				$this->description = $description;
				$this->gmt7_start_time = $gmt7_start_time;
				$this->gmt7_end_time = $gmt7_end_time;
				$this->new_price = $new_price;
				$this->old_price = $old_price;
				$this->start_date = $start_date;
				$this->end_date = $end_date;
				$this->start_time = $start_time;
				$this->end_time = $end_time;
			}
		}

		function get_current_time_ISO_8601_GMT_7(){
			return gmdate("Y-m-d\TH:i:s\Z", time() + 7*60*60);
		}

		function get_today_date_GMT_7($dateFormat){
			return gmdate($dateFormat, time() + 7*60*60);
		}

		function get_nextday_date_GMT_7($dateFormat){
			return gmdate($dateFormat, time() + 7*60*60 + 24*60*60);
		}

		function get_current_time_GMT_7($timeFormat){
			return gmdate($timeFormat, time() + 7*60*60);
		}
		function get_unix_time_UTC_from_GMT_7($time_string_hh_mm, $date_string_yyyy_mm_dd){ // ex 22:00, 2014-01-01 GMT + 7
			$time_string_hh_mm = $time_string_hh_mm.":00"; // H:i:s
			$date_string = $date_string_yyyy_mm_dd." ".$time_string_hh_mm; // GMT+7
			return (strtotime($date_string) - 7*60*60);
		}
		function item_type($available_time, $start_date, $end_date){
			
			$currentDate = get_today_date_GMT_7("Y-m-d");
			if ($currentDate >= $end_date) return 2;
			elseif ($currentDate == $start_date){
				list($gmt7_start_time, $gmt7_end_time) = explode("-", $available_time);
				$currentTime = get_current_time_GMT_7("H:i");
				if ($currentTime < $gmt7_start_time) return 1;
				if ($currentTime >= $gmt7_start_time && $currentTime <= $gmt7_end_time) return 0;
				return -1;
			}
		}
		//read api from scj.vn

		$currentTime = get_current_time_GMT_7("H:i");
		$today = get_today_date_GMT_7("Y-m-d");
		$nextDay = get_nextday_date_GMT_7("Y-m-d");
		$API_URL = "http://www.scj.vn/index.php?option=com_broadcasting&task=getEvent&lang=vi&type=day&start=".$today."&end=".$nextDay;

		$json = file_get_contents($API_URL);
		$responses = json_decode($json);
		$start_date = $today;
		$end_date = $nextDay;
		//end of api reading
		$array = array();
		
		foreach ($responses as $product) {
			$available_time = $product->scjtime;
			$channel_id = 1;
			$title = $product->product_name;
			$link = $product->ori_url;
			$img = $product->url;

			list($gmt7_start_time, $gmt7_end_time) = explode("-", $available_time);
			get_unix_time_UTC_from_GMT_7($gmt7_start_time, $start_date);
			$start_time = get_unix_time_UTC_from_GMT_7($gmt7_start_time, $start_date);
			$end_time = get_unix_time_UTC_from_GMT_7($gmt7_end_time, $start_date);
			$new_price = $product->marketprice;
			$old_price = $product->basic_price;
			$description = "Null";
			array_push($array, new Item(item_type($available_time, $start_date, $end_date), $title, $link, $img, $gmt7_start_time, $gmt7_end_time, $start_time, $end_time, $available_time, $channel_id, "rtmp://vtsstr6.sctv.vn/colive", $description, $new_price, $old_price, $start_date, $end_date));
		}
		return Response::json($array);
	});
});


Route::get('api/temporary/channels', function(){
	// $channels = App\Channels::all();
	// return Response::json($channels);
	$json = file_get_contents("http://homeshopping.esy.es/api/temporary/channels");
	echo($json);
});