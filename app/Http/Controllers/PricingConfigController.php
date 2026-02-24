<?php

namespace App\Http\Controllers;

use App\Models\PricingConfig;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class PricingConfigController extends Controller
{
    /**
     * Show the pricing configuration page
     */
    public function index()
    {
        $b2cConfig = PricingConfig::where('channel', 'b2c')->first();
        $b2bConfig = PricingConfig::where('channel', 'b2b')->first();

        return view('settings.pricing', compact('b2cConfig', 'b2bConfig'));
    }

    /**
     * Update pricing configurations
     */
    public function update(Request $request)
    {
        $request->validate([
            'b2c_markup_type' => 'required|in:percentage,fixed',
            'b2c_markup_value' => 'required|numeric|min:0',
            'b2b_markup_type' => 'required|in:percentage,fixed',
            'b2b_markup_value' => 'required|numeric|min:0',
        ]);

        PricingConfig::updateOrCreate(
            ['channel' => 'b2c'],
            [
                'markup_type' => $request->b2c_markup_type,
                'markup_value' => $request->b2c_markup_value,
                'is_active' => $request->has('b2c_is_active'),
            ]
        );

        PricingConfig::updateOrCreate(
            ['channel' => 'b2b'],
            [
                'markup_type' => $request->b2b_markup_type,
                'markup_value' => $request->b2b_markup_value,
                'is_active' => $request->has('b2b_is_active'),
            ]
        );

        Toastr::success('Pricing configuration updated successfully!', 'Success');
        return back();
    }
}
