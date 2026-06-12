@extends('layouts.admin')

@section('title', 'Panduan Ukuran')
@section('page_title', 'Panduan Ukuran')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Panduan Ukuran</h2>
    <p class="text-sm text-slate-500 mt-1">Kelola tabel panduan ukuran yang ditampilkan di halaman detail produk.</p>
</div>

@if ($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-start gap-2 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5"></i>
        <div>
            <p class="text-xs font-bold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('admin.settings.updatePanduanUkuran') }}" method="POST" id="size-guide-form">
    @csrf
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                <i class="fa-solid fa-ruler text-violet-600"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Tabel Panduan Ukuran</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Tambah, ubah, atau hapus baris pada tabel panduan ukuran.</p>
            </div>
            <button type="button" onclick="addSizeRow()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center gap-2 transition-all shadow-sm cursor-pointer">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Baris</span>
            </button>
        </div>

        <!-- Size Guide Table (Desktop) -->
        <div class="hidden sm:block overflow-x-auto rounded-xl border border-slate-200 mb-5">
            <table class="min-w-full text-sm" id="size-guide-table">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-semibold text-slate-500 text-left">
                        <th class="py-3 px-4">Ukuran</th>
                        <th class="py-3 px-4">Tinggi Badan</th>
                        <th class="py-3 px-4">Berat Badan</th>
                        <th class="py-3 px-4 w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="size-guide-body" class="divide-y divide-slate-100">
                    @foreach($sizeGuide as $index => $row)
                    <tr class="size-row hover:bg-slate-50/50 transition-colors">
                        <td class="py-2.5 px-4">
                            <input type="text" name="sizes[{{ $index }}][size]" value="{{ $row['size'] }}" required
                                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                                   placeholder="Contoh: 0-3m / S">
                        </td>
                        <td class="py-2.5 px-4">
                            <input type="text" name="sizes[{{ $index }}][height]" value="{{ $row['height'] }}" required
                                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                                   placeholder="Contoh: 55 - 61 cm">
                        </td>
                        <td class="py-2.5 px-4">
                            <input type="text" name="sizes[{{ $index }}][weight]" value="{{ $row['weight'] }}" required
                                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                                   placeholder="Contoh: 4 - 5.7 kg">
                        </td>
                        <td class="py-2.5 px-4 text-center">
                            <button type="button" onclick="removeSizeRow(this)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-all cursor-pointer">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Size Guide Cards (Mobile) -->
        <div class="sm:hidden space-y-3 mb-5" id="size-guide-mobile">
            @foreach($sizeGuide as $index => $row)
            <div class="size-row-mobile bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3 relative">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Ukuran</label>
                    <input type="text" name="sizes[{{ $index }}][size]" value="{{ $row['size'] }}" required
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                           placeholder="Contoh: 0-3m / S">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tinggi Badan</label>
                        <input type="text" name="sizes[{{ $index }}][height]" value="{{ $row['height'] }}" required
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                               placeholder="55 - 61 cm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Berat Badan</label>
                        <input type="text" name="sizes[{{ $index }}][weight]" value="{{ $row['weight'] }}" required
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                               placeholder="4 - 5.7 kg">
                    </div>
                </div>
                <button type="button" onclick="removeSizeRowMobile(this)" class="w-full flex items-center justify-center gap-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 py-2 rounded-lg transition-all cursor-pointer text-xs font-semibold">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span>Hapus Baris</span>
                </button>
            </div>
            @endforeach
        </div>

        <!-- Note -->
        <div class="mb-5">
            <label for="size-note" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Catatan Kaki</label>
            <textarea name="note" id="size-note" rows="2"
                      class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all resize-none"
                      placeholder="Contoh: *Ukuran di atas adalah estimasi rata-rata standar...">{{ $sizeGuideNote }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Panduan Ukuran</span>
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let rowIndex = {{ count($sizeGuide) }};

    function addSizeRow() {
        // Add to desktop table
        const tbody = document.getElementById('size-guide-body');
        const tr = document.createElement('tr');
        tr.className = 'size-row hover:bg-slate-50/50 transition-colors animate-fade-in';
        tr.innerHTML = `
            <td class="py-2.5 px-4">
                <input type="text" name="sizes[${rowIndex}][size]" required
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                       placeholder="Contoh: 2-3 Tahun">
            </td>
            <td class="py-2.5 px-4">
                <input type="text" name="sizes[${rowIndex}][height]" required
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                       placeholder="Contoh: 86 - 92 cm">
            </td>
            <td class="py-2.5 px-4">
                <input type="text" name="sizes[${rowIndex}][weight]" required
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                       placeholder="Contoh: 13.6 - 15 kg">
            </td>
            <td class="py-2.5 px-4 text-center">
                <button type="button" onclick="removeSizeRow(this)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-all cursor-pointer">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);

        // Add to mobile cards
        const mobileContainer = document.getElementById('size-guide-mobile');
        const card = document.createElement('div');
        card.className = 'size-row-mobile bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3 relative animate-fade-in';
        card.innerHTML = `
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Ukuran</label>
                <input type="text" name="sizes[${rowIndex}][size]" required
                       class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                       placeholder="Contoh: 2-3 Tahun">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tinggi Badan</label>
                    <input type="text" name="sizes[${rowIndex}][height]" required
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                           placeholder="86 - 92 cm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Berat Badan</label>
                    <input type="text" name="sizes[${rowIndex}][weight]" required
                           class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                           placeholder="13.6 - 15 kg">
                </div>
            </div>
            <button type="button" onclick="removeSizeRowMobile(this)" class="w-full flex items-center justify-center gap-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 py-2 rounded-lg transition-all cursor-pointer text-xs font-semibold">
                <i class="fa-solid fa-trash-can text-[10px]"></i>
                <span>Hapus Baris</span>
            </button>
        `;
        mobileContainer.appendChild(card);

        rowIndex++;
        // Focus on first input of new row (responsive)
        if (window.innerWidth >= 640) {
            tr.querySelector('input').focus();
        } else {
            card.querySelector('input').focus();
        }
    }

    function removeSizeRow(btn) {
        const row = btn.closest('tr');
        const tbody = document.getElementById('size-guide-body');
        if (tbody.querySelectorAll('.size-row').length <= 1) {
            alert('Minimal harus ada 1 baris ukuran.');
            return;
        }
        // Also remove corresponding mobile card
        const index = Array.from(tbody.querySelectorAll('.size-row')).indexOf(row);
        const mobileCards = document.querySelectorAll('#size-guide-mobile .size-row-mobile');
        if (mobileCards[index]) {
            mobileCards[index].style.opacity = '0';
            mobileCards[index].style.transform = 'translateX(-10px)';
            mobileCards[index].style.transition = 'all 0.2s ease';
            setTimeout(() => mobileCards[index].remove(), 200);
        }
        row.style.opacity = '0';
        row.style.transform = 'translateX(-10px)';
        row.style.transition = 'all 0.2s ease';
        setTimeout(() => row.remove(), 200);
    }

    function removeSizeRowMobile(btn) {
        const card = btn.closest('.size-row-mobile');
        const mobileContainer = document.getElementById('size-guide-mobile');
        if (mobileContainer.querySelectorAll('.size-row-mobile').length <= 1) {
            alert('Minimal harus ada 1 baris ukuran.');
            return;
        }
        // Also remove corresponding desktop row
        const index = Array.from(mobileContainer.querySelectorAll('.size-row-mobile')).indexOf(card);
        const desktopRows = document.querySelectorAll('#size-guide-body .size-row');
        if (desktopRows[index]) {
            desktopRows[index].remove();
        }
        card.style.opacity = '0';
        card.style.transform = 'translateX(-10px)';
        card.style.transition = 'all 0.2s ease';
        setTimeout(() => card.remove(), 200);
    }
</script>
@endsection
