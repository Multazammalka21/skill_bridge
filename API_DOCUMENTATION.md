# Pinteria API — Dokumentasi Teknis

> **Platform Edukasi Inklusi Anak Difabel** | SDG #4 Quality Education
> Framework: Laravel 11+ | Auth: JWT Bearer · Basic Auth · API Key
> Versi Dokumentasi: 2.0 | Diperbarui: 2026-06-03

---

## 🚀 Base URL

```
http://skill_bridge.test/api
```

> Semua request harus menyertakan header `Accept: application/json`

---

## 🔐 Metode Autentikasi

Pinteria API mendukung **3 metode autentikasi**:

| Metode | Header | Digunakan di |
|--------|--------|--------------|
| **JWT Bearer** | `Authorization: Bearer <token>` | Semua endpoint protected |
| **Basic Auth** | `Authorization: Basic base64(email:password)` | `POST /login/basic` |
| **API Key** | `X-API-Key: <key>` | Endpoint publik (`/public/*`) |

**API Key default:** `skillbridge-api-key-2024-secret`  
(dapat diubah via `.env` → `APP_API_KEY`)

---

## 📋 Referensi Endpoint

### 1. 🔐 Autentikasi

---

#### `POST /api/register`
Mendaftarkan akun orang tua baru. Mengembalikan JWT token secara otomatis.

**Header:**
```
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Nama Orang Tua",
  "email": "email@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Validasi:**
| Field | Aturan |
|-------|--------|
| `name` | required, string, maks 255 karakter |
| `email` | required, email valid, unik di tabel users |
| `password` | required, min 8 karakter, harus cocok dengan `password_confirmation` |

**Response `201 Created`:**
```json
{
  "message": "Registrasi berhasil.",
  "user": {
    "id": 1,
    "name": "Nama Orang Tua",
    "email": "email@example.com",
    "role": "parent",
    "created_at": "2026-06-03T04:00:00.000000Z",
    "updated_at": "2026-06-03T04:00:00.000000Z"
  },
  "token": "eyJ0eXAiOiJKV1Qi...",
  "type": "bearer",
  "expires_in": 3600
}
```

> ⚠️ Gunakan nilai `token` (bukan `access_token`) dari register, lalu gunakan sebagai `Authorization: Bearer <token>`

---

#### `POST /api/login`
Login dengan email dan password menggunakan JWT Bearer.

**Header:**
```
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "email@example.com",
  "password": "password123"
}
```

**Response `200 OK`:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1Qi...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "Nama Orang Tua",
    "email": "email@example.com",
    "role": "parent"
  }
}
```

> Gunakan `access_token` di header semua request protected:
> `Authorization: Bearer eyJ0eXAiOiJKV1Qi...`

**Response `422` (kredensial salah):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Email atau kata sandi salah."]
  }
}
```

---

#### `POST /api/login/basic`
Login menggunakan HTTP Basic Authentication.

**Header:**
```
Authorization: Basic base64(email:password)
Accept: application/json
```

**Contoh encoding:**
- Email: `user@test.com`, Password: `pass123`
- Base64: `dXNlckB0ZXN0LmNvbTpwYXNzMTIz`
- Header: `Authorization: Basic dXNlckB0ZXN0LmNvbTpwYXNzMTIz`

**Response `200 OK`:** (format sama dengan JWT Login)

**Response `401` (credentials kosong atau salah):**
```json
{
  "message": "Basic Auth credentials diperlukan."
}
```
> Header respons akan menyertakan: `WWW-Authenticate: Basic realm="Pinteria API"`

---

#### `POST /api/refresh`
Memperbarui JWT token sebelum kedaluwarsa. Token lama akan di-invalidate.

**Header:** `Authorization: Bearer <token>`

**Response `200 OK`:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1Qi... (baru)",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { ... }
}
```

---

#### `GET /api/me`
Mendapatkan data pengguna yang sedang login beserta daftar anak.

**Header:** `Authorization: Bearer <token>`

