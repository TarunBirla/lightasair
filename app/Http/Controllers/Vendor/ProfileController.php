<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;
        
        return view('vendor.profile', compact('user', 'vendorProfile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'website' => 'nullable|url',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'postcode' => 'nullable|string',
            'country' => 'nullable|string',
            'vat_number' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        $data = $request->except(['logo']);

        if ($request->hasFile('logo')) {
            if ($vendorProfile && $vendorProfile->logo_path) {
                Storage::disk('public')->delete($vendorProfile->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('vendors', 'public');
        }

        if ($vendorProfile) {
            $vendorProfile->update($data);
        } else {
            $user->vendorProfile()->create($data);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
