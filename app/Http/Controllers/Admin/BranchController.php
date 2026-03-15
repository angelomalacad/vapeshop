<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display a listing of all branches.
     */
    public function index()
    {
        $branches = Branch::withCount('users')->orderBy('name')->get();
        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created branch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'manager_name' => 'required|string|max:255',
            'opening_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        Branch::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'address' => $request->address,
            'phone' => $request->phone,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'opening_date' => $request->opening_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified branch.
     */
    public function show(Branch $branch)
    {
        $branch->load(['users' => function($query) {
            $query->whereIn('role', ['branch_admin', 'staff']);
        }]);
        
        $inventoryCount = $branch->inventories()->count();
        $stockMovements = $branch->stockMovements()->with('product')->latest()->limit(10)->get();
        
        return view('admin.branches.show', compact('branch', 'inventoryCount', 'stockMovements'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified branch.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'manager_name' => 'required|string|max:255',
            'opening_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $branch->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'address' => $request->address,
            'phone' => $request->phone,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'opening_date' => $request->opening_date,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified branch.
     */
    public function destroy(Branch $branch)
    {
        // Check if branch has any users
        $userCount = $branch->users()->count();
        if ($userCount > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete branch with assigned users. Please reassign or delete users first.');
        }

        // Check if branch has any inventory
        $inventoryCount = $branch->inventories()->count();
        if ($inventoryCount > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete branch with inventory items. Please transfer or remove inventory first.');
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully.');
    }

    /**
     * Toggle branch active status.
     */
    public function toggleStatus(Branch $branch)
    {
        $branch->update(['is_active' => !$branch->is_active]);
        
        return redirect()->back()
            ->with('success', 'Branch status updated successfully.');
    }
}