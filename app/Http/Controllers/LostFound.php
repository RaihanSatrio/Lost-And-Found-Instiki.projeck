<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Item;

class LostFound extends Controller
{
    /**
     * Menampilkan halaman katalog barang temuan.
     */
    public function index()
{
    $items = Item::latest()->get();
    
    // Ambil data session login jika ada
    $is_admin = Session::get('admin_logged_in', false);
    $mahasiswa_nim = Session::get('mahasiswa_nim', null);

    return view('lostfound', compact('items', 'is_admin', 'mahasiswa_nim'));
}

// 2. TAMBAH fungsi login mahasiswa menggunakan NIM
public function loginMahasiswa(Request $request)
{
    $request->validate([
        'nim' => 'required|numeric|digits_between:8,12', // Validasi NIM angka 8-12 digit
    ]);

    // Simpan NIM ke dalam session
    Session::put('mahasiswa_nim', $request->nim);
    
    // Pastikan session admin dimatikan jika mahasiswa login
    Session::forget('admin_logged_in'); 

    return redirect()->back()->with('success', 'Mahasiswa dengan NIM ' . $request->nim . ' berhasil login!');
}

// 3. UBAH fungsi login admin lu yang lama biar pake Session key yang jelas
public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // Simulasi jika sukses, set session admin
    Session::put('admin_logged_in', true);
    Session::forget('mahasiswa_nim'); // Hapus session mahasiswa

    return redirect()->back()->with('success', 'Halo Admin ' . $request->username . ', Anda berhasil login!');
}

// 4. TAMBAH fungsi logout buat bersihin semua session
public function logout()
{
    Session::flush(); // Hapus semua session login
    return redirect()->to('/')->with('success', 'Berhasil logout!');
}
    /**
     * Menyimpan laporan barang yang ditemukan oleh user.
     */
    public function store(Request $request)
{
    // 1. Validasi data (Pastiin namanya sama persis dengan atribut 'name' di tag form HTML lu)
    $request->validate([
        'title'    => 'required|string|max:255',
        'category' => 'required',
        'location' => 'required',
        'desc'     => 'required',
        'image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    // 2. Proses simpan gambar ke folder storage
    $path = null;
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('items', 'public');
    }

    // 3. PROSES INPUT KE DATABASE (Pastiin baris ini ketulis bener)
    Item::create([
        'title'    => $request->title,
        'category' => $request->category,
        'image'    => $path,
        'location' => $request->location,
        'desc'     => $request->desc,
    ]);

    // 4. Redirect balik ke halaman utama
    // Kalau nama route utama lu bukan 'dashboard', ganti pake redirect()->to('/') atau route halaman utama lu
    return redirect()->to('/')
                     ->with('success', 'Barang berhasil dipublikasikan!');
}
public function destroy($id)
{
    // 1. Cari data barang berdasarkan ID
    $item = Item::findOrFail($id);

    // 2. Hapus gambar dari folder storage biar gak menuh-menuhin server
    if ($item->image) {
        Storage::disk('public')->delete($item->image);
    }

    // 3. Hapus data dari database
    $item->delete();

    // 4. Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Barang telah diambil dan berhasil dihapus dari sistem!');
}

}