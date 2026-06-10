<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memakai relasi dan pengaturan limit paginasi (10 entri per halaman)
        $events = \App\Models\Event::with('category')->latest()->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil semua data kategori untuk ditampilkan di pilihan (dropdown) form
        $categories = \App\Models\Category::all();

        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Menerapkan validasi data request dari pengguna
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'poster' => 'nullable|image|max:2048' // Maksimal 2MB
        ]);

        if ($request->hasFile('poster')) {
            // Simpan ke direktori storage/app/public/posters
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model
        \App\Models\Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Event $event)
    {
        $categories = \App\Models\Category::all();
        return view('event-detail', compact('categories', 'event'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        // Mengambil semua data kategori untuk pilihan dropdown pada form edit
        $categories = \App\Models\Category::all();

        // Menampilkan halaman edit dengan membawa data event yang akan diubah
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
    $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'poster' => 'nullable|image|max:2048'
        ]); 

        if ($request->hasFile('poster')) {
            // Hapus gambar lama jika sebelumnya sudah memiliki poster
            if ($event->poster_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
            }
            // Upload gambar baru
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Tambahkan mekanisme cek & hapus file poster dari storage lokal
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        // Menghapus data event yang dipilih dari database
        $event->delete();

        // Mengalihkan kembali ke halaman indeks dengan pesan sukses
        return redirect()->route('admin.events.index')
            ->with('success', 'Data event beserta file posternya berhasil dihapus secara permanen.');
    }
}