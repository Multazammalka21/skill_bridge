# Skill Bridge API — Dokumentasi Penggunaan

> **Platform Edukasi Inklusi Anak Difabel** | SDG #4 Quality Education
> Framework: Laravel 13 | Auth: JWT + Basic Auth + API Key

---

## 🚀 Base URL

```
http://skill_bridge.test/api
```

---

## 🔐 Metode Autentikasi

Skill Bridge API mendukung **3 metode autentikasi**:

| Metode | Header | Endpoint |
|--------|--------|----------|
| **JWT Bearer** | `Authorization: Bearer <token>` | Semua endpoint protected |
| **Basic Auth** | `Authorization: Basic base64(email:password)` | `POST /login/basic` |
| **API Key** | `X-API-Key: <key>` | Endpoint publik (`/public/*`) |

---

## 📋 Daftar Endpoint

### 1. Autentikasi

#### `POST /api/register`
Mendaftarkan akun orang tua baru. Mengembalikan JWT token.

**Request Body:**
```json
{
  "name": "Nama Orang Tua",
  "email": "email@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response `201`:**
```json
{
  "message": "Registrasi berhasil.",
  "user": { "id": 1, "name": "Nama Orang Tua", "email": "email@example.com", "role": "parent" },
  "token": "eyJ0eXAiOiJKV1Qi...",
  "type": "bearer",
  "expires_in": 3600
}
```

---

#### `POST /api/login` *(JWT Bearer)*
Login dengan email dan password. Mengembalikan JWT token.

**Request Body:**
```json
{
  "email": "email@example.com",
  "password": "password123"
}
```

**Response `200`:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1Qi...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { "id": 1, "name": "Nama Orang Tua", "role": "parent" }
}
```

> Gunakan `access_token` di header: `Authorization: Bearer eyJ0eXAiOiJKV1Qi...`

---

#### `POST /api/login/basic` *(Basic Auth)*
Login menggunakan HTTP Basic Authentication.

**Header:**
```
Authorization: Basic base64(email:password)
```
Contoh: email `user@test.com` password `pass123` → `Authorization: Basic dXNlckB0ZXN0LmNvbTpwYXNzMTIz`

**Response `200`:** (sama dengan JWT Login)

---

#### `POST /api/refresh`
Memperbarui JWT token sebelum kedaluwarsa.

**Header:** `Authorization: Bearer <token>`

**Response `200`:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1Qi... (baru)",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

#### `GET /api/me`
Mendapatkan informasi pengguna yang sedang login.

**Header:** `Authorization: Bearer <token>`

**Response `200`:**
```json
{
  "user": {
    "id": 1,
    "name": "Orang Tua",
    "email": "email@example.com",
    "role": "parent",
    "children": [ ... ]
  }
}
```

---

#### `POST /api/logout`
Membatalkan JWT token saat ini (invalidate).

**Header:** `Authorization: Bearer <token>`

**Response `200`:**
```json
{ "message": "Logout berhasil. Token telah dibatalkan." }
```

---

### 2. API Key — Akses Publik

> Semua endpoint `/public/*` **tidak memerlukan login**, cukup sertakan header `X-API-Key`.

**API Key:** `skillbridge-api-key-2024-secret` (lihat `.env` → `APP_API_KEY`)

---

#### `GET /api/public/lessons`
Mendapatkan daftar lesson tanpa autentikasi.

**Header:** `X-API-Key: skillbridge-api-key-2024-secret`

**Query Params (opsional):**
| Param | Nilai | Keterangan |
|-------|-------|------------|
| `tipe_dunia` | `audio` atau `visual` | Filter berdasarkan tipe dunia |

**Response `200`:**
```json
{
  "lessons": [
    {
      "id": 1,
      "judul": "Mengenal Huruf A",
      "deskripsi": "...",
      "tipe_dunia": "audio",
      "urutan": 1,
      "durasi_menit": 5,
      "aktif": true
    }
  ]
}
```

---

#### `GET /api/public/modules`
Mendapatkan daftar modul tanpa autentikasi.

**Header:** `X-API-Key: skillbridge-api-key-2024-secret`

---

### 3. Children (Data Anak)

> Semua endpoint ini memerlukan `Authorization: Bearer <token>`

#### `GET /api/children`
Mendapatkan semua data anak milik orang tua yang login.

#### `POST /api/children`
Menambahkan data anak baru.

**Request Body:**
```json
{
  "nama_panggilan": "Budi",
  "tanggal_lahir": "2019-05-10",
  "jenis_disabilitas": "tunanetra"
}
```
> `jenis_disabilitas`: `tunanetra` (→ Audio World) atau `tunarungu` (→ Visual World)

