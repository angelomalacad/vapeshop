<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::where('is_active', true)->get();

        return view('branches.index', compact('branches'));
    }
}