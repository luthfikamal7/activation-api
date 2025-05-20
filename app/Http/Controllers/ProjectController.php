<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function list()
    {
        return view('pages.project-list', [ 
            'projects' => Project::with('customer')->get(),
        ]);
    }

    public function showForm()
    {
        $customers = Customer::all();
        return view('pages.register-project', compact('customers'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|string|max:255', // Not Unique
            'customer_id' => 'required|exists:customers,id',
        ]);

        Project::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'customer_id' => $request->customer_id,
        ]);

        return redirect()->route('project.list')->with('success', 'Project registered successfully!');

    }


    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $customers = Customer::all();
        return view('pages.register-project', compact('project', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->only('name', 'project_id', 'customer_id'));

        return redirect()->route('project.list')->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('project.list')->with('success', 'Project deleted successfully!');
    }
}
