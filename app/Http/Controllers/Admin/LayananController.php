<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::orderBy('id')->get();
        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('layanans', 'public');
        }

        Layanan::create($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('gambar')) {
            if ($layanan->gambar) Storage::disk('public')->delete($layanan->gambar);
            $validated['gambar'] = $request->file('gambar')->store('layanans', 'public');
        }

        $layanan->update($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Data layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        if ($layanan->gambar) Storage::disk('public')->delete($layanan->gambar);
        $layanan->delete();

        return back()->with('success', 'Layanan dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3048'], // Validasi Gambar Maks 3MB
            'harga'     => ['required', 'numeric', 'min:0'],
            'kategori'  => ['required', 'in:paket_wedding,makeup_only,tambahan'],
        ]);

        // PERBAIKAN: Mengambil langsung dari request, jika kosong (karena tidak ada di form) akan diisi default '💍'
        $data['icon']      = $request->input('icon') ?? '💍';
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}