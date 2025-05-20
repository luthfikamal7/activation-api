<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SerialKey;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SerialKeyController extends Controller {

    public function list()
    {
        return view('pages.serial-key-list', [
            'serialKeys' => SerialKey::with(['customer', 'project'])->get(),
        ]);
    }
    
    public function edit($id)
    {
        $serialKey = SerialKey::findOrFail($id);
        return view('pages.serial-key-edit', [
            'serialKey' => $serialKey,
            'customers' => Customer::all(),
            'projects' => Project::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $serialKey = SerialKey::findOrFail($id);
        $serialKey->update([
            'customer_id' => $request->customer_id,
            'project_id' => $request->project_id,
        ]);

        return redirect()->route('serial.key.list')->with('success', 'Serial key updated successfully!');
    }

    public function destroy($id)
    {
        $serialKey = SerialKey::findOrFail($id);
        $serialKey->delete();

        return redirect()->route('serial.key.list')->with('success', 'Serial key deleted successfully!');
    }

}

