<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

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

    public function store(\Illuminate\Http\Request $request)
    {
        // Menerapkan validasi data request dari pengguna sebelum disimpan ke database
        $data = $request->validate([
            'category_id' => 'required',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric'
        ]);

        // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model Event
        \App\Models\Event::create($data);

        // Kembali ke halaman indeks dengan pesan sukses
        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
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

    public function update(\Illuminate\Http\Request $request, Event $event)
    {
        // Menerapkan validasi data request dari pengguna
        $data = $request->validate([
            'category_id' => 'required',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric'
        ]);

        // Memperbarui data lama dengan data baru yang telah divalidasi
        $event->update($data);

        // Kembali ke halaman indeks dengan pesan sukses
        return redirect()->route('admin.events.index')->with('success', 'Rincian data event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Menghapus data event yang dipilih dari database
        $event->delete();

        // Mengalihkan kembali ke halaman indeks dengan pesan sukses
        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}