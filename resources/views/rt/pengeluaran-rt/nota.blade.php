@extends('layouts.rt')

@section('page-title', 'Nota Pengeluaran RT')
@section('back-url', route('manage-rt.shared.pengeluaran.index'))

@push('css')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            /* Hilangkan shadow dan border pada print */
            .card,
            .container-fluid {
                box-shadow: none !important;
                border: none !important;
            }

            body {
                background: white !important;
                margin: 0 !important;
            }

            .ttd {
                page-break-inside: avoid;
            }

        }

        .nota-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            width: 100%;
            /* supaya mengikuti container parent */
            padding: 0 15px;
            margin: 0 auto;
            box-sizing: border-box;
        }


        /* Header tanpa warna dan border */
        .nota-header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        /* Logo di header */
        .nota-header img.logo {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .nota-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .nota-subtitle {
            font-size: 14px;
            color: #444;
            margin-top: 4px;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
            max-width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 600px;
            white-space: nowrap;
        }

        .ttd {
            display: table;
            width: 100%;
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 40px;
            text-align: center;
        }

        .ttd>div {
            display: table-cell;
            padding: 0 20px;
            vertical-align: top;
        }

        .garis-ttd {
            border-bottom: 1px solid #000;
            width: 200px;
            height: 40px;
            margin: 0 auto 10px auto;
        }


        .ttd .col {
            text-align: center;
        }

        .ttd-name {
            font-weight: 600;
            margin-top: 40px;
            font-size: 16px;
        }

        .img-fluid {
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 100%;
            height: auto;
        }

        .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        /* Responsif untuk HP */
        @media (max-width: 576px) {
            .nota-title {
                font-size: 18px;
            }

            .nota-subtitle {
                font-size: 12px;
            }

            .ttd {
                font-size: 0.9rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="nota-container" id="printableArea">


        <!-- Hilangkan card dan card shadow -->
        <div>

            <!-- Header Nota dengan logo -->
            <div class="nota-header">
                <img src="{{ asset('logo.png') }}" alt="Logo RT" class="logo" />
                <div>
                    <h3 class="nota-title mb-1">NOTA PENGELUARAN RT</h3>
                    <p class="nota-subtitle mb-0">
                        <strong>{{ $pengeluaran->rt->name ?? 'RT Tidak Diketahui' }}</strong><br>
                        Tanggal: {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d F Y') }}
                    </p>
                </div>
            </div>

            <div>

                <!-- Info Ringkas -->
                <div class="row g-2 mb-3">
                    <div class="col-6 col-md-4">
                        <strong>Nomor:</strong> {{ $pengeluaran->nomor_nota }}
                    </div>
                    <div class="col-6 col-md-4">
                        <strong>Status:</strong>
                        @if ($pengeluaran->status_konfirmasi === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif ($pengeluaran->status_konfirmasi === 'confirmed')
                            <span class="badge bg-success">Terkonfirmasi</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($pengeluaran->status_konfirmasi) }}</span>
                        @endif
                    </div>
                    <div class="col-12 col-md-4">
                        <strong>Pencatat:</strong> {{ $pengeluaran->nama_pencatat }}
                    </div>
                </div>

                <hr class="my-3">

                <!-- Tabel Responsif -->
                <h5 class="mb-3">Daftar Pengeluaran</h5>
                <div class="table-container">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Item</th>
                                <th class="text-center">Jml</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengeluaran->items as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_item }}</td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
                                    <td>{{ $item->satuan ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>{{ $item->catatan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="5" class="text-end">Total Pengeluaran</td>
                                <td colspan="2" class="bg-light">
                                    <strong>Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Catatan -->
                @if ($pengeluaran->catatan)
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>Catatan:</strong>
                        <p class="mb-0">{{ $pengeluaran->catatan }}</p>
                    </div>
                @endif

                <!-- Bukti Gambar -->
                @if ($pengeluaran->bukti_gambar)
                    <div class="mt-4 text-center">
                        <strong>Bukti Transaksi:</strong><br>
                        <img src="{{ asset('storage/' . $pengeluaran->bukti_gambar) }}" alt="Bukti Gambar"
                            class="img-fluid rounded shadow-sm mt-2" style="max-height: 300px; object-fit: contain;">
                    </div>
                @endif

                <!-- Tanda Tangan -->
                <!-- Tanda Tangan -->
                <div class="ttd">
                    <div>
                        <p>Bendahara RT</p>
                        <div class="garis-ttd"></div>
                        <small>{{ $pengeluaran->rt->bendahara->name ?? 'Nama Bendahara' }}</small>
                    </div>
                    <div>
                        <p>Ketua RT</p>
                        <div class="garis-ttd"></div>
                        <small>{{ $pengeluaran->rt->ketuaRT->name ?? 'Nama Ketua RT' }}</small>
                    </div>
                </div>





                <hr class="my-4">

                <!-- Tombol Aksi (Hanya di layar) -->
                <div class="text-center mt-3 no-print">
                    <a href="{{ route('manage-rt.shared.pengeluaran.index') }}" class="btn btn-secondary mb-2 mb-md-0">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-primary mb-2 mb-md-0">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <button onclick="downloadPDF()" class="btn btn-success">
                        <i class="bi bi-download"></i> Simpan PDF
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        function downloadPDF() {
            const element = document.getElementById('printableArea');
            if (!element) {
                alert("Elemen untuk cetak tidak ditemukan!");
                return;
            }

            const opt = {
                margin: 0.7,
                filename: 'nota-pengeluaran-{{ $pengeluaran->nomor_nota ?? 'unknown' }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 3,
                    logging: true,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'cm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                // tambahkan ini:

                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy']
                }

            };


            if (typeof html2pdf !== 'undefined') {
                html2pdf().set(opt).from(element).save();
            } else {
                alert("Plugin PDF tidak tersedia. Silakan gunakan tombol 'Print' dan pilih 'Save as PDF'.");
                window.print();
            }
        }
    </script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script> --}}
@endpush
