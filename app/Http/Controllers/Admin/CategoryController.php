<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        
        $categories = Category::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->latest()->get();

        // Soal 1: Return ke halaman index admin kategori
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        Category::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Nama kategori berhasil diubah!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}