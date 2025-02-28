<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Display a listing of employees with search functionality
    public function index(Request $request)
    {
        // Retrieve search term from request
        $search = $request->input('search');

        // If search term exists, filter employees based on name, email, or position
        if ($search) {
            $employees = Employee::where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('position', 'like', "%$search%")
                ->get();
        } else {
            // Otherwise, return all employees
            $employees = Employee::all();
        }

        // Return the view with the list of employees
        return view('employees.index', compact('employees'));
    }

    // Show the form for creating a new employee
    public function create()
    {
        return view('employees.create');
    }

    // Store a newly created employee in the database
    public function store(Request $request)
    {
        // Validate input data before saving
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|numeric|digits_between:10,15',
            'position' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
        ], [
            // Custom validation messages
            'name.required' => 'The employee name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'phone.required' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must be numeric.',
            'phone.digits_between' => 'The phone number must be between 10 and 15 digits.',
            'position.required' => 'The position is required.',
            'salary.required' => 'The salary is required.',
            'salary.numeric' => 'The salary must be a valid number.',
            'salary.min' => 'The salary must be at least 0.',
        ]);

        // Create and save the employee to the database
        Employee::create($request->all());

        // Redirect to the employee index page
        return redirect()->route('employees.index');
    }

    // Show the form for editing an existing employee
    public function edit($id)
    {
        // Find employee by ID or return 404 error
        $employee = Employee::findOrFail($id);

        // Return the edit view with the employee data
        return view('employees.edit', compact('employee'));
    }

    // Update an existing employee in the database
    public function update(Request $request, $id)
    {
        // Validate input data before updating
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($id)],
            'phone' => 'required|numeric|digits_between:10,15',
            'position' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
        ], [
            // Custom validation messages
            'name.required' => 'The employee name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'phone.required' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must be numeric.',
            'phone.digits_between' => 'The phone number must be between 10 and 15 digits.',
            'position.required' => 'The position is required.',
            'salary.required' => 'The salary is required.',
            'salary.numeric' => 'The salary must be a valid number.',
            'salary.min' => 'The salary must be at least 0.',
        ]);

        // Find the employee by ID and update their data
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());

        // Redirect to the employee index page
        return redirect()->route('employees.index');
    }

    // Delete an employee from the database
    public function destroy($id)
    {
        // Find employee by ID or return 404 error
        $employee = Employee::findOrFail($id);

        // Delete the employee record
        $employee->delete();

        // Redirect to the employee index page
        return redirect()->route('employees.index');
    }
}