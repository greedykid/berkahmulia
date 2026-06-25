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
                <p class="text-[11px] text-slate-500 mt-0.5">Tambah, ubah, atau hapus kolom dan baris pada tabel panduan ukuran.</p>
            </div>
            <button type="button" onclick="addRow()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center gap-2 transition-all shadow-sm cursor-pointer">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Baris</span>
            </button>
        </div>

        <!-- Kolom Custom Header -->
        <div class="mb-6 bg-slate-50 p-6 border border-slate-200 rounded-2xl">
            <h5 class="font-bold text-slate-700 text-[11px] uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-500"></i>
                <span>Daftar Kolom Tabel</span>
            </h5>
            <div id="columns-container" class="flex flex-wrap gap-3 items-center mb-4">
                <!-- Columns inputs will be dynamically rendered here -->
            </div>
            <button type="button" onclick="addColumn()" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-all shadow-sm cursor-pointer">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Kolom</span>
            </button>
        </div>

        <!-- Toggle View (Mobile Only) -->
        <div class="flex sm:hidden justify-end mb-4">
            <div class="inline-flex rounded-xl p-0.5 bg-slate-100 border border-slate-200">
                <button type="button" onclick="setViewMode('grid')" id="btn-view-grid"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 bg-white text-indigo-600 shadow-sm">
                    <i class="fa-solid fa-grip text-[10px]"></i>
                    <span>Grid</span>
                </button>
                <button type="button" onclick="setViewMode('table')" id="btn-view-table"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 text-slate-500 hover:text-slate-700">
                    <i class="fa-solid fa-table text-[10px]"></i>
                    <span>Tabel</span>
                </button>
            </div>
        </div>

        <!-- Size Guide Table (Desktop) -->
        <div id="size-guide-table-container" class="hidden sm:block overflow-x-auto rounded-xl border border-slate-200 mb-5">
            <table class="min-w-full text-sm" id="size-guide-table">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-semibold text-slate-500 text-left">
                        <!-- Dynamic THs -->
                    </tr>
                </thead>
                <tbody id="size-guide-body" class="divide-y divide-slate-100">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>
        </div>

        <!-- Size Guide Cards (Mobile) -->
        <div class="sm:hidden space-y-3 mb-5" id="size-guide-mobile">
            <!-- Dynamic Mobile Cards -->
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

