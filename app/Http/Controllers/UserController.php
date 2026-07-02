<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    'password' => 'required|string|min:8', 
    'password_confirmation' => 'required|string|min:8|same:password', 
    'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:1048', 
    'role'     => 'required|in:Superadmin,Admin',
], [
    'name.required'     => 'Nama tidak boleh kosong.',
    'name.max'          => 'Nama tidak boleh lebih dari :max karakter.',
    'email.required'    => 'Email tidak boleh kosong.',
    'email.email'       => 'Format email tidak valid.',
    'email.unique'      => 'Email sudah terdaftar.',
    'password.required' => 'Password tidak boleh kosong.',
    'password.min'      => 'Password minimal harus :min karakter.',
    
    // Pesan error tambahan untuk password_confirmation
    'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
    'password_confirmation.min'      => 'Konfirmasi password minimal harus :min karakter.',
    'password_confirmation.same'     => 'Konfirmasi password tidak cocok dengan password utama.',
    
    // Pesan error tambahan untuk avatar
    'avatar.image'      => 'Avatar harus berupa gambar.',
    'avatar.mimes'      => 'Format gambar avatar harus jpeg, png, atau jpg.',
    'avatar.max'        => 'Ukuran avatar tidak boleh lebih dari 1 MB (1048 KB).',
    
    'role.required'     => 'Role harus dipilih.',
    'role.in'           => 'Role harus berupa Superadmin atau Admin.',
]);

        try {

        if ($request->file('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
$validated['password'] = bcrypt($request->password); // Hash the password before storing
$validated['email_verified_at'] = now(); // Set the email verified at timestamp


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
    public function show(User $user)
    {
            return view('users.show', [
            'title' => ' Detail users',
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
            return view('users.edit', [
            'title' => ' Edit users',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
$validated = $request->validate([
    'name'     => 'required|string|max:255',
    'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
    'password' => 'nullable|string|min:8', 
    'password_confirmation' => 'nullable|string|min:8|same:password', 
    'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:1048', 
    'role'     => 'required|in:Superadmin,Admin',
], [
    'name.required'     => 'Nama tidak boleh kosong.',
    'name.max'          => 'Nama tidak boleh lebih dari :max karakter.',
    'email.required'    => 'Email tidak boleh kosong.',
    'email.email'       => 'Format email tidak valid.',
    'email.unique'      => 'Email sudah terdaftar.',
    'password.required' => 'Password tidak boleh kosong.',
    'password.min'      => 'Password minimal harus :min karakter.',
    
    // Pesan error tambahan untuk password_confirmation
    'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
    'password_confirmation.min'      => 'Konfirmasi password minimal harus :min karakter.',
    'password_confirmation.same'     => 'Konfirmasi password tidak cocok dengan password utama.',
    
    // Pesan error tambahan untuk avatar
    'avatar.image'      => 'Avatar harus berupa gambar.',
    'avatar.mimes'      => 'Format gambar avatar harus jpeg, png, atau jpg.',
    'avatar.max'        => 'Ukuran avatar tidak boleh lebih dari 1 MB (1048 KB).',
    
    'role.required'     => 'Role harus dipilih.',
    'role.in'           => 'Role harus berupa Superadmin atau Admin.',
]);
  DB::beginTransaction();
        try {

        if ($request->file('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            if ($user->avatar) {
                // Hapus avatar lama jika ada
                Storage::disk('public')->delete($user->avatar);
            }
        }

        if($request->password) {
            $validated['password'] = bcrypt($request->password); // Hash the password before storing
        } else {
            unset($validated['password']); // Remove password from validated data if not provided
        }

        $user->update($validated);
        DB::commit();
        return to_route('users.index')->withSuccess('Data berhasil di ubah');
        
        } catch (\Exception $e) {
            DB::rollBack();
        return to_route('users.edit', $user)->withError('Data gagal di ubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
        $user->delete();
            if ($user->avatar) {
                // Hapus avatar lama jika ada
                Storage::disk('public')->delete($user->avatar);
            }

             DB::commit();
        return to_route('users.index')->withSuccess('Data berhasil di hapus');
        
        } catch (\Exception $e) {
            DB::rollBack();
        return to_route('users.index')->withError('Data gagal di hapus');
        }
    }
    
}