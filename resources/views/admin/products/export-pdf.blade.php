<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk - Berkah Mulia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #3c7c94; padding-bottom: 16px; }
        .header h1 { font-size: 20px; color: #3c7c94; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #64748b; }
        .print-btn { position: fixed; top: 16px; right: 16px; background: #3c7c94; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: bold; z-index: 100; }
        .print-btn:hover { background: #2d5f72; }
        @media print { .print-btn { display: none; } }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        tr:hover { background: #f8fafc; }
        .cat-header { background: #3c7c94; color: white; padding: 6px 12px; font-size: 11px; font-weight: bold; margin-top: 16px; border-radius: 4px; }
        .price { font-weight: bold; color: #1e293b; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .status-ready { background: #ecfdf5; color: #047857; }
        .status-po { background: #fffbeb; color: #b45309; }
        .variants { font-size: 10px; color: #64748b; }
        .footer { margin-top: 24px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 12px; }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <div class="header">
        <h1>Katalog Produk Berkah Mulia</h1>
        <p>Daftar produk tersedia per {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} — Total: {{ $products->count() }} produk</p>
    </div>

    @php $currentCategory = ''; @endphp
    @foreach($products as $prod)
        @if($prod->category->name !== $currentCategory)
            @php $currentCategory = $prod->category->name; @endphp
            <div class="cat-header">{{ $currentCategory }}</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:35%">Nama Produk</th>
                        <th style="width:10%">SKU</th>
                        <th style="width:12%">Harga</th>
                        <th style="width:8%">Status</th>
                        <th style="width:30%">Varian (Ukuran / Stok)</th>
                    </tr>
                </thead>
                <tbody>
        @endif
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $prod->name }}</strong></td>
                        <td>{{ $prod->sku ?: '-' }}</td>
                        <td class="price">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                        <td><span class="status status-{{ $prod->status === 'ready' ? 'ready' : 'po' }}">{{ $prod->status === 'ready' ? 'Ready' : 'PO' }}</span></td>
                        <td class="variants">
                            @foreach($prod->variants as $v)
                                {{ $v->size ?: '-' }} ({{ $v->stock }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                    </tr>
        @if($loop->last || $products[$loop->index + 1]->category->name !== $currentCategory)
                </tbody>
            </table>
        @endif
    @endforeach

    <div class="footer">
        <p>Berkah Mulia — Pakaian Bayi, Anak-anak & Underwear | Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>
</html>