**Response `200 OK`:**
```json
{
  "user": {
    "id": 1,
    "name": "Orang Tua",
    "email": "email@example.com",
    "role": "parent",
    "children": [
      {
        "id": 1,
        "user_id": 1,
        "nama_panggilan": "Budi",
        "tanggal_lahir": "2019-05-10",
        "jenis_disabilitas": "tunanetra",
        "created_at": "...",
        "updated_at": "..."
      }
    ]
  }
}
```

---

#### `POST /api/logout`
Membatalkan (invalidate) JWT token aktif.

**Header:** `Authorization: Bearer <token>`

**Response `200 OK`:**
```json
{
  "message": "Logout berhasil. Token telah dibatalkan."
}
```

---

### 2. 🔑 API Key — Akses Publik

> Endpoint `/public/*` **tidak memerlukan JWT login**, cukup sertakan header `X-API-Key`.

**API Key:** `skillbridge-api-key-2024-secret`

---

#### `GET /api/public/lessons`
Mendapatkan daftar lesson tanpa autentikasi JWT.

**Header:**
```
X-API-Key: skillbridge-api-key-2024-secret
Accept: application/json
```

**Query Parameters (opsional):**
| Param | Tipe | Nilai | Keterangan |
|-------|------|-------|------------|
| `tipe_dunia` | string | `audio` atau `visual` | Filter berdasarkan tipe dunia |

**Response `200 OK`:**
```json
{
  "lessons": [
    {
      "id": 1,
      "judul": "Mengenal Huruf A",
      "deskripsi": "Belajar mengenal huruf A melalui cerita audio",
      "tipe_dunia": "audio",
      "kategori_usia": "4-6",
      "urutan": 1,
      "durasi_menit": 5,
      "aktif": true,
      "created_at": "...",
      "updated_at": "..."
    }
  ]
}
```

> **Catatan:** Endpoint ini hanya mengembalikan metadata lesson (tanpa field media seperti `gambar`, `audio_story_url`, dll) untuk efisiensi *lazy loading*.

**Response `403` (API Key salah):**
```json
{
  "message": "API Key tidak valid atau tidak diizinkan."
}
```

---

#### `GET /api/public/modules`
Mendapatkan daftar modul berdasarkan profil anak.

**Header:**
```
X-API-Key: skillbridge-api-key-2024-secret
Accept: application/json
```

**Query Parameters (wajib):**
| Param | Tipe | Keterangan |
|-------|------|------------|
| `child_id` | integer | ID anak yang profilnya ingin dimuat |

**Response `200 OK`:**
```json
{
  "usia": 7,
  "jenis_disabilitas": "tunanetra",
  "modules": [ ... ]
}
```

> ⚠️ Meskipun endpoint ini menggunakan API Key, sistem tetap memverifikasi bahwa anak tersebut milik pengguna yang login. Jika tidak cocok, akan mengembalikan `403 Forbidden`.

---

### 3. 👶 Children — Manajemen Data Anak

> Semua endpoint ini memerlukan `Authorization: Bearer <token>`

---

#### `GET /api/children`
Mendapatkan semua data anak milik orang tua yang sedang login.

**Response `200 OK`:**
```json
{
  "children": [
    {
      "id": 1,
      "user_id": 1,
      "nama_panggilan": "Budi",
      "tanggal_lahir": "2019-05-10",
      "jenis_disabilitas": "tunanetra",
      "created_at": "...",
      "updated_at": "..."
    }
  ]
}
```

---

#### `POST /api/children`
Menambahkan data anak baru untuk orang tua yang login.

**Request Body:**
```json
{
  "nama_panggilan": "Budi",
  "tanggal_lahir": "2019-05-10",
  "jenis_disabilitas": "tunanetra"
}
```

**Validasi:**
| Field | Aturan |
|-------|--------|
| `nama_panggilan` | required, string, maks 100 karakter |
| `tanggal_lahir` | required, format tanggal valid |
| `jenis_disabilitas` | required, salah satu: `tunanetra` atau `tunarungu` |

> `tunanetra` → **Audio World** (belajar via suara)
> `tunarungu` → **Visual World** (belajar via gambar/visual)

