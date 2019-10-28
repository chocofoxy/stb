<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UsersJson;
use App\User ;
use Illuminate\Database\QueryException ;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $users = User::all() ;
        return UsersJson::collection($users) ;
      } else {
        return response('access ', 403) ;
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $new_user = new User ;
       $time = strtotime($request->input('daten'));
       try {
       $new_user->fill([
         'nom' => $request->input('nom') ,
         'prenom' => $request->input('prenom') ,
         'email' => $request->input('email')  ,
         'username' => $request->input('username') ,
         'password' => $request->input('password') ,
         'daten' => date('Y-m-d',$time) ,
         'sexe' => $request->input('sexe')
         ]) ;
        $new_user->setToken($request->input('username'));
        $new_user->save(); }
        catch (QueryException $e){
            return response('Username already exists', 500) ;
        }
        return new UsersJson($new_user) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $user = $request->get('user');
      return new UsersJson($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getuser(Request $request , $id)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $user = User::where('token',$id)->first() ;
        return new UsersJson($user) ;
      } else {
        return response('access ', 403) ;
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $user = $request->get('user');
      $time = strtotime($request->input('daten'));
      //return response($request->input('daten'), 500);
      //try {
      $user->update([
        'nom' => $request->input('nom') ,
        'prenom' => $request->input('prenom') ,
        'email' => $request->input('email')  ,
        'password' => $request->input('password') ,
        'daten' => date('Y-m-d',$time)
        ]) ;
        /*}
       catch (QueryException $e){
           return response('Username already exists', 500) ;
       }*/
       return new UsersJson($user) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request , $id)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $user = User::find($id) ;
        $user->delete();
        return new UsersJson($user) ;
      } else {
        return response('access ', 403) ;
      }
    }

}
