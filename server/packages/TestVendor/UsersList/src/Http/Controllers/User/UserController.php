<?php

namespace TestVendor\UsersList\Http\Controllers\User;

use TestVendor\UsersList\Http\Requests\User\StoreUserRequest;
use TestVendor\UsersList\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController
{
    /**
     * @return View
     */
    public function index(): View
    {
        $users = User::with('phones')->orderByDesc('id')->paginate(10)->withQueryString();
        return view('userslist::index', compact('users'));
    }

    /**
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = User::create($request->only(['first_name', 'last_name']));
            $phones  = collect($request->input('phones'))
                ->filter()
                ->map(fn ($p) => ['number' => $p]);
            $user->phones()->createMany($phones);
        });

        return back()->with('success', 'User added');
    }

    /**
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('userslist::edit', compact('user'));
    }

    /**
     * @param StoreUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($request, $user) {
            $user->update($request->only(['first_name', 'last_name']));
            $phones = collect($request->input('phones'))->filter();
            $user->phones()->delete();
            $user->phones()->createMany($phones->map(fn ($p) => ['number' => $p])->toArray());
        });

        return redirect()->route('phonebook.index')->with('success', 'User updated');
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return back()->with('success', 'User deleted');
    }
}