#### `GET /api/children/{id}`
Detail data satu anak.

#### `PUT /api/children/{id}`
Memperbarui data anak.

#### `DELETE /api/children/{id}`
Menghapus data anak.

---

### 4. Dashboard Orang Tua

#### `GET /api/dashboard`
Ringkasan aktivitas semua anak.

#### `GET /api/dashboard/child/{child}`
Progress detail satu anak (lesson selesai, skor quiz, sesi belajar).

---

### 5. Lessons

#### `GET /api/lessons`
Daftar lesson (hanya metadata, tanpa media — lazy load).

**Query Params:** `?tipe_dunia=audio|visual`

#### `GET /api/lessons/{id}`
Detail lesson lengkap termasuk media dan quiz questions.

#### `POST /api/lessons/{id}/complete`
Menandai lesson sebagai selesai oleh anak.

**Body:** `{ "child_id": 1 }`

#### `POST /api/lessons/{id}/start-session`
Memulai sesi belajar.

**Body:** `{ "child_id": 1 }`

**Response:** `{ "session_id": 5 }`

#### `POST /api/sessions/{session_id}/end`
Mengakhiri sesi belajar.

---

### 6. Quiz

#### `POST /api/quiz/answer`
Mengirimkan jawaban quiz anak.

**Body:**
```json
{
  "child_id": 1,
  "lesson_id": 1,
  "quiz_question_id": 1,
  "jawaban_anak": "kucing",
  "benar": true,
  "skor": 100,
  "percobaan": 1
}
```

#### `GET /api/quiz/results`
Mendapatkan hasil quiz anak untuk lesson tertentu.

**Query Params:** `?child_id=1&lesson_id=1`

---

## ❌ Error Responses

| HTTP Code | Keterangan |
|-----------|------------|
| `400` | Bad Request — validasi gagal |
| `401` | Unauthenticated — token tidak ada / tidak valid |
| `403` | Forbidden — API Key tidak valid |
| `404` | Not Found — resource tidak ditemukan |
| `422` | Unprocessable Entity — error validasi Laravel |
| `500` | Internal Server Error |

**Contoh error 401:**
```json
{ "message": "Tidak terautentikasi. Sertakan Bearer token yang valid." }
```

**Contoh error 403 (API Key salah):**
```json
{ "message": "API Key tidak valid atau tidak diizinkan." }
```

**Contoh error 422:**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

## 🧪 Testing dengan Postman

1. Import file `SkillBridge_API.postman_collection.json` ke Postman
2. Set variable `base_url` = `http://skill_bridge.test/api`
3. Jalankan **Register** atau **Login** → token otomatis tersimpan ke variable `jwt_token`
4. Semua endpoint lain langsung bisa diuji menggunakan token tersebut

---

## 🗄️ Struktur Database & Relasi

```
users (orang tua)
  └── children (anak) [user_id FK]
        ├── quiz_results [child_id, lesson_id, quiz_question_id FK]
        ├── lesson_completions [child_id, lesson_id FK]
        └── study_sessions [child_id, lesson_id FK]

lessons
  ├── quiz_questions [lesson_id FK]
  ├── quiz_results [lesson_id FK]
  ├── lesson_completions [lesson_id FK]
  └── study_sessions [lesson_id FK]
```

---

## ⚙️ Konfigurasi JWT

Token JWT dikonfigurasi di `config/jwt.php`:
- **TTL**: 60 menit (default)
- **Refresh TTL**: 20160 menit (2 minggu)
- **Algoritma**: HS256

Untuk mengubah TTL, edit `JWT_TTL` di `.env`.

---

## 📁 Struktur File Penting

```
app/
  Http/
    Controllers/Api/
      AuthController.php      ← JWT + Basic Auth
      ChildController.php     ← CRUD anak
      LessonController.php    ← Lessons & sesi
      QuizController.php      ← Quiz
      ParentDashboardController.php
      ModuleController.php
    Middleware/
      ApiKeyMiddleware.php    ← Validasi X-API-Key
  Models/
    User.php                  ← implements JWTSubject
    Child.php                 ← BelongsTo User
    Lesson.php                ← HasMany relations
    ...
routes/
  api.php                     ← Semua route API
config/
  auth.php                    ← Guard 'api' dengan driver JWT
  jwt.php                     ← Konfigurasi JWT
.env                          ← JWT_SECRET, APP_API_KEY
SkillBridge_API.postman_collection.json  ← Import ke Postman
```
