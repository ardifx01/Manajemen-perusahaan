<div>
    <label for="nama_kendaraan" class="block font-medium text-blue-700 mb-1">Nama Kendaraan</label>
    <input type="text" name="nama_kendaraan" id="nama_kendaraan"
        class="w-full border border-gray-300 px-3 py-2 rounded"
        value="{{ old('nama_kendaraan', $kendaraan->nama_kendaraan ?? '') }}" required>
</div>

<div>
    <label for="no_polisi" class="block font-medium text-blue-700 mb-1">No Polisi</label>
    <input type="text" name="no_polisi" id="no_polisi"
        class="w-full border border-gray-300 px-3 py-2 rounded"
        value="{{ old('no_polisi', $kendaraan->no_polisi ?? '') }}" required>
</div>
