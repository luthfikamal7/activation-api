<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Support\Str;

class ActivationKeyController extends Controller
{
    public function showForm()
    {
        return view('pages.generate-key', [
            'customers' => Customer::all(),
            'projects' => Project::all(),
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'duration' => 'required|integer',
            'customer_id' => 'required|exists:customers,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $serialCode = strtoupper(Str::random(12)); // random key
        $duration = (int) $request->duration;

        $activationKey = SerialKey::create([
            'serial_code' => $serialCode,
            'is_used' => false,
            'start_at' => null,
            'expires_at' => $request->duration,
            'customer_id' => $request->customer_id,
            'project_id' => $request->project_id,
            'duration' => $request->duration,
        ]);

        return redirect()->back()->with('success', $serialCode);
    }
}
