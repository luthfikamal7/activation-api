<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SerialKeyController extends Controller {
    public function activate(Request $request) {
        $request->validate([
            'serial_code' => 'required|string'
        ]);

        $serialKey = SerialKey::where('serial_code', $request->serial_code)->first();

        if (!$serialKey) {
            return response()->json(['message' => 'Invalid serial code'], 404);
        }

        if ($serialKey->is_used) {
            return response()->json(['message' => 'Serial code already used'], 400);
        }

        // Generate validation key and set expiration date
        $serialKey->update([
            'is_used' => true,
            'validation_key' => Str::random(32),
            'expires_at' => Carbon::now()->addYear()
        ]);

        return response()->json([
            'validation_key' => $serialKey->validation_key,
            'expires_at' => $serialKey->expires_at
        ]);
    }
}

