<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /** Daftar foto galeri + form tambah. */
    public function index()
    {
        $galeris = Galeri::orderBy('urutan')->orderBy('id')->get();

        return view('admin.galeri.index', compact('galeris'));
    }

    /** Tambah foto galeri baru. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'gambar'    => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'urutan'    => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Galeri::create([
            'judul'     => $validated['judul'],
            'gambar'    => $request->file('gambar')->store('galeri', 'public'),
            'urutan'    => $validated['urutan'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    /** Perbarui foto galeri (ganti gambar opsional). */
    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'gambar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'urutan'    => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'judul'     => $validated['judul'],
            'urutan'    => $validated['urutan'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama bila ada.
            if ($galeri->gambar) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('galeri', 'public');
        }

        $galeri->update($data);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    /** Hapus foto galeri + file-nya. */
    public function destroy(Galeri $galeri)
    {
        if ($galeri->gambar) {
            Storage::disk('public')->delete($galeri->gambar);
        }
        $galeri->delete();

        return back()->with('success', 'Foto galeri dihapus.');
    }
}
