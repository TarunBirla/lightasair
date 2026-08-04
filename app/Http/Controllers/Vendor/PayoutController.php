<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::where('vendor_id', auth()->id())
            ->with('order')
            ->paginate(15);
            
        return view('vendor.payouts.index', compact('payouts'));
    }
}
