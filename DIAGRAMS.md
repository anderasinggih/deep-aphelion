# Diagram Sistem Kembaran Ngadu (Mermaid.js) 📊

Dokumen ini berisi kumpulan diagram sistem **Kembaran Ngadu** yang ditulis dalam format **Mermaid.js** sesuai dengan standar rendering Mermaid Live.

---

## 1. Activity Diagram (Global)

```mermaid
---
config:
  theme: default
  themeVariables: {}
---
flowchart TD
    subgraph Warga [WARGA]
        W_Start(( ))
        W_Input["Isi Formulir &<br/>Upload Foto (Kompresi Client)"]
        W_Detail["Buka Detail Laporan Selesai<br/>Tunggu 15 Detik"]
        W_Rating["Isi Ulasan & Rating<br/>(4 Metrik Pelayanan)"]
        W_End["Terima Notif &<br/>Lihat Hasil"]
        W_Stop(( ))
    end

    subgraph Sistem [SISTEM]
        S_Val["Validasi Data &<br/>Generate Tracking"]
        S_Log["Catat Riwayat &<br/>Update Timeline"]
        S_Notify["Kirim Notifikasi WA"]
        S_PromptRating["Tampilkan Modal Rating"]
        S_SaveRating["Simpan Rating & Ulasan"]
    end

    subgraph Admin [ADMIN / PETUGAS]
        A_Review["Review &<br/>Verifikasi Laporan"]
        A_Decision{Laporan<br/>Valid?}
        A_Process["Update Status:<br/>Diproses"]
        A_Finish["Input Hasil &<br/>Foto Selesai (Opsional)"]
        A_Reject["Update Status:<br/>Ditolak"]
    end
    W_Start ~~~ S_Val ~~~ A_Review
    W_Start --> W_Input
    W_Input --> S_Val
    S_Val --> A_Review
    
    A_Review --> A_Decision
    A_Decision -- Ya --> A_Process
    A_Process --> A_Finish
    A_Finish --> S_Log
    
    A_Decision -- Tidak --> A_Reject
    A_Reject --> S_Log
    
    S_Log --> S_Notify
    S_Notify --> W_End
    W_End --> W_Detail
    
    W_Detail --> S_PromptRating
    S_PromptRating --> W_Rating
    W_Rating --> S_SaveRating
    S_SaveRating --> W_Stop

    classDef default font-family:Arial,font-size:12px;
    style Warga fill:#fff,stroke:#333,stroke-width:2px
    style Sistem fill:#fff,stroke:#333,stroke-width:2px
    style Admin fill:#fff,stroke:#333,stroke-width:2px
```

---

## 2. Entity Relationship Diagram (ERD)

