<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiOdooController extends Controller
{

    public function getUsers(Request $request)
    {
        return $request->header()['token'];
        if ($request->header()['token'] == '4jdwqiogt490QD') {
            return response()->json(['message'=>'Autorizacion']);
        }else{

            return response()->json(['message'=>'No Tienes autorizacion']);
        }
    }

    public function getClients(Request $request)
    {
        if ($request->data1) {
        }

        return response()->json(json_decode($request->data1));
    }
}
