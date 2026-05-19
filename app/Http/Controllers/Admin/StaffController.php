<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of staff and branch admins.
     */
    public function index()
    {
        $staff = User::whereIn('role', ['branch_admin', 'staff'])
                    ->with('branch')
                    ->orderBy('name')
                    ->paginate(15);
        
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff account.
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.staff.create', compact('branches'));
    }

    /**
     * Store a newly created staff account.
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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff account created successfully.');
    }

/**
 * Show edit modal content
 */
public function modalEdit(User $staff)
{
    // Ensure we're only editing staff/branch admins
    if (!in_array($staff->role, ['branch_admin', 'staff'])) {
        abort(404);
    }
    
    $branches = Branch::where('is_active', true)->get();
    
    // Use the actual edit modal view
    return view('admin.staff.modals.edit', compact('staff', 'branches'));
}


    /**
     * Update a staff account.
     */
    public function update(Request $request, User $staff)
    {
        // Ensure we're only updating staff/branch admins
        if (!in_array($staff->role, ['branch_admin', 'staff'])) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
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

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff account updated successfully.');
    }

    /**
     * Remove a staff account.
     */
    public function destroy(User $staff)
    {
        // Ensure we're only deleting staff/branch admins
        if (!in_array($staff->role, ['branch_admin', 'staff'])) {
            abort(404);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff account deleted successfully.');
    }

    /**
     * Reset staff password.
     */
    public function resetPassword(Request $request, User $staff)
    {
        if (!in_array($staff->role, ['branch_admin', 'staff'])) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $staff->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Password reset successfully.');
    }
}