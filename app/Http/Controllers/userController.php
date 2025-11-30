<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Using the 'view' helper function
        return view('user.index', ['user' => "hello world"]);
        
        // Alternatively, using 'compact' method
        // return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view to create a new user
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Logic to store a new user

        // Redirect back to the user list or show success message
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Logic to retrieve a user by ID
        // $user = User::find($id);
        
        // Return a view to display the user details
        return view('user.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Logic to retrieve a user by ID for editing
        // $user = User::find($id);

        // Return a view to edit the user
        return view('user.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logic to update the user

        // Redirect back to the user list or show success message
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Logic to delete the user

        // Redirect back to the user list or show success message
        return redirect()->route('user.index');
    }
}
