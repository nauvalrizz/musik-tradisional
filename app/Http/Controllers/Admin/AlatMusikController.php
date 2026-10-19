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
        \Illuminate\Support\Facades\Cache::forget('homepage_data');

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
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'audio' => 'nullable|mimes:mp3|max:10240'
        ], [
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
            'gambar.mimes' => 'Gambar harus format JPG/JPEG/PNG.',
            'audio.max' => 'Ukuran audio maksimal 10 MB.',
            'audio.mimes' => 'Audio harus format MP3.',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('img', 'public');
            kompres_gambar(storage_path('app/public/' . $path));
            $data['gambar'] = $path;
            // copy ke public agar langsung terbaca (assets/img/alat-musik/)
            $dest = public_path('assets/img/alat-musik/' . basename($path));
            if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
            @copy(storage_path('app/public/' . $path), $dest);
        }

        if ($request->hasFile('audio')) {
            $path = $request->file('audio')->store('audio', 'public');
            $data['audio'] = $path;
            // copy ke public agar langsung terbaca audio_alat() (assets/js/audio/)
            $dest = public_path('assets/js/audio/' . basename($path));
            if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
            @copy(storage_path('app/public/' . $path), $dest);
        }

        AlatMusik::create($data);
        \Illuminate\Support\Facades\Cache::forget('homepage_data');

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
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'audio' => 'nullable|mimes:mp3|max:10240'
        ], [
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
            'gambar.mimes' => 'Gambar harus format JPG/JPEG/PNG.',
            'audio.max' => 'Ukuran audio maksimal 10 MB.',
            'audio.mimes' => 'Audio harus format MP3.',
        ]);

        $alat = AlatMusik::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('img', 'public');
            kompres_gambar(storage_path('app/public/' . $path));
            $data['gambar'] = $path;
            // copy ke public agar langsung terbaca (assets/img/alat-musik/)
            $dest = public_path('assets/img/alat-musik/' . basename($path));
            if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
            @copy(storage_path('app/public/' . $path), $dest);
        }

        if ($request->hasFile('audio')) {
            $path = $request->file('audio')->store('audio', 'public');
            $data['audio'] = $path;
            // copy ke public agar langsung terbaca audio_alat() (assets/js/audio/)
            $dest = public_path('assets/js/audio/' . basename($path));
            if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
            @copy(storage_path('app/public/' . $path), $dest);
        }

        $alat->update($data);
        \Illuminate\Support\Facades\Cache::forget('homepage_data');

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