```mermaid
---
config:
  theme: default
  look: handDrawn
  fontFamily: '''Recursive Variable'', sans-serif'
  themeVariables:
    fontFamily: '''Recursive Variable'', sans-serif'
title: Sample title
---
erDiagram
    direction TB
    USERS {
        bigint id PK ""  
        string name  ""  
        string nik  "Unique"  
        string email  "Unique"  
        string no_wa  ""  
        string password  ""  
        enum role  "warga, petugas, admin"  
        timestamp deleted_at  "Soft Delete"  
    }

    PENGADUANS {
        bigint id PK ""  
        string kode_tracking  "Unique"  
        bigint user_id FK "nullable"  
        string guest_name "nullable"
        string guest_wa "nullable"
        bigint kategori_id FK ""  
        string judul  ""  
        text deskripsi  ""  
        date tanggal_kejadian  ""  
        json foto_bukti  ""  
        string lokasi_kejadian  ""  
        decimal latitude  ""  
        decimal longitude  ""  
        enum status  "menunggu, diproses, selesai, ditolak"  
        boolean is_anonymous  ""  
        boolean is_private  ""  
        json foto_penyelesaian "nullable"
        text pesan_penutup  "Feedback Admin"  
        text catatan_internal "nullable"
        bigint linked_id FK "Self Reference"
        timestamp created_at  ""  
        timestamp deleted_at "Soft Delete"
    }

    PENGADUAN_RATINGS {
        bigint id PK ""
        bigint pengaduan_id FK ""
        bigint user_id FK "nullable"
        string ip_address ""
        integer rating_pelayanan "1-5"
        integer rating_respon "1-5"
        integer rating_kompetensi "1-5"
        integer rating_fasilitas "1-5"
        float rating "Rata-rata"
        text rating_komentar "nullable"
        timestamp created_at ""
    }

    PENGADUAN_HISTORIES {
        bigint id PK ""  
        bigint pengaduan_id FK ""  
        bigint user_id FK "Petugas"  
        string status_sebelumnya  ""  
        string status_baru  ""  
        text keterangan_admin  ""  
        string foto_bukti  ""  
        timestamp created_at  ""  
    }

    PENGADUAN_KOMENTARS {
        bigint id PK ""  
        bigint pengaduan_id FK ""  
        bigint user_id FK ""  
        bigint parent_id FK "Self Reference"  
        text komentar  ""  
        timestamp created_at  ""  
    }

    PENGADUAN_DUKUNGANS {
        bigint id PK ""  
        bigint pengaduan_id FK ""  
        bigint user_id FK ""  
        timestamp created_at  ""  
    }

    KATEGORIS {
        bigint id PK ""  
        string nama  ""  
        string icon  ""  
        string deskripsi  ""  
        integer sla_hari  "Target Penyelesaian"  
        timestamp deleted_at  ""  
    }

    USERS||--o{PENGADUANS:"melaporkan"
    USERS||--o{PENGADUAN_HISTORIES:"menangani"
    USERS||--o{PENGADUAN_KOMENTARS:"menulis"
    USERS||--o{PENGADUAN_DUKUNGANS:"memberi"
    USERS||--o{PENGADUAN_RATINGS:"memberikan"
    KATEGORIS||--o{PENGADUANS:"mengelompokkan"
    PENGADUANS||--o{PENGADUAN_HISTORIES:"memiliki riwayat"
    PENGADUANS||--o{PENGADUAN_KOMENTARS:"memiliki"
    PENGADUANS||--o{PENGADUAN_DUKUNGANS:"mendapat"
    PENGADUANS||--o{PENGADUAN_RATINGS:"memiliki"
    PENGADUAN_KOMENTARS||--o{PENGADUAN_KOMENTARS:"balasan (parent_id)"
```

---

## 3. Use Case Diagram

```mermaid
---
config:
  theme: default
---
graph TD
    W((Warga))
    P((Petugas))
    A((Admin))

    subgraph Sistem_Kembaran_Ngadu [Sistem Pengaduan Masyarakat]
        direction TB
        UC1("Login (Petugas / Admin)")
        subgraph Fitur_Warga [Aksi Warga]
            direction LR
            UC2("Kirim Pengaduan & Kompresi Foto")
            UC3("Lacak Status & Notifikasi WA")
            UC4("Dukungan & Komentar")
            UC_Rate("Beri Rating & Ulasan")
        end
        subgraph Fitur_Operasional [Aksi Petugas/Admin]
            direction LR
            UC5("Kelola Laporan")
            UC6("Update Status Selesai/Ditolak")
        end
        subgraph Fitur_Admin [Aksi Admin]
            direction LR
            UC7("Kelola Kategori & SLA")
            UC8("Kelola Pengguna")
            UC9("Audit Trail & Executive Report")
        end
    end
    W --- Fitur_Warga
    
    P --- UC1
    P --- Fitur_Operasional
    
    A --- UC1
    A --- Fitur_Operasional
    A --- Fitur_Admin
    style Sistem_Kembaran_Ngadu fill:#fff,stroke:#333
    style Fitur_Warga fill:#f0f7ff,stroke:#0284c7,stroke-dasharray: 5 5
    style Fitur_Operasional fill:#fff7ed,stroke:#ea580c,stroke-dasharray: 5 5
    style Fitur_Admin fill:#f8fafc,stroke:#64748b,stroke-dasharray: 5 5
```

---

## 4. Activity Diagram Pengiriman Laporan oleh Warga

