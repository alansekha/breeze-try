<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response()->view('user.index', [
            'users' => User::orderBy('updated_at', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()->view('user.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $inputs = $request->only(['name', 'email']);
        $inputs['password'] = Hash::make('rahasia123');
        $create = User::create($inputs);

        if ($create) {
            // add flash for the success notification
            session()->flash('notif.success', 'User created successfully!');
            return redirect()->route('user.index');
        }

        return abort(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        return response()->view('user.show', [
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Response
    {
        return response()->view('user.form', [
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $inputs = $request->only(['name', 'email']);
        $update = $user->update($inputs);

        if ($update) {
            session()->flash('notif.success', 'User updated successfully!');
            return redirect()->route('user.index');
        }

        return abort(500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $delete = $user->delete();

        if ($delete) {
            session()->flash('notif.success', 'User deleted successfully!');
            return redirect()->route('user.index');
        }

        return abort(500);
    }
}
