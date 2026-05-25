@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 pb-4 border-b">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Panel Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Modul Pengelolaan Jaringan Partner AmikomEventHub</p>
        </div>
        <div class="mt-4 md:mt-0 text-sm text-gray-500">
            Branch: <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-mono font-bold">ujian-tengah-semester-24.12.3200</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
            <h4 class="font-bold mb-1">Gagal Menyimpan Data!</h4>
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 font-bold">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold mb-4 text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0V9a2 2 0 012-2h2a2 2 0 012 2v12m-6 0h6"></path></svg>
                Registrasi Partner Baru
            </h2>
            <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Perusahaan / Institusi</label>
                    <input type="text" name="name" placeholder="Masukkan nama perusahaan/brand resmi..." required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Upload File Logo Gambar Partner</label>
                    <input type="file" name="logo" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-1">*Format wajib gambar (PNG/JPG), ukuran file maksimal 2MB.</p>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-sm hover:shadow transition text-sm text-center">
                    Simpan Data Partner Resmi
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <h2 class="text-lg font-bold mb-4 text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Pencarian Partner Basic Search
            </h2>
            <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama partner / sponsor pendukung..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-gray-700 transition text-sm">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-xl shadow-sm hover:shadow transition text-sm whitespace-nowrap">
                    Cari Partner
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.partners.index') }}" class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold rounded-xl transition text-sm flex items-center justify-center" title="Reset">
                        Reset
                    </a>
                @endif
            </form>
            <div class="mt-4 p-4 bg-gray-50 border rounded-xl text-xs text-gray-500 leading-relaxed">
                <span class="font-bold text-gray-700 block mb-1">💡 Catatan Demonstrasi UTS:</span>
                Ketika merekam video demo, ketikkan keyword pencarian di atas untuk menunjukkan fungsionalitas syntax filter <code class="bg-gray-200 px-1 py-0.5 rounded font-mono">where('name', 'LIKE', '%...%')</code> lo ke asdos.
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Tabel Entitas Partner Pendukung</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b text-sm font-semibold text-gray-600">
                        <th class="p-4 w-32 text-center">Visual Logo</th>
                        <th class="p-4">Nama Perusahaan/Mitra</th>
                        <th class="p-4 w-72 text-center">Form Update Cepat & Aksi Hapus</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="p-4 text-center">
                            <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="Logo {{ $partner->name }}" class="h-12 w-auto max-w-[100px] object-contain mx-auto bg-gray-50 p-1.5 rounded-lg border shadow-sm">
                        </td>
                        <td class="p-4 font-bold text-gray-900 text-base">
                            {{ $partner->name }}
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col gap-3">
                                <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-3 rounded-xl border border-gray-200 space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $partner->name }}" required class="w-full px-2.5 py-1 border rounded-lg text-xs focus:ring-1 focus:ring-amber-500 focus:outline-none">
                                    <div class="flex gap-1.5 items-center justify-between">
                                        <input type="file" name="logo" class="text-[10px] text-gray-500 file:py-1 file:px-2 file:rounded-md file:border-0 file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 w-40">
                                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white text-[11px] px-2.5 py-1 rounded-md font-bold transition">
                                            Update
                                        </button>
                                    </div>
                                </form>

                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner resmi [{{ $partner->name }}] beserta seluruh file logonya dari sistem server?')" class="text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center bg-red-50 text-red-700 hover:bg-red-100 py-1 rounded-lg font-semibold transition text-xs flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus Partner Permanen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-500 font-medium">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Jaringan data partner pendukung kosong / tidak ditemukan.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection