<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'vendor')->with('vendorProfile');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('vendorProfile', function ($qProfile) use ($search) {
                      $qProfile->where('business_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('approval_status')) {
            $status = $request->approval_status;
            $query->whereHas('vendorProfile', function ($q) use ($status) {
                $q->where('approval_status', $status);
            });
        }

        $vendors = $query->paginate(15);

        $stats = [
            'total' => User::where('role', 'vendor')->count(),
            'pending' => User::where('role', 'vendor')->whereHas('vendorProfile', function($q) {
                $q->where('approval_status', 'pending');
            })->count(),
            'approved' => User::where('role', 'vendor')->whereHas('vendorProfile', function($q) {
                $q->where('approval_status', 'approved');
            })->count(),
            'suspended' => User::where('role', 'vendor')->whereHas('vendorProfile', function($q) {
                $q->where('approval_status', 'suspended');
            })->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'stats'));
    }

    public function show(User $vendor)
    {
        if ($vendor->role !== 'vendor') {
            abort(404);
        }

        $vendor->loadMissing('vendorProfile');
        $vendor->loadCount(['products', 'rentalListings', 'auctions']);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(User $vendor)
    {
        if ($vendor->role !== 'vendor' || !$vendor->vendorProfile) {
            abort(404);
        }

        $vendor->vendorProfile->update(['approval_status' => 'approved', 'rejection_reason' => null]);

        return back()->with('success', 'Vendor approved successfully.');
    }

    public function reject(Request $request, User $vendor)
    {
        if ($vendor->role !== 'vendor' || !$vendor->vendorProfile) {
            abort(404);
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $vendor->vendorProfile->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Vendor rejected successfully.');
    }

    public function suspend(User $vendor)
    {
        if ($vendor->role !== 'vendor' || !$vendor->vendorProfile) {
            abort(404);
        }

        $vendor->vendorProfile->update(['approval_status' => 'suspended']);

        return back()->with('success', 'Vendor suspended successfully.');
    }

    public function reinstate(User $vendor)
    {
        if ($vendor->role !== 'vendor' || !$vendor->vendorProfile) {
            abort(404);
        }

        $vendor->vendorProfile->update(['approval_status' => 'approved']);

        return back()->with('success', 'Vendor reinstated successfully.');
    }
}
