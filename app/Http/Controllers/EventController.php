<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // ... fungsi index, dll ...

    public function show(Event $event)
    {
        // Ambil data kategori jika halaman detail membutuhkan data kategori untuk navbar/footer
        $categories = Category::all();

        // Oper data event ke blade event-detail
        return view('event-detail', compact('event', 'categories'));
    }
}