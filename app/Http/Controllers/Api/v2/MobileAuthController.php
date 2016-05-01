<?php

namespace App\Http\Controllers\Api\v2;

use App\Events\User\LoggedIn;
use App\Events\User\LoggedOut;
use App\Events\User\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\MobileRegisterRequest;
use App\Mailers\UserMailer;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\TwoFactor\Contracts\Authenticatable;
use App\Support\Enum\UserStatus;
use App;
use Auth;
use Authy;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;

class MobileAuthController extends Controller
{
    private $users;

    /**
     * Create a new authentication controller instance.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users) {
        $this->middleware('guest', ['except' => ['getLogout']]);
        $this->middleware('auth', ['only' => ['getLogout']]);
        $this->middleware('registration', ['only' => ['getRegister', 'postRegister']]);
        $this->users = $users;
    }
    //
    public function postLogin(Request $request){
    	$credentials = array('username' => $request->username, 'password' => $request->password);
    	if(!Auth::validate($credentials)){
    		return response()->json([
                'status' => false,
                'data' => array('message' => 'Incorrect username or password')
            ]);
    	}
    	else {
    		$user = Auth::getProvider()->retrieveByCredentials($credentials);

			if ($user->isBanned()) return response()->json(['status' => false]);

    		Auth::login($user);

    		// event(new LoggedIn($user));

    		return response()->json([
    			'status' => true,
    			'data' => array('user' => $user)
    		]);
    	}
    }

    public function logout(){
    	// event(new LoggedOut(Auth::user()));
        Auth::logout();
    	return response()->json([
    		'status' => true,
            'data' => array('message' => 'Logged out successfully!')
    	]);
    }

	private function isEmail($param) {
		return !Validator::make(
			['username' => $param],
			['username' => 'email']
		)->fails();
	}

    public function postRegister(Request $request){

        $errors = array();
        if (count(App\User::where('email', $request->email)->get()) > 0) {
            array_push($errors, "Email already exists!");
        }
        if (count(App\User::where('username', $request->username)->get()) > 0) {
            array_push($errors, "Username already exists!");
        }
        if ($request->password != $request->password_confirmation) {
            array_push($errors, "Password confirmation does not match!");
        }

        if (count($errors) > 0) {
            return response()->json([
                'status' => false,
                'data' => ['errors' => $errors]
            ]);
        }

        $status = settings('reg_email_confirmation')
        ? UserStatus::UNCONFIRMED
        : UserStatus::ACTIVE;

        // $data = [
        //     'username' => $request->username,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'status' => $status
        // ];

        try {
            $user = $this->users->create(array_merge(
                $request->only('email', 'username', 'password'),
                ['status' => $status]
            ));
        } catch (\Illuminate\Database\QueryException $e){
            return response()->json([
                'status' => false,
                'data' => [
                    'code' => $e->getCode(),
                    'message' => $e->errorInfo[2]
                ]
            ], 500);
        }
        $this->users->setRole($user->id, App\Role::findByName('User'));
        // $this->users->updateSocialNetworks($user->id, []);
        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user
            ]
        ]);
    }
}
