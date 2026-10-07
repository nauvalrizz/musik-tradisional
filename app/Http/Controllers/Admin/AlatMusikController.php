<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatMusik;
use Illuminate\Http\Request;
use App\Models\Pulau;

class AlatMusikController extends Controller
{
    public function index()
    {
        $query = AlatMusik::with('pulau');

        if (request('search')) {
            $query->where('nama', 'like', '%' . request('search') . '%');
        }

        $alat = $query->paginate(10);
        $pulau = Pulau::all();
        $kategori = ['Pukul', 'Petik', 'Tiup', 'Gesek', 'Ansambel'];
        $sumber = ['Idiofon', 'Aerofon', 'Kordofon', 'Membranofon'];

        return view('admin.alat.index', compact('alat', 'pulau', 'kategori', 'sumber'));
    }

    public function destroy($id)
    {
        $alat = AlatMusik::findOrFail($id);
        $alat->delete();

        return redirect('/admin/alat')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'pulau_id' => 'required',
            'kategori' => 'required',
            'sumber_bunyi' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio' => 'nullable|mimes:mp3'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('img', 'public');
        }

        if ($request->hasFile('audio')) {
            $data['audio'] = $request->file('audio')->store('audio', 'public');
        }

        AlatMusik::create($data);

        return redirect('/admin/alat')
            ->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'pulau_id' => 'required',
            'kategori' => 'required',
            'sumber_bunyi' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png',
            'audio' => 'nullable|mimes:mp3'
        ]);

        $alat = AlatMusik::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('img', 'public');
        }

        if ($request->hasFile('audio')) {
            $data['audio'] = $request->file('audio')->store('audio', 'public');
        }

        $alat->update($data);

        return redirect('/admin/alat')
            ->with('success', 'Data berhasil diupdate!');
    }

    public function edit($id)
    {
        $alat = AlatMusik::findOrFail($id);
        $pulau = Pulau::all();

        $kategori = ['Pukul', 'Petik', 'Tiup', 'Gesek', 'Ansambel'];
        $sumber = ['Idiofon', 'Aerofon', 'Kordofon', 'Membranofon'];

        return view('admin.alat.edit', compact('alat', 'pulau', 'kategori', 'sumber'));
    }
}
