<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->with('branch');

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);

        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')->where('is_active', true)->count();
        $inactiveCustomers = User::where('role', 'customer')->where('is_active', false)->count();
        $newThisMonth = User::where('role', 'customer')
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'activeCustomers', 'inactiveCustomers', 'newThisMonth'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.customers.create', compact('branches'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'barangay' => ['required', 'string', 'max:100'],
            'landmark' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:10'],
            'birthdate' => ['required', 'date', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $customer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'barangay' => $request->barangay,
            'landmark' => $request->landmark,
            'zip_code' => $request->zip_code,
            'birthdate' => $request->birthdate,
            'email_verified_at' => now(),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer account created successfully.');
    }

    /**
     * Show edit modal content
     */
    public function modalEdit(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('admin.customers.modals.edit', compact('customer'));
    }

    /**
 * Update a customer account.
 */
public function update(Request $request, User $customer)
{
    if ($customer->role !== 'customer') {
        abort(404);
    }

    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $customer->id],
        'phone' => ['required', 'string', 'max:20'],
        'address' => ['required', 'string', 'max:500'],
        'city' => ['required', 'string', 'max:100'],
        'province' => ['required', 'string', 'max:100'],
        'barangay' => ['required', 'string', 'max:100'],
        'landmark' => ['required', 'string', 'max:255'],
        'zip_code' => ['required', 'string', 'max:10'],
        'birthdate' => ['required', 'date'],
        'is_active' => ['boolean'],
    ]);

    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $updateData = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'city' => $request->city,
        'province' => $request->province,
        'barangay' => $request->barangay,
        'landmark' => $request->landmark,
        'zip_code' => $request->zip_code,
        'birthdate' => $request->birthdate,
        'is_active' => $request->has('is_active'),
    ];

    // Only update password if provided
    if ($request->filled('password')) {
        $validator = Validator::make($request->all(), [
            'password' => ['min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $updateData['password'] = Hash::make($request->password);
    }

    $customer->update($updateData);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Customer account updated successfully.'
        ]);
    }

    return redirect()->route('admin.customers.index')
        ->with('success', 'Customer account updated successfully.');
}

   /**
 * Remove a customer account.
 */
public function destroy(Request $request, User $customer)
{
    if ($customer->role !== 'customer') {
        abort(404);
    }

    $customerName = $customer->name;
    $customer->delete();

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Customer "' . $customerName . '" deleted successfully.'
        ]);
    }

    return redirect()->route('admin.customers.index')
        ->with('success', 'Customer account deleted successfully.');
}

    /**
     * Toggle customer status.
     */
    public function toggleStatus(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Customer account {$status} successfully.");
    }
    /**
 * Show customer details for modal view.
 */
public function show(User $customer)
{
    if ($customer->role !== 'customer') {
        abort(404);
    }

    return view('admin.customers.modals.show', compact('customer'));
}
}