**Response `201 Created`:**
```json
{
  "message": "Anak berhasil ditambahkan.",
  "child": {
    "id": 1,
    "user_id": 1,
    "nama_panggilan": "Budi",
    "tanggal_lahir": "2019-05-10",
    "jenis_disabilitas": "tunanetra",
    "created_at": "...",
    "updated_at": "..."
  }
}
```

---

#### `GET /api/children/{id}`
Mendapatkan detail satu anak beserta riwayat lesson dan hasil quiz.

**Response `200 OK`:**
```json
{
  "child": {
    "id": 1,
    "nama_panggilan": "Budi",
    "jenis_disabilitas": "tunanetra",
    "lesson_completions": [ ... ],
    "quiz_results": [ ... ]
  }
}
```

**Response `403` (bukan milik orang tua ini):**
```json
{
  "message": "Akses ditolak."
}
```

---

#### `PUT /api/children/{id}`
Memperbarui data anak. Semua field bersifat opsional (partial update).

**Request Body:**
```json
{
  "nama_panggilan": "Budi Santoso",
  "tanggal_lahir": "2019-05-10",
  "jenis_disabilitas": "tunarungu"
}
```

**Validasi:**
| Field | Aturan |
|-------|--------|
| `nama_panggilan` | opsional, string, maks 100 karakter |
| `tanggal_lahir` | opsional, format tanggal valid |
| `jenis_disabilitas` | opsional, salah satu: `tunanetra` atau `tunarungu` |

**Response `200 OK`:**
```json
{
  "message": "Data anak berhasil diperbarui.",
  "child": { ... }
}
```

---

#### `DELETE /api/children/{id}`
Menghapus data anak (beserta semua data relasinya).

**Response `200 OK`:**
```json
{
  "message": "Data anak berhasil dihapus."
}
```

---

### 4. 📊 Dashboard Orang Tua

---

#### `GET /api/dashboard`
Mendapatkan ringkasan aktivitas belajar semua anak milik orang tua yang login.

**Response `200 OK`:**
```json
{
  "children": [
    {
      "id": 1,
      "nama_panggilan": "Budi",
      "jenis_disabilitas": "tunanetra",
      "total_lesson_selesai": 5,
      "rata_rata_skor": 87.5,
      "total_waktu_belajar_menit": 45.2
    }
  ]
}
```

---

#### `GET /api/dashboard/child/{child}`
Mendapatkan detail progress satu anak, lengkap dengan data Chart.js-ready untuk grafik.

**Query Parameters (opsional):**
| Param | Default | Keterangan |
|-------|---------|------------|
| `days` | `30` | Jumlah hari ke belakang untuk rentang data grafik |

**Response `200 OK`:**
```json
{
  "child": {
    "id": 1,
    "nama_panggilan": "Budi",
    "jenis_disabilitas": "tunanetra"
  },
  "summary": {
    "total_lesson_selesai": 12,
    "rata_rata_skor": 85.3,
    "total_waktu_belajar_menit": 120.5
  },
  "charts": {
    "labels": ["04 Mei", "05 Mei", "...", "03 Jun"],
    "lessons_completed": [0, 1, 0, 2, ...],
    "quiz_scores": [0, 75.0, 0, 90.0, ...],
    "study_time_minutes": [0, 12.5, 0, 25.0, ...]
  }
}
```

> Data `charts` cocok langsung digunakan dengan **Chart.js** untuk visualisasi grafik perkembangan belajar anak.

**Response `403`:**
```json
{
  "message": "Akses ditolak."
}
```

---

### 5. 📚 Lessons

---

#### `GET /api/lessons`
Mendapatkan daftar lesson (hanya metadata, tanpa field media — lazy load).

**Query Parameters (opsional):**
| Param | Nilai | Keterangan |
|-------|-------|------------|
| `tipe_dunia` | `audio` atau `visual` | Filter berdasarkan tipe dunia |

