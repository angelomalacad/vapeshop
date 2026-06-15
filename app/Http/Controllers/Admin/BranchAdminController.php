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
     * Display a listing of branch admins, staff, and drivers.
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['branch_admin', 'staff', 'driver'])
                    ->with('branch');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Apply branch filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $branchAdmins = $query->orderBy('role')
                    ->orderBy('name')
                    ->paginate(15)
                    ->withQueryString();

        $branches = Branch::where('is_active', true)->get();

        return view('admin.branch-admin.index', compact('branchAdmins', 'branches'));
    }

    /**
     * Show the form for creating a new branch admin, staff, or driver.
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.branch-admin.create', compact('branches'));
    }

   /**
 * Store a newly created branch admin, staff, or driver.
 */
public function store(Request $request)
{
    // Handle "All Branches" option for drivers
    $branchId = $request->branch_id;
    if ($branchId == 'all') {
        $branchId = null;
    }

    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:branch_admin,driver'],
        'branch_id' => ['nullable'],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['nullable', 'string', 'max:500'],
    ]);

    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // For branch_admin, branch_id is required
    if ($request->role == 'branch_admin' && empty($branchId)) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Branch is required for Branch Admin']);
        }
        return redirect()->back()
            ->withErrors(['branch_id' => 'Branch is required for Branch Admin'])
            ->withInput();
    }

    try {
        $plainPassword = $request->password;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => $request->role,
            'branch_id' => $branchId,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => null,
            'is_active' => true,
        ]);

        // Send welcome email with verification link
        Mail::to($user->email)->send(new WelcomeStaffMail($user, $plainPassword));

        // Trigger email verification notification
        event(new Registered($user));

        $roleName = $request->role == 'branch_admin' ? 'Branch Admin' : 'Driver';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $roleName . ' account created successfully. A welcome email with verification link has been sent to ' . $user->email
            ]);
        }

        return redirect()->route('admin.branch-admin.index')
            ->with('success', $roleName . ' account created successfully. A welcome email with verification link has been sent to ' . $user->email);

    } catch (\Exception $e) {
        \Log::error('Error creating user: ' . $e->getMessage());

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Error creating account: ' . $e->getMessage()]);
        }

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
        if (!in_array($branchAdmin->role, ['branch_admin', 'staff', 'driver'])) {
            abort(404);
        }

        $branches = Branch::where('is_active', true)->get();

        return view('admin.branch-admin.modals.edit', compact('branchAdmin', 'branches'));
    }

 /**
 * Update a branch admin, staff, or driver account.
 */
public function update(Request $request, User $branchAdmin)
{
    if (!in_array($branchAdmin->role, ['branch_admin', 'staff', 'driver'])) {
        abort(404);
    }

    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $branchAdmin->id],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['nullable', 'string', 'max:500'],
        'is_active' => ['boolean'],
        'branch_id' => ['required'],
    ]);

    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Handle "All Branches" option for drivers
    $branchId = $request->branch_id;
    if ($branchId == 'all') {
        $branchId = null;
    }

    // Convert is_active correctly
    $isActive = $request->is_active == '1' ? true : false;

    // Update password if provided
    if ($request->filled('password')) {
        $request->validate([
            'password' => ['min:8', 'confirmed'],
        ]);

        $branchAdmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'branch_id' => $branchId,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $isActive,
            'password' => Hash::make($request->password),
        ]);
    } else {
        $branchAdmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'branch_id' => $branchId,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $isActive,
        ]);
    }

    $roleName = $branchAdmin->role == 'branch_admin' ? 'Branch Admin' : ($branchAdmin->role == 'staff' ? 'Staff' : 'Driver');

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => $roleName . ' account updated successfully.'
        ]);
    }

    return redirect()->route('admin.branch-admin.index')
        ->with('success', $roleName . ' account updated successfully.');
}
    /**
     * Remove a branch admin, staff, or driver account.
     */
    public function destroy(Request $request, $id)
{
    $branchAdmin = User::find($id);

    if (!$branchAdmin) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        return redirect()->back()->with('error', 'User not found');
    }

    if (!in_array($branchAdmin->role, ['branch_admin', 'staff', 'driver'])) {
        abort(404);
    }

    $roleName = $branchAdmin->role == 'branch_admin' ? 'Branch Admin' : ($branchAdmin->role == 'staff' ? 'Staff' : 'Driver');
    $branchAdmin->delete();

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => $roleName . ' account deleted successfully.'
        ]);
    }

    return redirect()->route('admin.branch-admin.index')
        ->with('success', $roleName . ' account deleted successfully.');
}
}
