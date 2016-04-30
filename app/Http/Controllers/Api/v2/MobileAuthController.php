<?php

namespace App\Http\Controllers\Api\v2;

use App\Events\User\LoggedIn;
use App\Events\User\LoggedOut;
use App\Events\User\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mailers\UserMailer;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\TwoFactor\Contracts\Authenticatable;
use App\Support\Enum\UserStatus;
use Auth;
use Authy;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;

class MobileAuthController extends Controller
{
    //
    public function postLogin(Request $request){
    	$credentials = array('username' => $request->username, 'password' => $request->password);
    	if(!Auth::validate($credentials)){
    		return response()->json(['status' => false]);
    	}
    	else {
    		$user = Auth::getProvider()->retrieveByCredentials($credentials);

			if ($user->isBanned()) return response()->json(['status' => false]);

    		Auth::login($user);

    		// event(new LoggedIn($user));

    		return response()->json([
    			'status' => true,
    			'data' => $user
    		]);
    	}
    }

    public function logout(){
    	// event(new LoggedOut(Auth::user()));

    	Auth::logout();

    	return response()->json([
    		'status' => true,
    		'msg' => 'Logged out successfully!'
    	]);
    }

	private function isEmail($param) {
		return !Validator::make(
			['username' => $param],
			['username' => 'email']
		)->fails();
	}

    public function postRegister(Request $request){
    	$credentials = $request->only('email', 'username', 'password');
    }
}
