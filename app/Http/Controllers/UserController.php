<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            return view('users.index', [
            'title' => 'users',
            'users' => User::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            return view('users.create', [
            'title' => ' Tambah users'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
$validated = $request->validate([
    'name'     => 'required|string|max:255',
    'email'    => 'required|string|email|max:255|unique:users,email', 
    'password' => 'required|string|min:8', // Biasanya password butuh minimal karakter
    'password_confirmation' => 'required|string|min:8|same:password', // Biasanya password butuh minimal karakter
    'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:1048', // Jika upload file, atau 'nullable|string' jika hanya simpan path/URL
    'role'     => 'required|in:Superadmin,Admin',
], [
    'name.required'     => 'Nama tidak boleh kosong.',
    'name.max'          => 'Nama tidak boleh lebih dari :max karakter.',
    'email.required'    => 'Email tidak boleh kosong.',
    'email.email'       => 'Format email tidak valid.',
    'email.unique'      => 'Email sudah terdaftar.',
    'password.required' => 'Password tidak boleh kosong.',
    'password.min'      => 'Password minimal harus :min karakter.',
    'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
    'password_confirmation.min'      => 'Konfirmasi password minimal harus :min karakter.',
    'password_confirmation.same'     => 'Konfirmasi password tidak cocok.',
    'avatar.image'      => 'Avatar harus berupa gambar.',
    'role.required'     => 'Role harus dipilih.',
    'role.in'           => 'Role harus berupa Superadmin atau Admin.',
]);

        try {

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        DB::beginTransaction();
        User::create($validated);
        DB::commit();
        return to_route('users.index')->withSuccess('Data berhasil di tambahkan');
        
        } catch (\Exception $e) {
            DB::rollBack();
        return to_route('users.create')->withError('Data gagal di tambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
