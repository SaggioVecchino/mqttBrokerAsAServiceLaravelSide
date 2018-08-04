<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrokerController extends Controller
{

    function disconnectAllDevices()
    {
        DB::table('devices')->where('connected', true)->update(["connected" => false]);
    }
}
