<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $partners = Partner::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->latest()->get();

        $categories = Category::all();

        return view('admin.partners.index', compact('partners', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = $request->file('logo')->store('partners', 'public');

        Partner::create([
            'name' => $request->name,
            'logo_url' => $path
        ]);

        return redirect()->back()->with('success', 'Mitra Partner berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $partner = Partner::findOrFail($id);
        $path = $partner->logo_url;

        if ($request->hasFile('logo')) {
            if ($partner->logo_url) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            $path = $request->file('logo')->store('partners', 'public');
        }

        $partner->update([
            'name' => $request->name,
            'logo_url' => $path
        ]);

        return redirect()->back()->with('success', 'Data partner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        
        if ($partner->logo_url) {
            Storage::disk('public')->delete($partner->logo_url);
        }
        
        $partner->delete();

        return redirect()->back()->with('success', 'Partner berhasil dihapus dari sistem!');
    }
}