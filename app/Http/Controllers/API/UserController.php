<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
// use Auth;

class UserController extends Controller
{
    public $successStatus = 200;
    /**
    	* login API
    	*
    	* @return \Illuminate\Http\Response
    	*/
    public function login() {
    	if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
    		$user = Auth::user();
    		$success['token'] = $user->createToken('MyApp')->accessToken;
    		return response()->json(['success' => $success], $this->successStatus);
    	}
    	else{
    		return response()->json(['error' => 'Unauthorised'], 401);
    	}
    }
    /**
    	* Register API
    	*
    	* @return \Illuminate\Http\Response
    	*/
    public function register(Request $request) {
    	if ($request) {
    		if($request->email) {
    			if($request->password) {
    				if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
    					$checkEmailExist = User::where('email', $request->email)->first();
    					if ($checkEmailExist) {
    						return response()->json([
								'status' => false,
								'message' => "User already exists. Please login!!!"
							]);
    					} else {
	    					$user = new User();
					    	$user->name = $request->name;
					    	$user->email = $request->email;
					    	$user->password = \Hash::make($request->password);
					    	$user->save();
					    	return response()->json([
								'status' => true,
								'message' => "Successfully added."
							]);
    					}
    				} else {
    					return response()->json([
							'status' => false,
							'message' => "Given email is not a valid email. PLEASE CHECK IT!!!"
						]);
    				}
    			} else {
    				return response()->json([
						'status' => false,
						'message' => "Please provide password."
					]);
    			}
    		} else {
    			return response()->json([
					'status' => false,
					'message' => "Please provide email."
				]);
    		}
    	} else {
    		return response()->json([
				'status' => false,
				'message' => "Please provide some data."
			]);
    	}
    }
    /**
    	* details API
    	*
    	* @return \Illuminate\Http\Response
    	*/
    	public function details() {
    		$user = Auth::user();
    		return response()->json(['success' => $user], $this->successStatus);
    	}
}
