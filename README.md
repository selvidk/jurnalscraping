## Jurnal Scraping

Sebuah aplikasi berbasis web yang berisi informasi jadwal publikasi berbagai jurnal. Memudahkan penulis artikel ilmiah mencari informasi jadwal publikasi untuk mencapai target waktu yang tepat.

Bekerja dengan cara melakukan scraping pada website [Sinta Jurnal](http://sinta.kemdikbud.go.id/journals) dan official website dari masing-masing jurnal yang terindeks SINTA.

Fitur aplikasi ini antara lain adalah:
- Dapat menampilkan keseluruhan data maupun hasil pencarian atau hasil filter 
- Dapat melakukan scraping (admin)
- Dapat mengelola data hasil scraping (admin)
- Dapat menampilkan tren pencarian dan riwayat pencarian (admin)

## Dikembangkan menggunakan
- Framework Laravel versi 8
- HTML5
- Template Bootstrap 5, [Startbootsrap](https://startbootstrap.com/) dan [Adminkit](https://demo.adminkit.io/?theme=default)
- JavaScript
- MySQL (dengan XAMPP)
- [Library Goutte](https://github.com/FriendsOfPHP/Goutte). Diperlukan untuk proses scraping 

## Pemasangan
1. Unduh projek dan tambahkan file .env
2. Import database
3. jalankan composer update dan jalankan program
4. Akses ke halaman admin melalui "url_utama/login" dengan email admin@gmail.com dan password admin