```mermaid
---
config:
  theme: default
  themeVariables: {}
---
flowchart TB
 subgraph Kolom_Warga["WARGA"]
        W_Start(("Mulai"))
        W_Form["Isi Form Pengaduan<br>Judul, Kategori, Foto, Lokasi"]
        W_Compress["Kompresi Gambar (Client-Side Canvas)<br>Maks 800px, Kualitas 30% (~35KB)"]
        W_Submit["Klik Tombol Kirim"]
        W_Fix["Perbaiki Data"]
        W_Success["Lihat Resi / Kode Tracking"]
        W_Stop(("Selesai"))
  end
 subgraph Kolom_Sistem["SISTEM"]
        S_Validate{"Validasi Data?"}
        S_Error["Tampilkan Error"]
        S_Process["Generate Kode Tracking<br>& Simpan ke Database"]
        S_Notify["Kirim Notifikasi ke Admin"]
  end
    W_Start --> W_Form
    W_Form --> W_Compress
    W_Compress --> W_Submit
    W_Fix -.-> W_Form
    W_Success --> W_Stop
    W_Submit --> S_Validate
    S_Validate -- Tidak --> S_Error
    S_Error --> W_Fix
    S_Validate -- Ya --> S_Process
    S_Process --> S_Notify
    S_Notify --> W_Success
    W_Start ~~~ S_Validate

    style W_Start fill:#FFCDD2
    style W_Stop fill:#FFCDD2
    style Kolom_Warga fill:#fff,stroke:#333,stroke-width:2px
    style Kolom_Sistem fill:#fff,stroke:#333,stroke-width:2px
```

---

## 5. Activity Diagram Penanganan Laporan (Validasi)

```mermaid
%%{init: {'theme': 'base', 'themeVariables': { 'subgraphPadding': 10 }}}%%
flowchart TD
    %% Kolom Admin
    subgraph Kolom_Admin [ADMIN / PETUGAS]
        A_Start((Mulai)) --> A_View[Buka Dashboard &<br/>Pilih Laporan Baru]
        A_View --> A_Review[Review Foto & Lokasi]
        A_Review --> A_Decision{Valid?}
        
        A_Decision -- Tidak --> A_Reject[Input Alasan &<br/>Klik Tolak]
        A_Decision -- Ya --> A_Accept[Klik Proses &<br/>Set Prioritas]
    end

    %% Kolom Sistem
    subgraph Kolom_Sistem [SISTEM]
        A_Reject --> S_Status_Reject[Update Status: Ditolak<br/>& Simpan Alasan]
        A_Accept --> S_Status_Process[Update Status: Diproses<br/>& Catat History]
        
        S_Status_Reject --> S_Notify[Kirim Notifikasi<br/>ke Warga]
        S_Status_Process --> S_Notify
    end

    %% Selesai
    S_Notify --> A_End((Selesai))

    %% Penyelarasan
    A_Start ~~~ S_Status_Reject

    %% Styling
    style A_Start fill:#000
    style A_End fill:#000
    style Kolom_Admin fill:#fff,stroke:#333,stroke-width:2px
    style Kolom_Sistem fill:#fff,stroke:#333,stroke-width:2px
```

---

## 6. Activity Diagram Penyelesaian & Umpan Balik

```mermaid
---
config:
  theme: default
  themeVariables: {}
  layout: dagre
---
flowchart TB
 subgraph Kolom_Petugas["PETUGAS / ADMIN"]
        P_Start(("Mulai"))
        P_Action["Melakukan Perbaikan/<br>Tindakan di Lapangan"]
        P_Doc["Ambil Foto Bukti<br>Penyelesaian (Opsional)"]
        P_Input["Unggah Foto (jika ada) &amp;<br>Isi Pesan Penutup"]
        P_Submit["Klik Tombol Selesai"]
  end
 subgraph Kolom_Sistem["SISTEM"]
        S_Val["Validasi Data"]
        S_Update["Update Status: Selesai<br>&amp; Simpan Bukti"]
        S_History["Catat Riwayat Pelayanan"]
        S_Notify["Kirim Notifikasi Final<br>ke WhatsApp Warga"]
        S_Delay{"Tunggu 15 Detik di Detail?"}
        S_Modal["Tampilkan Modal Penilaian"]
  end
 subgraph Kolom_Warga["WARGA"]
        W_View["Buka Detail Laporan"]
        W_Rate["Beri Rating 4 Metrik<br>&amp; Tulis Ulasan"]
        W_SubmitRate["Submit Rating"]
  end
    P_Start --> P_Action
    P_Action --> P_Doc
    P_Doc --> P_Input
    P_Input --> P_Submit
    P_Submit --> S_Val
    S_Val --> S_Update
    S_Update --> S_History
    S_History --> S_Notify
    S_Notify --> W_View
    W_View --> S_Delay
    S_Delay -- Ya --> S_Modal
    S_Modal --> W_Rate
    W_Rate --> W_SubmitRate
    W_SubmitRate --> P_End(("Selesai"))
    
    P_Start ~~~ S_Val

    style P_Start fill:#FFCDD2
    style P_End fill:#FFCDD2
    style Kolom_Petugas fill:#fff,stroke:#333,stroke-width:2px
    style Kolom_Sistem fill:#fff,stroke:#333,stroke-width:2px
    style Kolom_Warga fill:#fff,stroke:#333,stroke-width:2px
```

