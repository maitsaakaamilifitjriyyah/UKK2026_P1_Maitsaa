<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan semua user beserta detailnya
     */
    public function index()
    {
        // Menggunakan with('detail') jika ada relasi ke user_details
        $data = User::with('detail')->latest()->get();
        
        return view('user.index', compact('data'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:Admin,Employee,User',
            'nik'      => 'required|string|max:20|unique:user_details,nik',
            'name'     => 'required|string|max:255',
            'no_hp'    => 'required|string|max:15',
            'address'  => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'  => $validated['role'],
        ]);

        $user->detail()->create([
            'nik'     => $validated['nik'],
            'name'    => $validated['name'],
            'no_hp'   => $validated['no_hp'],
            'address' => $validated['address'],
            'birth_date' => $validated['birth_date'],
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.create', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|min:6',
            'role'     => 'required|in:Admin,Employee,User',
            'nik'      => ['required', 'string', 'max:20', Rule::unique('user_details', 'nik')->ignore($user->detail->nik ?? null, 'nik')],
            'no_hp'    => 'required|string|max:15',
            'address'  => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $userData = [
        'email' => $request->email,
        'password' => $request->password,
        'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        $user->detail()->update([
        'name'    => $request->name, 
        'nik'     => $request->nik,
        'address' => $request->address,
        'no_hp'   => $request->no_hp,
        'birth_date' => $request->birth_date,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->detail) {
            $user->detail->delete();
        }
        
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }
}