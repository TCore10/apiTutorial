<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Mail;
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
					    	Mail::send('text', ['user' => $user->name], function ($m) use ($user) {
					            //$m->from('work.test.tier5@gmail.com', 'Your Application');
					            $m->to($user->email, $user->name)->subject('Registration!!');
     					   });
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
    /**
    	* delete API
    	*
    	* @return \Illuminate\Http\Response
    	*/
    	public function delete(Request $request) {
    		if($request) {
    			if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
    				$user = User::where('email',$request->email)->first();
    				if ($user) {
    					if ($user->delete()) {
    						User::where('email', $request->email)->delete();
    						return response()->json([
								'status' => true,
								'message' => "User deleted Successfully."
							]);
    					} else {
    						return response()->json([
								'status' => false,
								'message' => "Something went wrong."
							]);
    					}
    				} else {
    					return response()->json([
							'status' => false,
							'message' => "User not found."
						]);
    				}
    			} else {
    				return response()->json([
						'status' => false,
						'message' => "Given email is not valid. PLEASE CHECK EMAIL!!!"
					]);
    			}
    		} else {
    			return response()->json([
					'status' => false,
					'message' => "Please provide email in URL."
				]);
    		}
    		/*$user = User::where('email',$email)->first();
        	if($user->delete()){
            return response()->json([
				'status' => true,
				'message' => "User deleted Successfully."
			]);
	        } else {
	        	return response()->json([
					'status' => false,
					'message' => "Something went Wrong."
				]);
	        }*/
    	}
    	public function update(Request $request) {

    		//dd($request->email);
    		$user = User::where('email',$request->email)->first();
    		//dd($user);
    		if($user) {
				//$user = User::find($email);
			    $user->name = $request->name;
			    $user->password = \Hash::make($request->password);
			    //dd($user->name);
			    $user->save();
			    return response()->json([
					'status' => true,
					'message' => "Successfully Updated."
				]);
			} else {
				return response()->json([
					'status' => false,
					'message' => "Email does not exist"
				]);
			}
    	}
}
