<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\SupabaseService;

class UserController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('nip', 'ILIKE', "%{$search}%")
                  ->orWhere('id_pegawai', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter by jabatan
        if ($request->has('jabatan') && $request->jabatan != '') {
            $query->where('jabatan', 'ILIKE', "%{$request->jabatan}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->ajax() || $request->get('ajax') == 1) {
            return view('admin.users.partials.table', compact('users'))->render();
        }
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $jabatan = Jabatan::active()->orderBy('nama_jabatan')->get();
        return view('admin.users.create', compact('jabatan'));
    }

    public function createOld()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'id_pegawai' => 'required|unique:users,id_pegawai',
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:users,nip',
            'jabatan' => 'required|string|max:255',
            'pangkat' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,admin,direktur,wadir,kabag-kabid,katimker-staf',
            'password' => 'required|min:8|confirmed',
            'status' => 'required|in:active,non-active,pending',
        ]);

        $validated['password'] = Hash::make($validated['password']);


        $user = User::create($validated);

        // Insert user into Supabase
        $supabaseData = [
            'id_pegawai' => $user->id_pegawai,
            'nama' => $user->nama,
            'nip' => $user->nip,
            'jabatan' => $user->jabatan,
            'pangkat' => $user->pangkat,
            'divisi' => $user->divisi,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        $this->supabase->insert('users', $supabaseData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $jabatan = Jabatan::active()->orderBy('nama_jabatan')->get();
        return view('admin.users.edit', compact('user', 'jabatan'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // If only status is being updated (from index table)
        if ($request->has('status') && count($request->all()) === 3) { // _token, _method, status
            $request->validate([
                'status' => 'required|in:active,non-active,pending',
            ]);
            $user->status = $request->status;
            $user->save();
            return redirect()->route('admin.users.index')->with('success', 'Status user berhasil diubah!');
        }

        $validated = $request->validate([
            'id_pegawai' => 'required|unique:users,id_pegawai,' . $user->id,
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:users,nip,' . $user->id,
            'jabatan' => 'required|string|max:255',
            'pangkat' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,direktur,wadir,kabag-kabid,katimker-staf',
            'status' => 'required|in:active,non-active,pending',
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        try {
            // Generate password random
            $newPassword = Str::random(10);
            
            // Set password langsung (bukan Hash::make)
            // User model akan otomatis hash via 'password' => 'hashed' cast
            $user->password = $newPassword;
            $user->save();

            \Log::info('Admin reset password untuk user', [
                'user_id' => $user->id,
                'id_pegawai' => $user->id_pegawai,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', "Password user {$user->nama} berhasil direset. Password baru: <strong>{$newPassword}</strong>");
        } catch (\Exception $e) {
            \Log::error('Error reset password: ' . $e->getMessage());
            return back()->with('error', 'Gagal mereset password. Coba lagi!');
        }
    }
}
