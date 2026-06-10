
<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lost & Found Center - Kampus</title>

    <style>
        

        :root { --primary: #2c3e50; --accent: #e74c3c; --bg: #f4f7f6; --text: #333; --white: #ffffff; --gray: #7f8c8d; }

        * { box-sizing: border-box; transition: all 0.3s ease; }

        body { font-family: 'Segoe UI', sans-serif; background-color: var(--bg); color: var(--text); margin: 0; }
        [data-theme="dark"] {
    --bg: #1a1a1a;
    --text: #f4f4f4;
    --white: #2d2d2d;
    --card-bg: #252525;
}       /* WAJIB: Terapkan variabel ke elemen body */
        body { 
            background-color: var(--bg) !important; 
            color: var(--text) !important; 
        }

        .card, .login-card { 
            background-color: var(--card-bg) !important; 
            color: var(--text); 
        }

       

        /* Navigasi */

        nav { background: var(--primary); padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; color: white; position: sticky; top: 0; z-index: 1000; }

        .nav-brand { font-weight: bold; font-size: 1.2rem; white-space: nowrap; }



        .nav-menu { display: flex; gap: 5px; }

        .nav-link {

            background: none; border: none; color: rgba(255, 255, 255, 0.7);

            cursor: pointer; font-weight: 600; font-size: 0.9rem; padding: 8px 12px;

            border-radius: 6px;

        }

        .nav-link:hover { color: white; background: rgba(255, 255, 255, 0.1); }

        .nav-link.active { color: white; border-bottom: 2px solid var(--accent); border-radius: 6px 6px 0 0; background: rgba(255, 255, 255, 0.1); }



        .btn-nav { background: transparent; border: 2px solid white; color: white; padding: 8px 20px; border-radius: 6px; cursor: pointer; margin-left: 10px; font-weight: 600; }

        .btn-nav:hover { background: white; color: var(--primary); }



        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }



        /* Katalog */

        .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }

        .card { background: var(--white); border-radius: 15px; overflow: hidden; border: 1px solid #eee; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .card img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; }

        .tag { background: #ebedef; padding: 5px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; color: var(--primary); }

        .btn-claim { display: block; text-align: center; background: var(--accent); color: white; padding: 12px; margin-top: 20px; border-radius: 8px; text-decoration: none; font-weight: bold; }



        /* Section Global */

        #login-section, #report-section { display: none; min-height: 70vh; justify-content: center; align-items: center; }

        .login-card { background: var(--white); padding: 40px; border-radius: 20px; width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }

        .form-group { margin-bottom: 20px; }

        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }

        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 14px; border: 2px solid #f0f0f0; border-radius: 10px; font-size: 1rem; font-family: inherit; }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--accent); outline: none; background: #fffafa; }



        .btn-submit { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; font-size: 1rem; }

        .btn-report { background: var(--accent); }

        .btn-report:hover { background: #c0392b; }

        

    </style>

</head>

<nav>
    <div style="display: flex; align-items: center; gap: 20px;">
        <div class="nav-brand">CAMPUS LOST & FOUND</div>
        <div class="nav-menu">
            <button class="nav-link active" data-cat="all" onclick="filterCategory('all', this)">Semua</button>
            <button class="nav-link" data-cat="Elektronik" onclick="filterCategory('Elektronik', this)">Elektronik</button>
            <button class="nav-link" data-cat="Dokumen" onclick="filterCategory('Dokumen', this)">Dokumen</button>
            <button class="nav-link" data-cat="Otomotif" onclick="filterCategory('Otomotif', this)">Otomotif</button>
            <button class="nav-link" data-cat="Lainnya" onclick="filterCategory('Lainnya', this)">Lainnya</button>
        </div>
    </div>

    <div>
    <button class="btn-nav" onclick="toggleDarkMode()" id="darkBtn">🌙 Mode Gelap</button>
    <button class="btn-nav" onclick="showReport()">Saya Menemukan Barang</button>
    
    @if(isset($is_admin) && $is_admin)
        <span style="color: white; font-weight: bold; margin-left: 10px;">👤 Admin FO</span>
        <a href="{{ route('logout') }}" class="btn-nav" style="text-decoration: none; background: #e74c3c;">Logout</a>
    @elseif(isset($mahasiswa_nim) && $mahasiswa_nim)
        <span style="color: white; font-weight: bold; margin-left: 10px;">🎓 NIM: {{ $mahasiswa_nim }}</span>
        <a href="{{ route('logout') }}" class="btn-nav" style="text-decoration: none; background: #e74c3c;">Logout</a>
    @else
        <button class="btn-nav" onclick="showLoginMahasiswa()">Login Mahasiswa</button>
        <button class="btn-nav" id="toggleBtn" onclick="togglePage()">Login Admin</button>
    @endif
</div>
</nav>

<div class="container">
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif
    
    <!-- SECTION KATALOG -->
    <div id="catalog-section">
        <header style="text-align: center; margin-bottom: 50px;">
            <h1>Cari Barang Hilang</h1>
            <p>Daftar barang yang ditemukan di lingkungan kampus.</p>
        </header>

        <div class="catalog-grid">
            @foreach($items as $item)
<div class="card item-card" data-category="{{ $item->category }}">
    @if($item->image)
        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}">
    @else
        <img src="https://via.placeholder.com/300x200?text=Tanpa+Foto" alt="No Image">
    @endif
    
    <span class="tag">{{ $item->category }}</span>
    <h3>{{ $item->title }}</h3>
    <div style="font-size: 0.85rem; color: var(--gray); margin-bottom: 10px;">
        📍 <strong>Lokasi:</strong> {{ $item->location ?? 'Lokasi tidak disebutkan' }}
    </div>
    <p>{{ \Illuminate\Support\Str::limit($item->desc, 100) }}</p>

    @if(isset($is_admin) && $is_admin)
        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah barang ini beneran sudah diambil? Data di DB akan dihapus.')" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit" style="width: 100%; padding: 12px; background: #c0392b; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                🗑️ Barang Sudah Diambil (Hapus)
            </button>
        </form>

    @elseif(isset($mahasiswa_nim) && $mahasiswa_nim)
        <a href="#" onclick="alert('Klaim barang \'{{ $item->title }}\' berhasil diajukan oleh NIM {{ $mahasiswa_nim }}! Silakan ke FO.')" class="btn-claim" style="background: var(--accent); margin-top: 20px; text-decoration: none; display: block;">
            🤝 Klaim Barang Sekarang
        </a>

    @else
        <button onclick="alert('Silakan klik tombol \'Login Mahasiswa\' di pojok kanan atas dan masukkan NIM Anda terlebih dahulu untuk melakukan klaim!')" class="btn-claim" style="background: #7f8c8d; cursor: pointer; width: 100%; border: none; font-weight: bold; margin-top: 20px; padding: 12px; color: white; border-radius: 8px;">
            🔒 Login NIM untuk Klaim
        </button>
    @endif
</div>
@endforeach
        </div>
    </div>

    <!-- SECTION REPORT -->
    <div id="report-section" style="display: none;">
        <div class="login-card">
            <h2>Laporkan Barang Ditemukan</h2>
            <form action="{{ route('report.found') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="title" placeholder="Contoh: Kunci Motor" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category">
                        <option value="Elektronik">Elektronik</option>
                        <option value="Dokumen">Dokumen</option>
                        <option value="Otomotif">Otomotif</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ambil Gambar</label>
                    <input type="file" name="image" accept="image/*" capture="camera" required>
                </div>
                <div class="form-group">
                    <label>Lokasi Penemuan</label>
                    <input type="text" name="location" placeholder="Contoh: Kantin Teknik" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="desc" rows="4" placeholder="Ceritakan detail barang..."></textarea>
                </div>
                <button type="submit" class="btn-submit btn-report">Kirim Laporan</button>
            </form>
        </div>
    </div>

    <!-- SECTION LOGIN  ADMIN -->
    <div id="login-section" style="display: none;">
        <div class="login-card">
            <h2>Login Admin</h2>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
            </form>
        </div>
    </div>
</div>
<!-- SECTION LOGIN  MAHASISWA -->
<div id="login-mahasiswa-section" style="display: none; min-height: 70vh; justify-content: center; align-items: center;">
    <div class="login-card">
        <h2>Login Mahasiswa</h2>
        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 20px;">Masukkan NIM Anda untuk dapat melakukan klaim barang.</p>
        <form action="{{ route('login.mahasiswa') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nomor Induk Mahasiswa (NIM)</label>
                <input type="number" name="nim" placeholder="Contoh: 2201010001" required>
            </div>
            <button type="submit" class="btn-submit" style="background: var(--accent);">Masuk</button>
        </form>
    </div>
</div>

<script>
    // 1. FITUR DARK MODE
    function toggleDarkMode() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', targetTheme);
        localStorage.setItem('theme', targetTheme);
        
        const btn = document.getElementById('darkBtn');
        if(btn) {
            btn.innerText = targetTheme === 'dark' ? '☀️ Mode Terang' : '🌙 Mode Gelap';
        }
    }

    // Cek tema saat halaman pertama kali dimuat
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        window.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('darkBtn');
            if (btn && savedTheme === 'dark') {
                btn.innerText = '☀️ Mode Terang';
            }
        });
    }

    // 2. DEFINISI ELEMEN HALAMAN
    const catalog = document.getElementById('catalog-section');
    const login = document.getElementById('login-section');
    const report = document.getElementById('report-section');
    const loginMahasiswaSec = document.getElementById('login-mahasiswa-section');
    const btnToggle = document.getElementById('toggleBtn');

    // Autoload filter jika ada parameter di URL (?filter=...)
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filter = urlParams.get('filter'); 
        if (filter) {
            const targetBtn = document.querySelector(`.nav-link[data-cat="${filter}"]`);
            if (targetBtn) filterCategory(filter, targetBtn);
        }
    };

    // 3. NAVIGASI BUKA-TUTUP HALAMAN/SECTION
    function showReport() {
        catalog.style.display = "none";
        login.style.display = "none";
        report.style.display = "flex";
        if(loginMahasiswaSec) loginMahasiswaSec.style.display = "none";
        if(btnToggle) btnToggle.innerText = "Kembali ke Katalog";
    }

    function showLoginMahasiswa() {
        catalog.style.display = "none";
        login.style.display = "none";
        report.style.display = "none";
        if(loginMahasiswaSec) loginMahasiswaSec.style.display = "flex";
    }

    function togglePage() {
        if (catalog.style.display === "none") {
            catalog.style.display = "block";
            login.style.display = "none";
            report.style.display = "none";
            if(loginMahasiswaSec) loginMahasiswaSec.style.display = "none";
            if(btnToggle) btnToggle.innerText = "Login Admin";
        } else {
            catalog.style.display = "none";
            login.style.display = "flex";
            report.style.display = "none";
            if(loginMahasiswaSec) loginMahasiswaSec.style.display = "none";
            if(btnToggle) btnToggle.innerText = "Kembali ke Katalog";
        }
    }

    // 4. FILTER KATEGORI BARANG
    function filterCategory(category, element) {
        catalog.style.display = "block";
        login.style.display = "none";
        report.style.display = "none";
        if(loginMahasiswaSec) loginMahasiswaSec.style.display = "none";
        if(btnToggle) btnToggle.innerText = "Login Admin";

        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        element.classList.add('active');

        const cards = document.querySelectorAll('.item-card');
        cards.forEach(card => {
            if (category === 'all') {
                card.style.display = "block";
            } else {
                card.style.display = (card.getAttribute('data-category') === category) ? "block" : "none";
            }
        });
    }
</script>

</html>