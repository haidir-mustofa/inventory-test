<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Inventory Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">📦 Sistem Inventory Sederhana (Laravel 10)</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row g-4">
            <!-- Kolom Form -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header {{ isset($product) ? 'bg-warning text-dark' : 'bg-primary text-white' }}">
                        {{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($product) ? route('product.update', $product->id) : route('product.store') }}" method="POST">
                            @csrf
                            @if(isset($product))
                            @method('PUT')
                            @endif
                            <div class="mb-2">
                                <label class="form-label">Kode Produk</label>
                                <input type="text" name="kode_produk" class="form-control" value="{{ $product->kode_produk ?? old('kode_produk') }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" value="{{ $product->nama_produk ?? old('nama_produk') }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Satuan (pcs/box/dll)</label>
                                <input type="text" name="satuan" class="form-control" value="{{ $product->satuan ?? old('satuan') }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" name="stok" class="form-control" min="0" value="{{ $product->stok ?? old('stok') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Satuan</label>
                                <input type="number" name="harga_satuan" class="form-control" min="0" value="{{ $product->harga_satuan ?? old('harga_satuan') }}" required>
                            </div>

                            @if(isset($product))
                            <button type="submit" class="btn btn-warning w-100 mb-2">Update Produk</button>
                            <a href="{{ url('/') }}" class="btn btn-secondary w-100">Batal</a>
                            @else
                            <button type="submit" class="btn btn-primary w-100">Simpan Produk</button>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Input Transaksi Stok</div>
                    <div class="card-body">
                        <form action="{{ route('transaction.store') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Pilih Produk</label>
                                <select name="product_id" class="form-select" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach(\App\Models\Product::all() as $p)
                                    <option value="{{ $p->id }}">{{ $p->kode_produk }} - {{ $p->nama_produk }} (Stok: {{ $p->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Jenis Transaksi</label>
                                <select name="jenis" class="form-select" required>
                                    <option value="masuk">Masuk</option>
                                    <option value="keluar">Keluar</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" min="1" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Proses Transaksi</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Tabel Data & Search -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ url('/') }}" method="GET" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan kode atau nama produk..." value="{{ $search ?? '' }}">
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                            @if($search)
                            <a href="{{ url('/') }}" class="btn btn-outline-danger">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">Daftar Produk</div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
                                    <th>Harga Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $prod)
                                <tr>
                                    <td><code>{{ $prod->kode_produk }}</code></td>
                                    <td>{{ $prod->nama_produk }}</td>
                                    <td>{{ $prod->satuan }}</td>
                                    <td><strong>{{ $prod->stok }}</strong></td>
                                    <td>Rp {{ number_format($prod->harga_satuan, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('product.edit', $prod->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('product.destroy', $prod->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada data produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">Riwayat Transaksi Stok</div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                <tr>
                                    <td>{{ $trx->tanggal }}</td>
                                    <td>{{ $trx->product->nama_produk ?? '-' }}</td>
                                    <td>
                                        @if($trx->jenis == 'masuk')
                                        <span class="badge bg-success">Masuk</span>
                                        @else
                                        <span class="badge bg-danger">Keluar</span>
                                        @endif
                                    </td>
                                    <td>{{ $trx->jumlah }}</td>
                                    <td>{{ $trx->keterangan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>