<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\PendaftarBantuan;
use App\Models\Customer;
use App\Models\Multipleuploads;
use App\Models\VerifikasiLapangan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'guest',
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Hindari menghapus diri sendiri
        if (auth()->id() === $user->id) {
            return back()->withErrors(['delete' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        // Bersihkan file dan data terkait sebelum akun dihapus
        ProgramBantuan::where('user_id', $user->id)->get()->each(function ($program) {
            if (!empty($program->media)) {
                Storage::disk('public')->delete('program_bantuan/' . $program->media);
            }
        });

        Warga::where('user_id', $user->id)->get()->each(function ($warga) {
            if ($warga->profile_picture) {
                Storage::disk('public')->delete($warga->profile_picture);
            }
        });

        Multipleuploads::where('user_id', $user->id)->get()->each(function ($upload) {
            if ($upload->filename && Storage::disk('public')->exists($upload->filename)) {
                Storage::disk('public')->delete($upload->filename);
            }
        });

        Media::where('user_id', $user->id)->get()->each(function ($media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
        });

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun dan seluruh datanya berhasil dihapus.');
    }
}
