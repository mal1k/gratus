<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Receiver;
use App\Models\Schedule;
use Illuminate\Http\Request;

class NFCAccessController extends Controller
{
    public function index($name)
    {
        $org = Organization::where('slug', '=', $name)->first();
        return view('organization.nfcaccess.dashboard', compact('org'));
    }
}
