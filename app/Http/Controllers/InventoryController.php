<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhere('kode_produk', 'like', "%{$search}%");
        })->latest()->paginate(10);

        $transactions = StockTransaction::with('product')->latest()->get();

        return view('inventory.index', compact('products', 'transactions', 'search'));
    }


    public function storeProduct(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:products,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::lockForUpdate()->find($request->product_id);

            if ($request->jenis === 'keluar') {
                if ($product->stok < $request->jumlah) {
                    return redirect()->back()->with('error', 'Transaksi ditolak! Stok ' . $product->nama_produk . ' tidak mencukupi (Sisa stok saat ini: ' . $product->stok . ').');
                }
                $product->stok -= $request->jumlah;
            } else {
                $product->stok += $request->jumlah;
            }

            $product->save();
            StockTransaction::create($request->all());

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi stok berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $search = request('search');
        $products = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhere('kode_produk', 'like', "%{$search}%");
        })->latest()->paginate(10);
        $transactions = StockTransaction::with('product')->latest()->get();

        return view('inventory.index', compact('product', 'products', 'transactions', 'search'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'kode_produk' => 'required|unique:products,kode_produk,' . $id,
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->to('/')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
    public function productHistory($id)
    {
        $product = Product::with('transactions')->findOrFail($id);

        return view('inventory.history', compact('product'));
    }
    // REST API (JSON Response)

    public function apiGetProducts(Request $request)
    {
        $search = $request->input('search');
        $products = Product::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhere('kode_produk', 'like', "%{$search}%");
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    public function apiStoreProduct(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:products,kode_produk',
            'nama_produk' => 'required|string',
            'satuan' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    public function apiStoreTransaction(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::lockForUpdate()->find($request->product_id);

            if ($request->jenis === 'keluar') {
                if ($product->stok < $request->jumlah) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Transaksi ditolak! Jumlah keluar melebihi stok saat ini (' . $product->stok . ').'
                    ], 422);
                }
                $product->stok -= $request->jumlah;
            } else {
                $product->stok += $request->jumlah;
            }

            $product->save();
            $transaction = StockTransaction::create($request->all());

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
