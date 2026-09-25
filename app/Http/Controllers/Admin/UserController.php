<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $users = User::where(function ($q) use ($search) {
            if ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            }
        })
        ->paginate(10)
        ->withQueryString();

        $roleColors = [
            'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
            'kasir' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        ];

        return view('admin.user.index', compact('users', 'search', 'roleColors'));
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        return view('admin.user.edit', compact('user'));
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        // The UpdateRequest already handles unique:user,username,{id} exclusion.
        // Filter out null/empty values so partial updates don't wipe existing data.
        $data = array_filter(
            $request->validated(),
            fn($value) => ! is_null($value) && $value !== ''
        );

        if (array_key_exists('password', $data)) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.user')->with('success', 'User berhasil diedit!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Berhasil hapus user!');
    }
}
