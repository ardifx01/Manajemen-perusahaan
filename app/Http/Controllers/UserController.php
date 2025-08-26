<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('users.create');
    }

    public function index(Request $request)
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        $users = User::query()
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Only admin can create users; allow everyone to view the form
        if (! $request->user()?->is_admin) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menambahkan user.')->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Password hashing is handled by User model cast ('password' => 'hashed')
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        return redirect()->route('users.create')->with('status', 'user-created');
    }

    public function destroy(Request $request, User $user)
    {
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('status', 'user-deleted');
    }
}
