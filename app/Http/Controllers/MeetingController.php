<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Request as RequestCredit ;
use App\Meeting ;
use App\Http\Resources\UsersJson;
use App\User ;
use Illuminate\Database\QueryException ;

class MeetingController extends Controller
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
        $meeting = Meeting::all() ;
        return UsersJson::collection($meeting) ;
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
      $user = $request->get('user');
      if ( $user->admin ) {
        //$other_user = User::find(5) ;
        $meeting = new Meeting ;
        /*$date = strtotime($request->input('date')) ;
        $meeting->fill([
          'date' => date('Y-m-d',$date) ,
          'user' => 5
        ]);
        $meeting->save();*/
        return UsersJson::collection($meeting) ;
      } else {
        return response('access ', 403) ;
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $user = $request->get('user')->token ;
      if ( Meeting::where('user',$user)->first() ) {
        return UsersJson(Meeting::where('user',$user)->first()) ;
      } else {
        return response(' you dont have') ;
      }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $meeting = Meeting::where($id) ;
        $meeting->update([
          'date' => date('Y-m-d' ,strtotime($request->input('date')))
        ]);
        return UsersJson::collection($meeting) ;
      } else {
        return response('access ', 403) ;
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $meeting = Meeting::find($id) ;
        $meeting->delete();
        return UsersJson::collection($meeting) ;
      } else {
        return response('access ', 403) ;
      }
    }
}
