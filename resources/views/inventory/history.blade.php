<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Transaksi - {{ $product->nama_produk }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>📜 Histori Transaksi Produk</h2>
                <h4 class="text-muted">{{ $product->kode_produk }} - {{ $product->nama_produk }}</h4>
            </div>
            <a href="{{ url('/') }}" class="btn btn-secondary">Kembali ke Beranda</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-1"><strong>Satuan:</strong> {{ $product->satuan }}</p>
                <p class="mb-1"><strong>Stok Saat Ini:</strong> <span class="badge bg-primary fs-6">{{ $product->stok }}</span></p>
                <p class="mb-0"><strong>Harga Satuan:</strong> Rp {{ number_format($product->harga_satuan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Daftar Transaksi Masuk & Keluar</div>
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->transactions()->latest()->get() as $trx)
                        <tr>
                            <td>{{ $trx->tanggal }}</td>
                            <td>
                                @if($trx->jenis == 'masuk')
                                <span class="badge bg-success">Masuk</span>
                                @else
                                <span class="badge bg-danger">Keluar</span>
                                @endif
                            </td>
                            <td><strong>{{ $trx->jumlah }}</strong></td>
                            <td class="text-muted">{{ $trx->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada histori transaksi untuk produk ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>