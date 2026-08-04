<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Payout::with(['vendor', 'order']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payouts = $query->paginate(20);

        $stats = [
            'pending_amount' => Payout::where('status', 'pending')->sum('amount'),
            'paid_amount' => Payout::where('status', 'paid')->sum('amount'),
            'total_payouts' => Payout::count(),
        ];

        return view('admin.payouts.index', compact('payouts', 'stats'));
    }

    public function markPaid(Request $request, Payout $payout)
    {
        $payout->update([
            'status' => 'paid',
            'payout_date' => now(),
            'reference' => $request->reference,
        ]);

        return back()->with('success', 'Payout marked as paid successfully');
    }

    public function markProcessing(Payout $payout)
    {
        $payout->update([
            'status' => 'processing',
        ]);

        return back()->with('success', 'Payout marked as processing');
    }
}
