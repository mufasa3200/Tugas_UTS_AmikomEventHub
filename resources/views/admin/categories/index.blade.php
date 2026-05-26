@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 pb-4 border-b">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Panel Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Modul Pengelolaan Kategori Event AmikomEventHub</p>
        </div>
    </div>  
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <div class="flex-1 text-right">
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700 font-bold">&times;</button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold mb-4 text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Tambah Kategori Baru
            </h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" placeholder="Masukkan nama kategori (contoh: Seminar IT)..." required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm hover:shadow transition text-sm whitespace-nowrap">
                    Simpan Data
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <h2 class="text-lg font-bold mb-4 text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Pencarian Kategori Basic Search
            </h2>
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama kategori yang dicari lalu tekan Enter..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-gray-700 transition text-sm">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-xl shadow-sm hover:shadow transition text-sm whitespace-nowrap">
                    Cari Data
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold rounded-xl transition text-sm flex items-center justify-center" title="Reset Pencarian">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Tabel Entitas Kategori</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b text-sm font-semibold text-gray-600">
                        <th class="p-4 w-24">ID</th>
                        <th class="p-4">Nama Kategori</th>
                        <th class="p-4 w-48 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    @forelse($table_categories as $category)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="p-4 font-mono font-bold text-gray-500">#{{ $category->id }}</td>
                        <td class="p-4 font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="p-4 flex justify-center gap-2">
                            <button onclick="pemicuEditKategori('{{ $category->id }}', '{{ $category->name }}')" class="bg-amber-50 text-amber-700 hover:bg-amber-100 px-3 py-1.5 rounded-lg font-semibold transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11 20H8v-3l9.414-9.414z"></path></svg>
                                Edit
                            </button>
                            
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori [{{ $category->name }}]? Tindakan ini bersifat permanen.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1.5 rounded-lg font-semibold transition flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-500 font-medium">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tidak ada data kategori yang cocok dengan kata kunci Anda.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="form-rahasia-edit-category" method="POST" class="hidden">
    @csrf
    @method('PUT')
    <input type="hidden" name="name" id="field-rahasia-name">
</form>

<script>
    function pemicuEditKategori(id, namaSekarang) {
        let inputBaru = prompt("Form Perubahan Kategori\nSilakan edit nama kategori di bawah ini:", namaSekarang);
        if (inputBaru !== null && inputBaru.trim() !== "") {
            let form = document.getElementById('form-rahasia-edit-category');
            form.action = "/admin/categories/" + id;
            document.getElementById('field-rahasia-name').value = inputBaru.trim();
            form.submit();
        }
    }
</script>
@endsection