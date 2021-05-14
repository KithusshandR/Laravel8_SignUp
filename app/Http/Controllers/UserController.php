<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    function index(Request $request)
    {
        $user= User::where('email', $request->email)->first();
       
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Login Sucessfully'
            ];
        
             return response($response, 201);
    }

     //Create 

     public function register (Request $request) {
        $user= User::where('email', $request->email)->first();

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'     
        ]);

        if($validator->fails()){
            return response([
                'errorMessage' => true,
                'message' => 'Validator Error'
            ]);
        }

        if($user){
            return response([
                'errorMessage' => true,
                'message' => 'Email Already Exists'
            ]);
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $result = $user->save();

        if($result){
            return response([
                'errorMessage' => false,
                'message'=>'User Successfully Created'
            ]);
        }
    }

    //Get
    function getUser(){
       
        return User::get();
    }

    //updet
    function update(Request $request){

        $user=User::find($request->id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=$request->password;
        $result=$user->save();
        if($result){
            return ["result"=>"data is updated "];
        }
        else{
            return ["result"=>"data is updated failed"];
        }   
    }

    //delate
    function delete($id){
        $user=User::find($id);
        $result=$user->delete();
        if ($result)
        {
            return ["result"=>"record has been delete"];
        }
        else{
            return ["result"=>"delete oparation is faild"];
        }
    }

}
