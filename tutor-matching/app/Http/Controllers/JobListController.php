<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorporateJob;
use App\Services\ListSearchService;

class JobListController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Employer::query();
        \App\Services\ListSearchService::applyPrefectureAddressFilter($query, $request);
        $jobs = $query->orderBy('updated_at', 'desc')->get();
        return view('jobs', compact('jobs'));
    }
}
