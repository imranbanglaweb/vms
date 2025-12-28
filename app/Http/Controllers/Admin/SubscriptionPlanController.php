<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::latest()->get();
        return view('admin.dashboard.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.dashboard.plans.create');
    }

    public function store(Request $request)
    {
        SubscriptionPlan::create([
            'name' => $request->name,
            'slug' => strtolower($request->slug),
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle,
            'vehicle_limit' => $request->vehicle_limit,
            'user_limit' => $request->user_limit,
            'features' => $request->features,
            'is_popular' => $request->is_popular ?? false,
            'is_active' => true
        ]);

        return redirect()->route('admin.plans.index')->with('success','Plan created');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('admin.dashboard.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $plan->update($request->all());
        return redirect()->route('admin.dashboard.plans.index')->with('success','Plan updated');
    }
}
