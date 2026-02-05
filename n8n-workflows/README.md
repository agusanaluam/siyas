# n8n Invoice & Zakat Automation Workflows

Automasi WhatsApp untuk input invoice donasi dan perhitungan zakat menggunakan n8n dan WAHA (WhatsApp HTTP API).

## Fitur

### 1. Input Invoice via NLP Text
Format pesan yang didukung:
```
no.liq 001 nominal 50ribu untuk shodaqoh
no.liq 002 dari Ahmad nominal 100000 untuk infaq
no.liq 003 nominal 1juta untuk zakat mal
```

### 2. Input Invoice via Gambar
Kirim foto kwitansi/invoice dan sistem akan otomatis mengekstrak data menggunakan OCR (OpenAI Vision).

### 3. Perhitungan Zakat Mal
```
hitung zakat mal penghasilan 150000000
hitung zakat mal 100juta
```

### 4. Perhitungan Zakat Fitrah
```
hitung zakat fitrah 5 orang
hitung zakat fitrah 3 jiwa
```

## Struktur Spreadsheet

Buat Google Spreadsheet dengan sheet bernama "Donasi" dan kolom berikut:

| No.Liq | Nama Donatur | Nominal | Jenis | Tanggal | Dari WA | Pesan Asli |
|--------|--------------|---------|-------|---------|---------|------------|

## File Workflow

1. **invoice-zakat-automation.json** - Workflow menggunakan webhook standar + HTTP Request untuk WAHA
2. **invoice-zakat-waha-native.json** - Workflow menggunakan n8n-nodes-waha native nodes (lebih mudah dikonfigurasi)

## Cara Setup

### Prasyarat
1. n8n instance (self-hosted atau cloud)
2. WAHA server running (https://github.com/devlikeapro/waha)
3. Google Sheets API access
4. OpenAI API key (untuk OCR gambar)

### Langkah-langkah

#### 1. Setup WAHA Server
```bash
docker run -d \
  --name waha \
  -p 3000:3000 \
  -e WHATSAPP_HOOK_URL=https://your-n8n-url/webhook/waha-webhook \
  -e WHATSAPP_HOOK_EVENTS=message \
  devlikeapro/waha
```

#### 2. Install n8n-nodes-waha (untuk workflow native)
```bash
# Di n8n instance Anda
npm install n8n-nodes-waha
```

Atau via n8n UI: Settings > Community Nodes > Install > `n8n-nodes-waha`

#### 3. Import Workflow ke n8n
1. Buka n8n
2. Klik "Import from file"
3. Pilih salah satu file JSON workflow
4. Klik "Import"

#### 4. Konfigurasi Credentials

##### Google Sheets OAuth2
1. Buat project di Google Cloud Console
2. Enable Google Sheets API
3. Buat OAuth2 credentials
4. Di n8n: Credentials > New > Google Sheets OAuth2
5. Masukkan Client ID dan Client Secret

##### OpenAI API
1. Dapatkan API key dari https://platform.openai.com
2. Di n8n: Credentials > New > OpenAI API
3. Masukkan API Key

##### WAHA API (untuk workflow native)
1. Di n8n: Credentials > New > WAHA API
2. Masukkan:
   - Host URL: `http://your-waha-server:3000`
   - API Key: (jika dikonfigurasi di WAHA)

#### 5. Update Konfigurasi Workflow

Ganti placeholder berikut di workflow:
- `YOUR_SPREADSHEET_ID` - ID Google Spreadsheet Anda
- `YOUR_GOOGLE_CREDENTIAL_ID` - ID credential Google Sheets
- `YOUR_OPENAI_CREDENTIAL_ID` - ID credential OpenAI
- `YOUR_WAHA_CREDENTIAL_ID` - ID credential WAHA
- `YOUR_WAHA_SERVER` - URL server WAHA Anda

#### 6. Konfigurasi Webhook WAHA

Di WAHA, set webhook URL:
```
POST http://your-n8n-url/webhook/waha-webhook
```

Events yang perlu di-subscribe:
- `message` - untuk menerima pesan masuk

#### 7. Aktivasi Workflow
1. Klik toggle "Active" di workflow
2. Test dengan mengirim pesan ke nomor WhatsApp yang terkoneksi

## Konfigurasi Zakat

### Zakat Mal
Default konfigurasi (bisa diubah di node "Calculate Zakat Mal"):
- Harga emas: Rp 1.200.000/gram
- Nisab: 85 gram emas (~Rp 102.000.000)
- Tarif: 2.5%

### Zakat Fitrah
Default konfigurasi (bisa diubah di node "Calculate Zakat Fitrah"):
- Harga beras: Rp 18.000/kg
- Takaran per jiwa: 2.5 kg atau 3.5 liter
- Total per jiwa: Rp 45.000

## Contoh Penggunaan

### Input Invoice Text
```
User: no.liq 2024/001 dari Ahmad nominal 500ribu untuk shodaqoh
Bot: *Data Invoice Berhasil Disimpan!*
     No.Liq: 2024/001
     Nama Donatur: Ahmad
     Nominal: Rp 500.000
     Jenis: Shodaqoh
     ...
```

### Hitung Zakat Mal
```
User: hitung zakat mal penghasilan 200000000
Bot: *Perhitungan Zakat Mal*
     Penghasilan/Harta: Rp 200.000.000
     Nisab: Rp 102.000.000
     Status: WAJIB ZAKAT
     Zakat yang harus dibayar: Rp 5.000.000
     ...
```

### Hitung Zakat Fitrah
```
User: hitung zakat fitrah 4 orang
Bot: *Perhitungan Zakat Fitrah*
     Jumlah Jiwa: 4 orang
     Total Zakat Fitrah:
     - Beras: 10 kg
     - Uang: Rp 180.000
     ...
```

## Troubleshooting

### Pesan tidak terdeteksi
- Pastikan webhook WAHA sudah dikonfigurasi dengan benar
- Cek apakah format pesan sesuai dengan pattern yang didukung
- Lihat log n8n untuk error details

### OCR tidak berfungsi
- Pastikan OpenAI API key valid dan memiliki akses ke GPT-4 Vision
- Pastikan gambar yang dikirim cukup jelas

### Data tidak tersimpan ke spreadsheet
- Pastikan Google Sheets credentials valid
- Pastikan spreadsheet ID benar
- Pastikan sheet "Donasi" sudah ada dengan kolom yang sesuai

## Support

Untuk pertanyaan atau issues, silakan buat issue di repository ini.
