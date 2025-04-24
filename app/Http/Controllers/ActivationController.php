<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use Carbon\Carbon;

class ActivationController extends Controller
{
    /**
     * Validate the serial code and return the encrypted validation key.
     */
    public function validateSerial(Request $request)
    {
        // Validate input
        $request->validate([
            'serial_code' => 'required|string',
        ]);

        // Find the serial key
        $serialKey = SerialKey::where('serial_code', $request->serial_code)->first();

        // Check if the key exists
        if (!$serialKey) {
            return response()->json(['message' => 'Serial code not found'], 404);
        }

        // Check if the key has already been used
        if ($serialKey->is_used) {
            return response()->json(['message' => 'Serial code has already been used'], 400);
        }

        // Set activation start and expiration date
        $startAt = Carbon::today();
        $expiresAt = $startAt->copy()->addYears($serialKey->duration);

        // Update the serial key
        $serialKey->update([
            'is_used' => true,
            'start_at' => $startAt,
            'expires_at' => $expiresAt,
            'updated_at' => Carbon::now(),
        ]);

        // Prepare data to be encrypted
        $plainData = [
            'serial_code' => $serialKey->serial_code,
            'start_at' => $startAt->toDateString(),
            'expires_at' => $expiresAt->toDateString(),
        ];

        // Encrypt data
        $encryptedData = $this->encryptResponse($plainData);

        // Final response
        return response()->json([
            'serial_code' => $serialKey->serial_code,
            'start_at' => $startAt->toDateString(),
            'expires_at' => $expiresAt->toDateString(),
            'encryptedResponse' => $encryptedData
        ], 200);
    }

    private function encryptResponse($data, $status = 200)
    {
        // Load public key
        $publicKeyPath = storage_path('app/keys/public_key.pem'); // Ensure this path exists
        if (!file_exists($publicKeyPath)) {
            return response()->json(['message' => 'Public key not found'], 500);
        }

        $publicKey = file_get_contents($publicKeyPath);

        // Encrypt data
        if (!openssl_public_encrypt(json_encode($data), $encrypted, $publicKey)) {
            return response()->json(['message' => 'Encryption failed'], 500);
        }

        return response()->json(['data' => base64_encode($encrypted)], $status);
    }
}