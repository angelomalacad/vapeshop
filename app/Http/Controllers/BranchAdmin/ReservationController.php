<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\InventoryReservation;
use App\Services\InventoryReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(InventoryReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'branch_inventory_id' => 'required|exists:branch_inventories,id',
            'quantity' => 'required|integer|min:1',
            'reservation_type' => 'required|in:online_order,stock_transfer,pickup',
            'order_id' => 'nullable|exists:orders,id',
            'stock_transfer_id' => 'nullable|exists:stock_transfers,id',
        ]);

        $inventory = BranchInventory::findOrFail($request->branch_inventory_id);

        // Check if inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $result = match($request->reservation_type) {
            'online_order' => $this->reservationService->reserveForOrder(
                $inventory,
                $request->order_id,
                $request->quantity
            ),
            'stock_transfer' => $this->reservationService->reserveForTransfer(
                $inventory,
                $request->quantity,
                $request->stock_transfer_id
            ),
            default => ['success' => false, 'message' => 'Invalid reservation type']
        };

        return response()->json($result);
    }

    public function release($reservationId)
    {
        $reservation = InventoryReservation::findOrFail($reservationId);

        // Check authorization
        if ($reservation->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $result = $this->reservationService->releaseReservation($reservationId);
        return response()->json($result);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'flavor_id' => 'nullable|exists:product_flavors,id',
        ]);

        $result = $this->reservationService->checkAvailability(
            $request->branch_id,
            $request->product_id,
            $request->quantity,
            $request->flavor_id
        );

        return response()->json($result);
    }

    public function activeReservations()
    {
        $branchId = Auth::user()->branch_id;

        $reservations = InventoryReservation::with(['branchInventory.product', 'branchInventory.flavor'])
            ->whereHas('branchInventory', function($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('branch-admin.inventory.reservations', compact('reservations'));
    }
}