**Response `200 OK`:**
```json
{
  "lessons": [
    {
      "id": 1,
      "judul": "Mengenal Huruf A",
      "deskripsi": "Belajar mengenal huruf A",
      "tipe_dunia": "audio",
      "kategori_usia": "4-6",
      "urutan": 1,
      "durasi_menit": 5,
      "aktif": true
    }
  ]
}
```

> Field media (`gambar`, `animasi_lottie`, `efek_suara`, `audio_story_url`, dll) **tidak disertakan** di endpoint ini untuk efisiensi bandwidth.

---

#### `GET /api/lessons/{lesson}`
Mendapatkan detail lengkap satu lesson termasuk semua media dan soal quiz.

**Response `200 OK`:**
```json
{
  "lesson": {
    "id": 1,
    "judul": "Mengenal Huruf A",
    "deskripsi": "...",
    "tipe_dunia": "audio",
    "kategori_usia": "4-6",
    "urutan": 1,
    "prerequisite_lesson_id": null,
    "gambar": "lessons/gambar-a.jpg",
    "animasi_lottie": "lessons/animasi-a.json",
    "efek_suara": "lessons/suara-a.mp3",
    "teks_narasi": "Ini adalah huruf A...",
    "teks_keterangan": "A dibaca 'ah'",
    "konten_tipe": "audio_story",
    "audio_story_url": "storage/audio/cerita-a.mp3",
    "durasi_menit": 5,
    "aktif": true,
    "quiz_questions": [
      {
        "id": 1,
        "lesson_id": 1,
        "pertanyaan": "Huruf apakah ini?",
        "jawaban_benar": "A",
        "pilihan": ["A", "B", "C", "D"],
        "tipe": "pilihan_ganda",
        "gambar": null,
        "audio_url": "storage/audio/pertanyaan-1.mp3",
        "animasi_url": null,
        "efek_suara_url": null,
        "poin": 10
      }
    ]
  }
}
```

**Field `quiz_questions[].tipe`:**
| Nilai | Keterangan |
|-------|------------|
| `pilihan_ganda` | Soal pilihan berganda |
| `audio` | Soal berbasis audio (khusus Dunia Audio/tunanetra) |
| `drag_drop` | Soal drag & drop (khusus Dunia Visual/tunarungu) |

---

#### `POST /api/lessons/{lesson}/complete`
Menandai lesson sebagai selesai untuk anak tertentu. Menggunakan `firstOrCreate` sehingga aman dipanggil berulang kali.

**Request Body:**
```json
{
  "child_id": 1
}
```

**Validasi:**
| Field | Aturan |
|-------|--------|
| `child_id` | required, harus ada di tabel `children` |

**Response `200 OK`:**
```json
{
  "message": "Lesson berhasil diselesaikan."
}
```

---

#### `POST /api/lessons/{lesson}/start-session`
Memulai sesi belajar. Dipanggil saat anak memasuki halaman lesson.

**Request Body:**
```json
{
  "child_id": 1
}
```

**Response `200 OK`:**
```json
{
  "message": "Sesi belajar dimulai.",
  "session_id": 5
}
```

> Simpan `session_id` untuk digunakan saat memanggil endpoint **End Session**.

---

#### `POST /api/sessions/{session}/end`
Mengakhiri sesi belajar. Menghitung durasi otomatis dari `started_at` hingga sekarang.

**Header:** `Authorization: Bearer <token>` (tanpa body)

**Response `200 OK`:**
```json
{
  "message": "Sesi belajar selesai.",
  "durasi_detik": 342
}
```

---

### 6. 🧩 Quiz

---

#### `POST /api/quiz/answer`
Mengirimkan jawaban quiz anak. Setiap jawaban disimpan sebagai record terpisah.

**Request Body:**
```json
{
  "child_id": 1,
  "lesson_id": 1,
  "quiz_question_id": 1,
  "jawaban_anak": "A",
  "benar": true,
  "skor": 100,
  "percobaan": 1
}
```

