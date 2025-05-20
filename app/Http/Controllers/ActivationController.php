<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use App\Models\Customer;
use App\Models\Project;
use Carbon\Carbon;

class ActivationController extends Controller
{
    public function validateSerial(Request $request)
    {
        $request->validate([
            'serial_code' => 'required|string',
            'email' => 'required|email',
            'project_id' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Lookup by project_id (your custom field), not id (PK)
        $project = Project::where('project_id', $request->project_id)->first();
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Now use the internal PK `id` from the matched project
        $serialKey = SerialKey::where('serial_code', $request->serial_code)
            ->where('project_id', $project->id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$serialKey) {
            return response()->json(['message' => 'Serial code not found'], 404);
        }

        if ($serialKey->is_used) {
            return response()->json(['message' => 'Serial code has already been used'], 400);
        }

        $startAt = Carbon::now()->startOfDay();
        $expiresAt = $startAt->copy()->addMonths($serialKey->duration);

        $serialKey->update([
            'start_at' => $startAt,
            'expires_at' => $expiresAt,
            'is_used' => true,
        ]);

        $plainData = [
            'serial_code' => $serialKey->serial_code,
            'email' => $request->email,
            'project_id' => $request->project_id, // still return user-sent value
            'start_at' => $startAt->toDateString(),
            'expires_at' => $expiresAt->toDateString(),
        ];

        $encryptedData = $this->encryptOnly($plainData);

        return response()->json([
            'serial_code' => $serialKey->serial_code,
            'start_at' => $startAt->toDateString(),
            'expires_at' => $expiresAt->toDateString(),
            'encryptedResponse' => $encryptedData        
        ]);
    }

    private function encryptOnly($data)
    {
        $publicKeyPath = storage_path('app/keys/public_key.pem');

        if (!file_exists($publicKeyPath)) {
            return 'Public key not found';
        }

        $publicKey = file_get_contents($publicKeyPath);

        if (!openssl_public_encrypt(json_encode($data), $encrypted, $publicKey)) {
            return 'Encryption failed';
        }

        return base64_encode($encrypted);
    }
}