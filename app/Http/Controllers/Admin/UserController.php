<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'guru');
        
        if ($role === 'guru') {
            $data = Guru::orderBy('nama')->paginate(15);
        } else {
            $data = Siswa::with('kelas')->orderBy('nama_lengkap')->paginate(15);
        }

        return view('admin.users', compact('data', 'role'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('admin.users_form', [
            'user' => null,
            'role' => request('role', 'guru'),
            'kelas' => $kelas
        ]);
    }

    public function store(Request $request)
    {
        $role = $request->role;

        if ($role === 'guru') {
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:guru',
                'password' => 'required|min:6',
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'no_hp' => 'required|string',
                'pendidikan_terakhir' => 'required|string',
            ]);

            Guru::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
            ]);
        } else {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|unique:siswa',
                'password' => 'required|min:6',
                'id_kelas' => 'required|exists:kelas,id',
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'no_hp' => 'required|string',
                'nisn' => 'required|string|unique:siswa',
                'tahun_masuk' => 'required|digits:4',
            ]);

            Siswa::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_kelas' => $request->id_kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'nisn' => $request->nisn,
                'tahun_masuk' => $request->tahun_masuk,
            ]);
        }

        return redirect()->route('admin.users.index', ['role' => $role])->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(Request $request, $id)
    {
        $role = $request->get('role', 'guru');
        $kelas = Kelas::orderBy('nama')->get();
        
        if ($role === 'guru') {
            $user = Guru::findOrFail($id);
        } else {
            $user = Siswa::findOrFail($id);
        }

        return view('admin.users_form', compact('user', 'role', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $role = $request->role;

        if ($role === 'guru') {
            $user = Guru::findOrFail($id);
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:guru,email,' . $id,
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'no_hp' => 'required|string',
                'pendidikan_terakhir' => 'required|string',
            ]);

            $data = $request->all();
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }
            $user->update($data);
        } else {
            $user = Siswa::findOrFail($id);
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|unique:siswa,email,' . $id,
                'id_kelas' => 'required|exists:kelas,id',
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'no_hp' => 'required|string',
                'nisn' => 'required|string|unique:siswa,nisn,' . $id,
                'tahun_masuk' => 'required|digits:4',
            ]);

            $data = $request->all();
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }
            $user->update($data);
        }

        return redirect()->route('admin.users.index', ['role' => $role])->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $role = $request->get('role', 'guru');
        
        if ($role === 'guru') {
            Guru::findOrFail($id)->delete();
        } else {
            Siswa::findOrFail($id)->delete();
        }

        return redirect()->route('admin.users.index', ['role' => $role])->with('success', 'User berhasil dihapus!');
    }
}