**Validasi:**
| Field | Tipe | Aturan |
|-------|------|--------|
| `child_id` | integer | required, harus ada di tabel `children` |
| `lesson_id` | integer | required, harus ada di tabel `lessons` |
| `quiz_question_id` | integer | required, harus ada di tabel `quiz_questions` |
| `jawaban_anak` | string | required |
| `benar` | boolean | required (`true` / `false`) |
| `skor` | integer | required, 0–100 |
| `percobaan` | integer | required, minimum 1 |

**Response `201 Created`:**
```json
{
  "message": "Jawaban berhasil disimpan.",
  "result": {
    "id": 10,
    "child_id": 1,
    "lesson_id": 1,
    "quiz_question_id": 1,
    "jawaban_anak": "A",
    "benar": true,
    "skor": 100,
    "percobaan": 1,
    "created_at": "...",
    "updated_at": "..."
  }
}
```

---

#### `GET /api/quiz/results`
Mendapatkan semua hasil quiz anak untuk lesson tertentu, beserta detail soalnya.

**Query Parameters (wajib):**
| Param | Tipe | Keterangan |
|-------|------|------------|
| `child_id` | integer | ID anak |
| `lesson_id` | integer | ID lesson |

**Contoh:** `GET /api/quiz/results?child_id=1&lesson_id=1`

**Response `200 OK`:**
```json
{
  "results": [
    {
      "id": 10,
      "child_id": 1,
      "lesson_id": 1,
      "quiz_question_id": 1,
      "jawaban_anak": "A",
      "benar": true,
      "skor": 100,
      "percobaan": 1,
      "quiz_question": {
        "id": 1,
        "pertanyaan": "Huruf apakah ini?",
        "jawaban_benar": "A",
        "pilihan": ["A", "B", "C", "D"],
        "tipe": "pilihan_ganda",
        "poin": 10
      }
    }
  ]
}
```

---

## ❌ Error Responses

| HTTP Code | Keterangan |
|-----------|------------|
| `400` | Bad Request — format request tidak valid |
| `401` | Unauthenticated — token tidak ada, tidak valid, atau sudah kedaluwarsa |
| `403` | Forbidden — API Key tidak valid, atau akses ke resource milik user lain |
| `404` | Not Found — resource tidak ditemukan |
| `422` | Unprocessable Entity — validasi Laravel gagal |
| `500` | Internal Server Error |

**Contoh 401 (tidak ada token):**
```json
{
  "message": "Tidak terautentikasi. Sertakan Bearer token yang valid."
}
```

**Contoh 403 (API Key salah):**
```json
{
  "message": "API Key tidak valid atau tidak diizinkan."
}
```

**Contoh 422 (validasi gagal):**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

## 🗄️ Struktur Database & Relasi Model

```
users (orang tua)                          [role: 'parent']
  └── children (anak)                      [user_id FK]
        ├── quiz_results                   [child_id, lesson_id, quiz_question_id FK]
        ├── lesson_completions             [child_id, lesson_id FK]
        ├── study_sessions                 [child_id, lesson_id FK]
        └── child_badges (pivot)           [child_id, badge_id FK]

lessons
  ├── quiz_questions                       [lesson_id FK]
  │     └── quiz_results                  [quiz_question_id FK]
  ├── lesson_completions                   [lesson_id FK]
  ├── study_sessions                       [lesson_id FK]
  └── category                            [category_id FK]

badges
  └── child_badges (pivot)                 [badge_id, child_id FK]
```

### Field Penting Model `Lesson`

| Field | Tipe | Keterangan |
|-------|------|------------|
| `tipe_dunia` | string | `audio` (tunanetra) atau `visual` (tunarungu) |
| `kategori_usia` | string | Kategori usia target (e.g., `4-6`, `7-9`) |
| `konten_tipe` | string | Tipe konten: `audio_story`, `visual`, dll |
| `audio_story_url` | string | Path file audio cerita (untuk Dunia Audio) |
| `prerequisite_lesson_id` | integer/null | Lesson yang harus diselesaikan dulu (learning path) |
| `aktif` | boolean | Hanya lesson aktif yang dikembalikan API |

### Field Penting Model `QuizQuestion`

