# Setup Project Autopahala

Panduan untuk menjalankan project setelah clone dari GitHub.

## Langkah-langkah

1. Install dependency Laravel:

```bash
composer install
```

2. Copy file environment:

```bash
copy .env.example .env
```

3. Buat app key:

```bash
php artisan key:generate
```

4. Jalankan database migration:

```bash
php artisan migrate
```

5. Install dependency frontend:

```bash
npm install
```

6. Jalankan Vite:

```bash
npm run dev
```

7. Jalankan Laravel:

```bash
php artisan serve
```

## Catatan Kerja Tim

- Jangan upload folder `vendor`.
- Jangan upload folder `node_modules`.
- Jangan upload file `.env`.
- Sebelum mulai coding, lakukan `git pull`.
- Kerjakan fitur di branch masing-masing.
- Jangan edit file yang sama bersamaan kalau belum koordinasi.