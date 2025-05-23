<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use App\Models\Customer;
use App\Models\Project;
use Carbon\Carbon;

class ActivationRegisterController extends Controller
{
    public function validateSerial(Request $request)
    {
        $request->validate([
            'serial_code' => 'required|string',
            'customer_name' => 'required|string',
            'email' => 'required|email',
            'project_name' => 'required|string',
            'project_id' => 'required|string',
        ]);

        $serialKey = SerialKey::where('serial_code', $request->serial_code)->first();
        if (!$serialKey) {
            return response()->json(['message' => 'Serial code not found'], 404);

        }

        if ($serialKey->is_used) {
            return response()->json(['message' => 'Serial code has already been used'], 400);
        }

        // Check if the customer exists by email
            $customer = Customer::where('email', $request->email)->first();
            if (!$customer) {

                // Create a new customer if not found
                $customer = Customer::create([
                    'name' => $request->customer_name, 
                    'email' => $request->email,
                ]);
            }

            // Check if the project exists by project_id AND customer_id
            $project = Project::where('project_id', $request->project_id)
            ->where('customer_id', $customer->id)
            ->first();

            if (!$project) {
                // Create a new project if not found
                $project = Project::create([
                    'name' => $request->project_name, 
                    'project_id' => $request->project_id,
                    'customer_id' => $customer->id,
                ]);
                
            }

            //Update the Serial Key with the new project ID and customer ID
            $serialKey->update([
                'project_id' => $project->id,
                'customer_id' => $customer->id,
            ]);


        // Now use the internal PK `id` from the matched project
        $serialKey = SerialKey::where('serial_code', $request->serial_code)
            ->where('project_id', $project->id)
            ->where('customer_id', $customer->id)
            ->first();


        $startAt = Carbon::now()->startOfDay();
        $expiresAt = $startAt->copy()->addMonths($serialKey->duration);

        $serialKey->update([
            'start_at' => $startAt,
            'expires_at' => $expiresAt,
            'is_used' => true,
        ]);

        $plainData = [
            'serial_code' => $serialKey->serial_code,
            'email' => $serialKey->email,
            'project_id' => $serialKey->project_id,
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