---

## 7. Sequence Diagram Proses Kirim Pengaduan

```mermaid
---
config:
  theme: default
---
sequenceDiagram
    actor W as Warga
    participant V as View: PengaduanForm
    participant JS as JS: Canvas Compressor
    participant C as Controller: Livewire Class
    participant S as Storage: FileSystem
    participant M as Model: Pengaduan
    participant DB as Database: MySQL

    W->>V: Isi Data Laporan (Judul, Deskripsi, Kategori, Lokasi)
    W->>V: Unggah Foto Bukti
    activate V
    V->>JS: Ambil File Foto Asli
    activate JS
    Note over JS: Resize (Maks 800px) & Kompresi Kualitas (30%)
    JS-->>V: Kembalikan Blob/File Terkompresi (~35KB)
    deactivate JS
    
    W->>V: Klik Tombol "Kirim"
    V->>C: save() / submit()
    activate C
    C->>C: validate(rules)
    Note right of C: Cek NIK, Ukuran Foto, & Input Wajib
    alt Data Tidak Valid
        C-->>V: return Validation Errors
        V-->>W: Tampilkan Pesan Error
    else Data Valid
        C->>S: storePhoto(foto_bukti_terkompresi)
        activate S
        S-->>C: return FilePath
        deactivate S
        
        C->>M: create(data_pengaduan + filePath)
        activate M
        M->>DB: INSERT INTO pengaduans
        activate DB
        DB-->>M: return Success & ID (PK)
        deactivate DB
        M-->>C: return Pengaduan Object
        deactivate M
        
        C->>C: triggerNotification()
        Note right of C: Kirim notif ke WhatsApp & Admin Panel
        
        C-->>V: flash("Laporan Berhasil Terkirim")
        V-->>W: Redirect ke Detail Pengaduan (Resi)
    end
    deactivate C
    deactivate V
```

---

## 8. Sequence Diagram Update Status & Umpan Balik Rating

```mermaid
---
config:
  theme: default
---
sequenceDiagram
    actor A as Admin / Petugas
    actor W as Warga
    participant V as View: PengaduanDetail
    participant C as Controller: Admin/User Class
    participant M1 as Model: Pengaduan
    participant M2 as Model: PengaduanHistory
    participant MR as Model: PengaduanRating
    database DB as Database: MySQL
    participant N as System: Notification

    A->>V: Pilih Status Baru (Selesai)
    A->>V: Input Catatan / Foto Hasil (Opsional)
    A->>V: Klik "Update Status"
    activate V
    V->>C: updateStatus(status, catatan)
    activate C
    C->>C: validate()
    Note over C, DB: Start Transaction
    C->>M1: update(status_baru)
    activate M1
    M1->>DB: UPDATE pengaduans SET status = 'selesai'
    activate DB
    DB-->>M1: return Success
    deactivate DB
    M1-->>C: updated
    deactivate M1
    C->>M2: create(log_riwayat)
    activate M2
    M2->>DB: INSERT INTO pengaduan_histories
    activate DB
    DB-->>M2: return Success
    deactivate DB
    M2-->>C: history_created
    deactivate M2
    Note over C, DB: Commit Transaction
    C->>N: sendStatusNotification(user_id)
    activate N
    N-->>C: WhatsApp Notif Sent
    deactivate N
    C-->>V: emit("statusUpdated")
    deactivate C
    V-->>A: Refresh & Tampilkan Status Selesai
    deactivate V

    W->>V: Klik Link Notifikasi WA / Kunjungi Detail Laporan Selesai
    activate V
    V->>V: Hitung Delay 15 Detik
    Note over V: Auto-Popup modal rating terpicu
    V-->>W: Tampilkan Modal Penilaian
    W->>V: Isi 4 Metrik Bintang & Komentar
    W->>V: Klik "Kirim Penilaian"
    V->>C: submitRating(pelayanan, respon, kompetensi, fasilitas, ulasan)
    activate C
    C->>MR: create(rating_data)
    activate MR
    MR->>DB: INSERT INTO pengaduan_ratings
    activate DB
    DB-->>MR: return Success
    deactivate DB
    MR-->>C: rating_created
    deactivate MR
    C-->>V: Refresh rating container
    deactivate C
    V-->>W: Tampilkan ulasan terkirim & Refresh list ulasan (Alpine pagination)
    deactivate V
```