| Field | Tipe | Keterangan |
|-------|------|------------|
| `tipe` | string | `pilihan_ganda`, `audio`, `drag_drop` |
| `pilihan` | array (JSON) | Array opsi jawaban |
| `jawaban_benar` | string | Jawaban yang benar |
| `audio_url` | string/null | URL audio untuk soal tipe audio |
| `poin` | integer | Poin untuk soal ini |

---

## ⚙️ Konfigurasi JWT

Token JWT dikonfigurasi di `config/jwt.php`:

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| **TTL** | 60 menit | Durasi token aktif |
| **Refresh TTL** | 20.160 menit (2 minggu) | Batas waktu refresh token |
| **Algoritma** | HS256 | Algoritma signing |

Untuk mengubah TTL, edit `JWT_TTL` di `.env`.

---

## 🧪 Testing dengan Postman

1. Import file `SkillBridge_API.postman_collection.json` ke Postman
2. Set variable `base_url` = `http://skill_bridge.test/api`
3. Jalankan **Register** atau **Login (JWT Bearer)**
   - Token otomatis tersimpan ke variable `{{jwt_token}}` via test script
4. Semua endpoint protected langsung bisa diuji menggunakan token tersebut
5. Untuk endpoint API Key, gunakan variable `{{api_key}}` yang sudah terisi

---

## 📁 Struktur File Relevan

```
app/
  Http/
    Controllers/Api/
      AuthController.php          ← JWT + Basic Auth login/register
      ChildController.php         ← CRUD data anak (via Repository Pattern)
      LessonController.php        ← Lessons, sesi belajar
      QuizController.php          ← Submit & retrieve hasil quiz
      ParentDashboardController.php ← Statistik & grafik progress anak
      ModuleController.php        ← Modul berdasarkan profil anak
    Middleware/
      ApiKeyMiddleware.php        ← Validasi header X-API-Key
  Models/
    User.php                      ← implements JWTSubject, role: 'parent'
    Child.php                     ← BelongsTo User, jenis_disabilitas
    Lesson.php                    ← HasMany QuizQuestions, tipe_dunia, scope: active/listing
    QuizQuestion.php              ← BelongsTo Lesson, pilihan (JSON cast)
    QuizResult.php                ← BelongsTo Child, Lesson, QuizQuestion
    LessonCompletion.php          ← BelongsTo Child, Lesson
    StudySession.php              ← BelongsTo Child, Lesson, durasi_detik
    Badge.php                     ← BelongsToMany Child (via child_badges)
  Repositories/
    Contracts/ChildRepositoryInterface.php
    ChildContentRepository.php    ← Modul filtered by age & disability type
routes/
  api.php                         ← Semua route API (3 grup: public, api.key, auth:api)
config/
  auth.php                        ← Guard 'api' menggunakan driver JWT
  jwt.php                         ← Konfigurasi JWT (TTL, algoritma)
.env                              ← JWT_SECRET, APP_API_KEY
SkillBridge_API.postman_collection.json ← Import ke Postman untuk testing
```

---

## 🔄 Alur Penggunaan Tipikal

```
1. POST /register  atau  POST /login
        ↓
   Dapat access_token (JWT)
        ↓
2. POST /children           → Tambah profil anak (tunanetra/tunarungu)
        ↓
3. GET  /modules?child_id=X → Dapatkan modul sesuai usia & disabilitas
        ↓
4. GET  /lessons?tipe_dunia=audio → Daftar lesson (metadata saja)
        ↓
5. GET  /lessons/{id}       → Detail lesson + soal quiz (saat anak buka lesson)
        ↓
6. POST /lessons/{id}/start-session → Catat mulai belajar → dapat session_id
        ↓
7. POST /quiz/answer        → Kirim setiap jawaban quiz
        ↓
8. POST /lessons/{id}/complete   → Tandai lesson selesai
        ↓
9. POST /sessions/{id}/end       → Catat akhir sesi, hitung durasi
        ↓
10. GET /dashboard/child/{id}    → Lihat grafik progress anak
```
