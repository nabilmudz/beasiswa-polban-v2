# API Documentation - Beasiswa

## Base URL
```
http://your-domain.com/api
```

## Endpoints

### 1. Get All Beasiswa (List dengan Pagination)

**Endpoint:** `GET /api/beasiswa`

**Query Parameters (Opsional):**
- `search` - Cari berdasarkan nama beasiswa
- `jenis_beasiswa[]` - Filter berdasarkan jenis beasiswa (bisa multiple)
- `tipe_beasiswa` - Filter berdasarkan tipe beasiswa
- `jurusan` - Filter berdasarkan jurusan
- `per_page` - Jumlah data per halaman (default: 15)
- `page` - Nomor halaman

**Contoh Request:**
```bash
# Get all beasiswa
curl -X GET "http://your-domain.com/api/beasiswa"

# Get dengan filter
curl -X GET "http://your-domain.com/api/beasiswa?search=prestasi&per_page=10"

# Get dengan multiple filters
curl -X GET "http://your-domain.com/api/beasiswa?jenis_beasiswa[]=akademik&jenis_beasiswa[]=non-akademik&tipe_beasiswa=internal"
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Data beasiswa berhasil diambil",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": "uuid-string",
                "nama_beasiswa": "Beasiswa Prestasi Akademik",
                "deskripsi": "Deskripsi beasiswa...",
                "youtube_url": "https://youtube.com/...",
                "jenis_beasiswa": "akademik",
                "tipe_beasiswa": "internal",
                "kuota": 50,
                "sumber": "Kampus",
                "tanggal_mulai": "2026-01-01",
                "tanggal_berakhir": "2026-12-31",
                "publish": true,
                "allow_multiple": false,
                "created_at": "2026-01-01T00:00:00.000000Z",
                "updated_at": "2026-01-01T00:00:00.000000Z",
                "syarat_beasiswa": [...],
                "benefit_beasiswa": [...],
                "poster_beasiswa": [...],
                "link_beasiswa": {...},
                "syarat_dokumen": [...]
            }
        ],
        "first_page_url": "http://your-domain.com/api/beasiswa?page=1",
        "from": 1,
        "last_page": 3,
        "last_page_url": "http://your-domain.com/api/beasiswa?page=3",
        "links": [...],
        "next_page_url": "http://your-domain.com/api/beasiswa?page=2",
        "path": "http://your-domain.com/api/beasiswa",
        "per_page": 15,
        "prev_page_url": null,
        "to": 15,
        "total": 45
    }
}
```

**Response Error (500):**
```json
{
    "success": false,
    "message": "Gagal mengambil data beasiswa",
    "error": "Error message detail"
}
```

---

### 2. Get Single Beasiswa (Detail)

**Endpoint:** `GET /api/beasiswa/{id}`

**Path Parameters:**
- `id` - UUID beasiswa

**Contoh Request:**
```bash
curl -X GET "http://your-domain.com/api/beasiswa/550e8400-e29b-41d4-a716-446655440000"
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Detail beasiswa berhasil diambil",
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "nama_beasiswa": "Beasiswa Prestasi Akademik",
        "deskripsi": "Deskripsi lengkap beasiswa...",
        "youtube_url": "https://youtube.com/...",
        "jenis_beasiswa": "akademik",
        "tipe_beasiswa": "internal",
        "kuota": 50,
        "sumber": "Kampus",
        "tanggal_mulai": "2026-01-01",
        "tanggal_berakhir": "2026-12-31",
        "publish": true,
        "allow_multiple": false,
        "created_at": "2026-01-01T00:00:00.000000Z",
        "updated_at": "2026-01-01T00:00:00.000000Z",
        "syarat_beasiswa": [
            {
                "id": 1,
                "syarat": "IPK minimal 3.5",
                "created_at": "2026-01-01T00:00:00.000000Z",
                "updated_at": "2026-01-01T00:00:00.000000Z"
            }
        ],
        "benefit_beasiswa": [
            {
                "id": 1,
                "benefit": "Bantuan dana pendidikan",
                "created_at": "2026-01-01T00:00:00.000000Z",
                "updated_at": "2026-01-01T00:00:00.000000Z"
            }
        ],
        "poster_beasiswa": [...],
        "link_beasiswa": {
            "id": 1,
            "beasiswa_id": "550e8400-e29b-41d4-a716-446655440000",
            "url": "https://...",
            "created_at": "2026-01-01T00:00:00.000000Z",
            "updated_at": "2026-01-01T00:00:00.000000Z"
        },
        "syarat_dokumen": [...]
    }
}
```

**Response Error - Not Found (404):**
```json
{
    "success": false,
    "message": "Beasiswa tidak ditemukan",
    "error": "Beasiswa dengan ID tersebut tidak ada"
}
```

**Response Error (500):**
```json
{
    "success": false,
    "message": "Gagal mengambil detail beasiswa",
    "error": "Error message detail"
}
```

---

## Testing API

### Menggunakan cURL:
```bash
# Test get all
curl -X GET "http://localhost:8000/api/beasiswa"

# Test get by ID
curl -X GET "http://localhost:8000/api/beasiswa/{beasiswa-id}"
```

### Menggunakan Postman:
1. Buat request baru
2. Pilih method `GET`
3. Masukkan URL: `http://localhost:8000/api/beasiswa`
4. Klik Send

### Menggunakan JavaScript (Fetch):
```javascript
// Get all beasiswa
fetch('http://your-domain.com/api/beasiswa')
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));

// Get single beasiswa
fetch('http://your-domain.com/api/beasiswa/550e8400-e29b-41d4-a716-446655440000')
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
```

---

## Notes

- Semua endpoint mengembalikan response dalam format JSON
- API ini tidak memerlukan authentication (public access)
- Jika Anda ingin menambahkan authentication, uncomment middleware `auth:sanctum` di routes/api.php
- Pagination default adalah 15 items per halaman
- Filter dapat dikombinasikan sesuai kebutuhan
