import random
from locust import HttpUser, task, between

# Panduan Menjalankan Load Test dengan 500 User:
# 1. Pastikan Locust sudah terinstal: pip install locust
# 2. Jalankan perintah berikut di terminal:
#    locust -f locustfile.py
# 3. Buka browser dan akses http://localhost:8089
# 4. Masukkan parameter:
#    - Number of users: 500
#    - Spawn rate: 10 atau 20 (jumlah user baru per detik)
#    - Host: https://kembaranngadu.my.id (atau http://localhost:8000 untuk lokal)
# 5. Klik "Start swarming"

class KembaranNgaduUser(HttpUser):
    # Simulasi waktu tunggu antar request (antara 1 sampai 4 detik)
    wait_time = between(1, 4)

    # List dummy tracking code untuk simulasi melihat aduan acak
    tracking_codes = [
        "PKM-KBR/001/V/2026",
        "PKM-KBR/002/V/2026",
        "PKM-KBR/003/V/2026",
    ]

    @task(50)  # Prioritas tinggi (50% dari aktivitas user)
    def visit_home(self):
        """Simulasi warga membuka Halaman Beranda."""
        self.client.get("/")

    @task(20)  # Prioritas sedang (20% dari aktivitas user)
    def visit_tentang_kami(self):
        """Simulasi warga membuka Halaman Tentang Kami."""
        self.client.get("/tentang-kami")

    @task(15)  # Prioritas sedang (15% dari aktivitas user)
    def visit_create_pengaduan(self):
        """Simulasi warga membuka Form Pembuatan Laporan Baru."""
        self.client.get("/pengaduan/create")

    @task(15)  # Prioritas sedang (15% dari aktivitas user)
    def view_detail_pengaduan(self):
        """Simulasi warga membuka Detail Aduan / Tracking Code secara acak."""
        # Jika ada tracking code, gunakan salah satu secara acak
        code = random.choice(self.tracking_codes)
        self.client.get(f"/pengaduan/{code}")
