<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('q');

        // Get the sort column and direction from the request (default to 'name' and 'asc' if not provided)
        $sortBy = $request->input('sort_by', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Start the query to retrieve users
        $query = User::query();

        // Apply the search filter if there's a search term
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%");
            });
        }

        // Apply sorting by selected column and direction
        $query->orderBy($sortBy, $sortDirection);

        // Paginate the results (10 users per page)
        $users = $query->paginate(10);

        // Pass the users and the search term back to the view
        return view('users.index', compact('users', 'searchTerm', 'sortBy', 'sortDirection'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'age' => 'required|integer|min:1',
        ]);

        User::create($validated);

        return redirect()->back()->with('success', 'User created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'age' => 'required|integer|min:1',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);
        return redirect()->back()->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
