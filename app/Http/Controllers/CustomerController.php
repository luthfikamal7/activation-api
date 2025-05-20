<?php

namespace App\Http\Controllers;
use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function showForm()
    {
        return view('pages.register-customer');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('customers.list')->with('success', 'Customer registered successfully!');
    }

    public function list()
    {
        $customers = Customer::all();
        return view('pages.customer-list', compact('customers'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('pages.register-customer', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customers.list')->with('success', 'Customer: "'.$request->name.'" updated successfully!');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.list')->with('success', 'Customer : "'.$customer->name.'"  deleted successfully!');
    }


}
