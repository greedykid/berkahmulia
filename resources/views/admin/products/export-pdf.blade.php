<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Berkah Mulia</title>
    <!-- Import Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            font-size: 11px; 
            color: #334155; 
            padding: 30px; 
            background: #ffffff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Page setup for print */
        @page {
            size: A4;
            margin: 15mm;
        }

        /* Top Action Bar (Screen only) */
        .no-print-bar {
            position: sticky;
            top: 10px;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(248, 250, 252, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .no-print-bar > span {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }
        .print-btn { 
            background: #3c7c94; 
            color: white; 
            border: none; 
            padding: 8px 16px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 11px; 
            font-weight: 700; 
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .print-btn:hover { 
            background: #2d5f72; 
            transform: translateY(-1px);
        }

        /* Header design */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }
        
        /* Navbar style logo letters */
        .brand-name {
            display: flex;
            gap: 0.5px;
            align-items: center;
            line-height: none;
        }
        .logo-letter {
            display: inline-block;
            font-weight: 800;
            font-size: 18px;
            letter-spacing: -0.5px;
            text-shadow: 1px 1px 0 #334155, 
                         -1px -1px 0 #334155, 
                         1px -1px 0 #334155, 
                         -1px 1px 0 #334155;
        }
        .logo-b { color: #f4bb71; }
        .logo-e { color: #95d0b9; }
        .logo-r { color: #80bde5; }
        .logo-k { color: #f9dd6c; }
        .logo-a { color: #b99dc8; }
        .logo-h { color: #96cfb9; }
        .logo-m { color: #f8a06d; }
        .logo-u { color: #96cfb4; }
        .logo-l { color: #fdda68; }
        .logo-i { color: #b99dc8; }
        .logo-a2 { color: #b99dc8; }

        .brand-subtitle {
            font-size: 9.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }
        .doc-title-section {
            text-align: right;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 800;
            color: #3c7c94;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-meta {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 3px;
            font-weight: 550;
        }

        @media print { 
            .no-print-bar { display: none !important; } 
            body { padding: 0; }
            .table-wrapper {
                overflow: visible !important;
                border: none !important;
                border-radius: 0 !important;
                margin-bottom: 0 !important;
            }
        }

        /* Category section */
        .category-section {
            margin-bottom: 28px;
        }
        
        .cat-header { 
            background: #f8fafc; 
            color: #1e293b; 
            padding: 6px 12px; 
            font-size: 11px; 
            font-weight: 700; 
            border-radius: 6px; 
            display: inline-block;
            margin-bottom: 10px;
            border-left: 3px solid #3c7c94;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        /* Table layout & wrapper */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th { 
            background: #f8fafc; 
            padding: 8px 10px; 
            text-align: left; 
            font-size: 9px; 
            font-weight: 700;
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            color: #64748b; 
            border-bottom: 1px solid #cbd5e1; 
        }
        td { 
            padding: 8px 10px; 
            border-bottom: 1px solid #e2e8f0; 
            vertical-align: middle; 
            color: #475569;
            font-size: 10.5px;
        }
        tr:nth-child(even) td {
            background: #fafafb;
        }
        
        .product-name {
            font-weight: 700;
            color: #0f172a;
        }
        .sku-code {
            font-family: monospace;
            color: #64748b;
            font-size: 9.5px;
        }
        .price { 
            font-weight: 700; 
            color: #0f172a; 
        }
        
        /* Status Badges */
        .status-badge { 
            display: inline-block; 
            padding: 2px 6px; 
            border-radius: 5px; 
            font-size: 8px; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-ready { 
            background: #d1fae5; 
            color: #065f46; 
        }
        .status-po { 
            background: #fef3c7; 
            color: #92400e; 
        }
        
        /* Variants tags */
        .variants-container {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .variant-tag {
            background: #f1f5f9;
            color: #475569;
            padding: 1.5px 5px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        /* Responsive styling for mobile */
        @media (max-width: 640px) {
            body {
                padding: 12px;
                font-size: 13px;
            }
            .no-print-bar {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
                padding: 12px 16px;
                text-align: center;
                top: 5px;
                margin-bottom: 16px;
            }
            .no-print-bar > span {
                font-size: 13px;
            }
            .print-btn {
                width: 100%;
                justify-content: center;
                padding: 12px 16px;
                font-size: 13px;
            }
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding-bottom: 16px;
                margin-bottom: 16px;
            }
            .brand-name .logo-letter {
                font-size: 22px;
            }
            .brand-subtitle {
                font-size: 11px;
            }
            .doc-title-section {
                text-align: left;
                width: 100%;
            }
            .doc-title {
                font-size: 16px;
            }
            .doc-meta {
                font-size: 11px;
            }
            .cat-header {
                font-size: 13px;
                padding: 8px 12px;
            }
            table {
                min-width: 650px;
            }
            th {
                font-size: 11px;
                padding: 10px 8px;
            }
            td {
                font-size: 12px;
                padding: 10px 8px;
            }
            .sku-code {
                font-size: 11px;
            }
            .variant-tag {
                font-size: 10.5px;
                padding: 3px 6px;
            }
            .status-badge {
                font-size: 9.5px;
                padding: 3px 8px;
            }
            .table-wrapper {
                margin-bottom: 15px;
            }
            .footer {
                font-size: 10.5px;
                margin-top: 20px;
                padding-top: 12px;
            }
        }

        /* Footer layout */
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            font-size: 8.5px; 
            color: #94a3b8; 
            border-top: 1px solid #f1f5f9; 
            padding-top: 14px; 
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Top Action Bar (Hidden on print) -->
    <div class="no-print-bar">
        <span>Pratinjau Katalog Cetak PDF</span>
        <button class="print-btn" onclick="window.print()">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Header Section -->
    <div class="header-container">
        <div class="logo-section">
            <img src="/logo.webp" alt="Berkah Mulia Logo" class="logo-img">
            <div>
                <div class="brand-name">
                    <span class="logo-letter logo-b">B</span>
                    <span class="logo-letter logo-e">e</span>
                    <span class="logo-letter logo-r">r</span>
                    <span class="logo-letter logo-k">k</span>
                    <span class="logo-letter logo-a">a</span>
                    <span class="logo-letter logo-h">h</span>
                    <span style="width: 4px;"></span>
                    <span class="logo-letter logo-m">M</span>
                    <span class="logo-letter logo-u">u</span>
                    <span class="logo-letter logo-l">l</span>
                    <span class="logo-letter logo-i">i</span>
                    <span class="logo-letter logo-a2">a</span>
                </div>
                <div class="brand-subtitle">Pakaian Bayi, Anak-anak & Underwear</div>
            </div>
        </div>
        <div class="doc-title-section">
            <div class="doc-title">Katalog Produk</div>
            <div class="doc-meta">Per Tanggal: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }} — Total: {{ $products->count() }} Produk</div>
        </div>
    </div>

    @php 
        $currentCategory = ''; 
        $itemIndex = 0;
    @endphp
    @foreach($products as $prod)
        @if($prod->category->name !== $currentCategory)
            @if($currentCategory !== '')
                </tbody>
            </table>
            </div> <!-- Close table-wrapper -->
            </div> <!-- Close category-section -->
            @endif
            @php 
                $currentCategory = $prod->category->name; 
                $itemIndex = 0;
            @endphp
            <div class="category-section">
                <div class="cat-header">{{ $currentCategory }}</div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:5%">No</th>
                                <th style="width:38%">Nama Produk</th>
                                <th style="width:12%">SKU</th>
                                <th style="width:15%">Harga</th>
                                <th style="width:10%">Status</th>
                                <th style="width:20%">Varian (Ukuran / Stok)</th>
                            </tr>
                        </thead>
                        <tbody>
        @endif
                    @php $itemIndex++; @endphp
                    <tr>
                        <td>{{ $itemIndex }}</td>
                        <td class="product-name">{{ $prod->name }}</td>
                        <td class="sku-code">{{ $prod->sku ?: '-' }}</td>
                        <td class="price">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge status-{{ $prod->status === 'ready' ? 'ready' : 'po' }}">
                                {{ $prod->status === 'ready' ? 'Ready' : 'PO' }}
                            </span>
                        </td>
                        <td>
                            <div class="variants-container">
                                @foreach($prod->variants as $v)
                                    <span class="variant-tag">{{ $v->size ?: '-' }} ({{ $v->stock }})</span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
        @if($loop->last)
                    </tbody>
                </table>
                </div> <!-- Close table-wrapper -->
            </div> <!-- Close final category-section -->
        @endif
    @endforeach

    <!-- Footer Section -->
    <div class="footer">
        <p>Berkah Mulia Catalog — Dicetak otomatis pada {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB</p>
    </div>
</body>
</html>