<!-- Custom Confirm Delete Column Modal -->
<div id="delete-column-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity animate-fade-in" onclick="closeDeleteColumnModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 transform scale-95 transition-all duration-300 ease-out flex flex-col items-center text-center">
        <!-- Warning Icon -->
        <div class="w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center mb-3">
            <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg"></i>
        </div>
        
        <h3 class="text-sm font-bold text-slate-800 mb-2">Hapus Kolom</h3>
        <p class="text-[11px] text-slate-500 leading-relaxed px-2">
            Apakah Anda yakin ingin menghapus kolom ini? Seluruh data dalam kolom ini akan ikut terhapus di setiap baris.
        </p>
        
        <div class="flex gap-2 w-full mt-5">
            <button type="button" onclick="closeDeleteColumnModal()" 
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2 rounded-lg text-xs transition-all cursor-pointer">
                Batal
            </button>
            <button type="button" onclick="confirmDeleteColumn()"
                    class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2 rounded-lg text-xs transition-all cursor-pointer">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // State initialization
    let state = {
        columns: @json($columns),
        rows: @json($rows)
    };

    function escapeHtml(str) {
        if (typeof str !== 'string') return '';
        return str.replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/"/g, "&quot;")
                  .replace(/'/g, "&#039;");
    }

    function renderTable() {
        // Render columns list in settings panel
        const columnsContainer = document.getElementById('columns-container');
        columnsContainer.innerHTML = '';
        state.columns.forEach((column, colIndex) => {
            const div = document.createElement('div');
            div.className = 'column-item flex items-center bg-white border border-slate-200 rounded-xl pl-3 pr-1 py-1 gap-2 shadow-sm animate-fade-in';
            div.innerHTML = `
                <span class="text-xs font-semibold text-slate-400">${colIndex + 1}</span>
                <input type="text" name="columns[]" value="${escapeHtml(column)}" required
                       class="column-input border-0 p-0 text-xs font-bold text-slate-700 focus:ring-0 focus:outline-none w-28 bg-transparent"
                       placeholder="Nama Kolom..."
                       oninput="updateColumnName(${colIndex}, this.value)">
                <button type="button" onclick="removeColumn(${colIndex})" class="text-rose-500 hover:text-rose-700 p-1 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            `;
            columnsContainer.appendChild(div);
        });

        // Render desktop table headers
        const theadTr = document.querySelector('#size-guide-table thead tr');
        theadTr.innerHTML = '';
        state.columns.forEach((column, colIndex) => {
            const th = document.createElement('th');
            th.className = 'py-3 px-4 text-xs font-semibold text-slate-500 text-left size-header-' + colIndex;
            th.textContent = column || `Kolom ${colIndex + 1}`;
            theadTr.appendChild(th);
        });
        const thAction = document.createElement('th');
        thAction.className = 'py-3 px-4 w-16 min-w-[64px] text-center text-xs font-semibold text-slate-500';
        thAction.textContent = 'Aksi';
        theadTr.appendChild(thAction);

        // Render desktop table body
        const tbody = document.getElementById('size-guide-body');
        tbody.innerHTML = '';
        state.rows.forEach((row, rowIndex) => {
            const tr = document.createElement('tr');
            tr.className = 'size-row hover:bg-slate-50/50 transition-colors';
            
            // Render a cell for each column
            state.columns.forEach((_, colIndex) => {
                const cellVal = row[colIndex] || '';
                const td = document.createElement('td');
                td.className = 'py-2.5 px-4';
                const widthCh = Math.max(cellVal.length + 3, 12);
                td.innerHTML = `
                    <input type="text" name="rows[${rowIndex}][]" value="${escapeHtml(cellVal)}" required
                           style="width: ${widthCh}ch"
                           class="border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                           placeholder="Isi data..."
                           oninput="updateCellValue(${rowIndex}, ${colIndex}, this.value); adjustInputWidth(this)">
                `;
                tr.appendChild(td);
            });

            const tdAction = document.createElement('td');
            tdAction.className = 'py-2.5 px-4 text-center min-w-[64px]';
            tdAction.innerHTML = `
                <button type="button" onclick="removeRow(${rowIndex})" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-all cursor-pointer">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            `;
            tr.appendChild(tdAction);
            tbody.appendChild(tr);
        });

        // Render mobile cards
        const mobileContainer = document.getElementById('size-guide-mobile');
        mobileContainer.innerHTML = '';
        state.rows.forEach((row, rowIndex) => {
            const card = document.createElement('div');
            card.className = 'size-row-mobile bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3 relative';
            
            let fieldsHtml = '';
            state.columns.forEach((column, colIndex) => {
                const cellVal = row[colIndex] || '';
                const columnName = column || `Kolom ${colIndex + 1}`;
                fieldsHtml += `
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 col-label-${colIndex}">${escapeHtml(columnName)}</label>
                         <input type="text" value="${escapeHtml(cellVal)}" required
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all bg-white"
                               placeholder="Isi data..."
                               oninput="updateCellValue(${rowIndex}, ${colIndex}, this.value)">
                    </div>
                `;
            });

            card.innerHTML = `
                ${fieldsHtml}
                <button type="button" onclick="removeRow(${rowIndex})" class="w-full flex items-center justify-center gap-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 py-2 rounded-lg transition-all cursor-pointer text-xs font-semibold">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span>Hapus Baris</span>
                </button>
            `;
            mobileContainer.appendChild(card);
        });
    }

    function updateColumnName(colIndex, value) {
        state.columns[colIndex] = value;
        // Update header & label texts without full redraw to avoid losing cursor focus
        const th = document.querySelector(`.size-header-${colIndex}`);
        if (th) {
            th.textContent = value || `Kolom ${colIndex + 1}`;
        }
        const labels = document.querySelectorAll(`.col-label-${colIndex}`);
        labels.forEach(lbl => {
            lbl.textContent = value || `Kolom ${colIndex + 1}`;
        });
    }

    function adjustInputWidth(input) {
        if (!input) return;
        const len = input.value ? input.value.length : 0;
        input.style.width = Math.max(len + 3, 12) + 'ch';
    }

    function updateCellValue(rowIndex, colIndex, value) {
        // Ensure rows entry exists and has enough length
        if (!state.rows[rowIndex]) {
            state.rows[rowIndex] = [];
        }
        state.rows[rowIndex][colIndex] = value;

        // Synchronize in real-time between desktop inputs and mobile inputs
        // to prevent inputs diverging if viewport changes or both are checked.
        const rowInputs = document.querySelectorAll(`#size-guide-body tr:nth-child(${rowIndex + 1}) input`);
        if (rowInputs[colIndex] && rowInputs[colIndex].value !== value) {
            rowInputs[colIndex].value = value;
            adjustInputWidth(rowInputs[colIndex]);
        }
        const cardInputs = document.querySelectorAll(`#size-guide-mobile > div:nth-child(${rowIndex + 1}) input`);
        if (cardInputs[colIndex] && cardInputs[colIndex].value !== value) {
            cardInputs[colIndex].value = value;
        }
    }

    function addColumn() {
        state.columns.push('');
        state.rows.forEach(row => {
            row.push('');
        });
        renderTable();
        
        // Focus the newly added column name input
        const columnInputs = document.querySelectorAll('#columns-container .column-input');
        if (columnInputs.length > 0) {
            columnInputs[columnInputs.length - 1].focus();
        }
    }

    let pendingDeleteColIndex = null;

    function removeColumn(colIndex) {
        if (state.columns.length <= 1) {
            alert('Minimal harus ada 1 kolom.');
            return;
        }
        pendingDeleteColIndex = colIndex;
        openDeleteColumnModal();
    }

    function openDeleteColumnModal() {
        const modal = document.getElementById('delete-column-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeDeleteColumnModal() {
        const modal = document.getElementById('delete-column-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
        pendingDeleteColIndex = null;
    }

    function confirmDeleteColumn() {
        if (pendingDeleteColIndex !== null) {
            const colIndex = pendingDeleteColIndex;
            state.columns.splice(colIndex, 1);
            state.rows.forEach(row => {
                row.splice(colIndex, 1);
            });
            renderTable();
            closeDeleteColumnModal();
        }
    }

    function addRow() {
        const newRow = Array(state.columns.length).fill('');
        state.rows.push(newRow);
        renderTable();

        // Focus first cell of new row (depending on viewport)
        if (window.innerWidth >= 640) {
            const inputs = document.querySelectorAll(`#size-guide-body tr:last-child input`);
            if (inputs.length > 0) inputs[0].focus();
        } else {
            const inputs = document.querySelectorAll(`#size-guide-mobile > div:last-child input`);
            if (inputs.length > 0) inputs[0].focus();
        }
    }

    function removeRow(rowIndex) {
        if (state.rows.length <= 1) {
            alert('Minimal harus ada 1 baris ukuran.');
            return;
        }
        state.rows.splice(rowIndex, 1);
        renderTable();
    }

    let currentViewMode = 'grid'; // default on mobile
    function setViewMode(mode) {
        currentViewMode = mode;
        const gridBtn = document.getElementById('btn-view-grid');
        const tableBtn = document.getElementById('btn-view-table');
        const tableContainer = document.getElementById('size-guide-table-container');
        const mobileContainer = document.getElementById('size-guide-mobile');

        if (!gridBtn || !tableBtn || !tableContainer || !mobileContainer) return;

        if (mode === 'grid') {
            gridBtn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 bg-white text-indigo-600 shadow-sm";
            tableBtn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 text-slate-500 hover:text-slate-700";
            
            tableContainer.className = "hidden sm:block overflow-x-auto rounded-xl border border-slate-200 mb-5";
            mobileContainer.className = "sm:hidden space-y-3 mb-5";
        } else {
            gridBtn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 text-slate-500 hover:text-slate-700";
            tableBtn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 bg-white text-indigo-600 shadow-sm";
            
            tableContainer.className = "block overflow-x-auto rounded-xl border border-slate-200 mb-5";
            mobileContainer.className = "hidden";
        }
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        renderTable();
    });
</script>
@endsection
