<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function baseAddress()
    {
        $data = DB::table('base_address')->select(['const_name','address'])->get();
        return new JsonResponse($data);
    }

    public function updateRoles(Request $request)
    {
        $data = $request->all();
        Log::info('POST数据',$data);
        return $data;
//        Log
    }
}
