<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use DB;

class CountriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $temp = DB::table('country_user')
            ->select('country_id', 'name', 'english_name')
            ->join('countries', 'country_user.country_id', '=', 'countries.id');
        return DB::table('country_note')
            ->select('country_id', 'name', 'english_name')
            ->join('countries', 'country_note.country_id', '=', 'countries.id')
            ->union($temp)
            ->orderBy('country_id', 'asc')
            ->get();
    }
}
