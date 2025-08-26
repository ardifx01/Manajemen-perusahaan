<div>
    <label for="nama_produk" class="block font-medium text-blue-700 mb-1">Nama Produk</label>
    <input type="text" name="nama_produk" id="nama_produk"
        class="w-full border border-gray-300 px-3 py-2 rounded"
        value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
</div>

<div>
    <label for="harga" class="block font-medium text-blue-700 mb-1">Harga</label>
    <input type="number" name="harga" id="harga"
        class="w-full border border-gray-300 px-3 py-2 rounded"
        value="{{ old('harga', $produk->harga ?? '') }}" required>
</div>
