<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use App\Models\Customer;
use App\Models\Project;
use Carbon\Carbon;


class ActivationUpdateController extends Controller
{
    public function validateSerial(Request $request)
    {
        $request->validate([
            'serial_code' => 'required|string',
            'email' => 'required|email',
            'new_project_id' => 'required|string', // for Updating the Serial Key
            'old_project_id' => 'required|string', // for Insert in Log
        ]);

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Lookup by old project_id (your custom field), not id (PK)
        $oldProject = Project::where('project_id', $request->old_project_id)->first();
        if (!$oldProject) {
            return response()->json(['message' => 'Old Project ID not found'], 404);
        }

        // Lookup by new project_id (your custom field), not id (PK)
        $newProject = Project::where('project_id', $request->new_project_id)->first();
        if (!$newProject) {

            // Insert New Project Using the New Project ID from the Request
             $newProject = Project::create([
                'name' => $oldProject->name, // Copy the name from the old project
                'project_id' => $request->new_project_id,
                'customer_id' => $customer->id,
            ]);
        }


        // Now use the internal PK `id` from the matched project
        $serialKey = SerialKey::where('serial_code', $request->serial_code)
            ->where('project_id', $oldProject->id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$serialKey) {
            return response()->json(['message' => 'Serial code not found'], 404);
        }

        if ($serialKey->is_used) { // Check if the serial key is already used for re-apply license

            // return response()->json(['message' => 'Serial code has already been used'], 400);

            // $startAt = Carbon::now()->startOfDay();
            // $expiresAt = $startAt->copy()->addMonths($serialKey->duration);

            $serialKey->update([
                'project_id' => $newProject->id,
                'is_used' => true,
            ]);

            $plainData = [
                'serial_code' => $serialKey->serial_code,
                'email' => $request->email,
                'project_id' => $request->new_project_id, // still return user-sent value
                'start_at' => $serialKey->start_at->toDateString(),
                'expires_at' => $serialKey->expires_at->toDateString(),
            ];

            $encryptedData = $this->encryptOnly($plainData);

            return response()->json([
                'serial_code' => $serialKey->serial_code,
                'start_at' => $serialKey->start_at->toDateString(),
                'expires_at' => $serialKey->expires_at->toDateString(),
                'encryptedResponse' => $encryptedData        
            ]);
        }
        else {
            return response()->json(['message' => 'Serial code has not been used yet '], 400);
        }


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
