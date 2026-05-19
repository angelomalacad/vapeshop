<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\WelcomeStaffMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class BranchAdminController extends Controller
{
    /**
     * Display a listing of branch admins and staff.
     */
    public function index()
    {
        $branchAdmins = User::whereIn('role', ['branch_admin', 'staff'])
                    ->with('branch')
                    ->orderBy('role')
                    ->orderBy('name')
                    ->paginate(15);
        
        $branches = Branch::where('is_active', true)->get();
        
        return view('admin.branch-admin.index', compact('branchAdmins', 'branches'));
    }

    /**
     * Show the form for creating a new branch admin or staff.
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.branch-admin.create', compact('branches'));
    }

    
    /**
 * Store a newly created branch admin or staff.
 */
/**
 * Store a newly created branch admin or staff.
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:branch_admin,staff'],
        'branch_id' => ['required', 'exists:branches,id'],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['nullable', 'string', 'max:500'],
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $plainPassword = $request->password;
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => null, // Not verified yet
            'is_active' => true,
        ]);

        // Send welcome email with verification link
        Mail::to($user->email)->send(new WelcomeStaffMail($user, $plainPassword));
        
        // Trigger email verification notification
        event(new Registered($user));

        return redirect()->route('admin.branch-admin.index')
            ->with('success', 'Branch staff account created successfully. A welcome email with verification link has been sent to ' . $user->email);
            
    } catch (\Exception $e) {
        \Log::error('Error creating user: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating account: ' . $e->getMessage());
    }
}

    /**
     * Show edit modal content
     */
    public function modalEdit(User $branchAdmin)
    {
        if (!in_array($branchAdmin->role, ['branch_admin', 'staff'])) {
            abort(404);
        }
        
        $branches = Branch::where('is_active', true)->get();
        
        return view('admin.branch-admin.modals.edit', compact('branchAdmin', 'branches'));
    }

/**
 * Update a branch admin or staff account.
 */
public function update(Request $request, User $branchAdmin)
{
    if (!in_array($branchAdmin->role, ['branch_admin', 'staff'])) {
        abort(404);
    }

    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $branchAdmin->id],
        'role' => ['required', 'in:branch_admin,staff'],
        'branch_id' => ['required', 'exists:branches,id'],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['nullable', 'string', 'max:500'],
        'is_active' => ['boolean'],
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Update password if provided
    if ($request->filled('password')) {
        $branchAdmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
            'password' => Hash::make($request->password),
        ]);
    } else {
        $branchAdmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);
    }

    return redirect()->route('admin.branch-admin.index')
        ->with('success', 'Account updated successfully.');
}

    /**
     * Remove a branch admin or staff account.
     */
    public function destroy(User $branchAdmin)
    {
        if (!in_array($branchAdmin->role, ['branch_admin', 'staff'])) {
            abort(404);
        }

        $branchAdmin->delete();

        return redirect()->route('admin.branch-admin.index')
            ->with('success', 'Account deleted successfully.');
    }
}