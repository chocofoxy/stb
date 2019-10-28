<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Request as RequestCredit ;
use App\Http\Resources\UsersJson;
use App\User ;
use Illuminate\Database\QueryException ;

class RequestController extends Controller
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
        $requests = RequestCredit::all() ;
        return UsersJson::collection($requests) ;
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
    { if ( RequestCredit::where('owner' , $request->get('user')->token )->first() ) {
       return response()->json([ 'status' => 402 , 'msg' => 'Username already exists']) ;
      }
      $requestCredit = new RequestCredit ;
      $requestCredit->fill([
        'type' => $request->input('type') ,
        'file' => $request->input('file') ,
        'owner' => $request->get('user')->token  ,
        'montant' => $request->input('montant') ,
        'periode' => $request->input('periode')
        ]) ;
       $requestCredit->save();
       return new UsersJson($requestCredit) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $requestCredit = RequestCredit::where('owner' , $request->get('user')->token )->first() ;
        if ( !$requestCredit ) {
           return response()->json([ 'status' => 403 , 'msg' => 'you don"t have a request']) ;
         }
        return new UsersJson($requestCredit) ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $requestCredit = RequestCredit::where('owner' , $request->get('user')->token )->first() ;
        if ( !$requestCredit ) {
           return response()->json([ 'status' => 403 , 'msg' => 'you don"t have a request']) ;
         } elseif ( $requestCredit->confirmed == 1 ){
            return response()->json([ 'status' => 405 , 'msg' => 'you cant change , its confirmed']) ;
          }
        $requestCredit->delete();
        return new UsersJson($requestCredit) ;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { $requestCredit = RequestCredit::where('owner' , $request->get('user')->token )->first() ;
      if ( !$requestCredit ) {
         return response()->json([ 'status' => 403 , 'msg' => 'you don"t have a request']) ;
       } elseif ( $requestCredit->confirmed == 1 ){
          return response()->json([ 'status' => 405 , 'msg' => 'you cant change , its confirmed']) ;
        }
        $requestCredit->update([
          'type' => $request->input('type') ,
          'file' => $request->input('file') ,
          'montant' => $request->input('montant') ,
          'periode' => $request->input('periode')
          ]) ;
         return new UsersJson($requestCredit) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function decline(Request $request , $id)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $requestCredit = RequestCredit::findOrFail($id) ;
        $requestCredit->update([
          'confirmed' => 1
          ]);
        return new UsersJson($requestCredit) ;
      } else {
        return response('access ', 403) ;
      }
    }

    public function accept(Request $request , $id)
    {
      $user = $request->get('user');
      if ( $user->admin ) {
        $requestCredit = RequestCredit::findOrFail($id) ;
        $requestCredit->update([
          'accepted' => true ,
          'confirmed' => true
          ]);
        return new UsersJson($requestCredit) ;
      } else {
        return response('access ', 403) ;
      }
    }
}
