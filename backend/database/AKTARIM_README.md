# 🚀 Eski Sistem Veri Aktarım Rehberi

Bu dokümantasyon, eski pazarlama sisteminden yeni Laravel tabanlı sisteme veri aktarımını açıklar.

## 📋 Aktarım Özeti

- **Hedef:** 5 talep türü (Kayar Pano, Dijital Baskı, Cephe/Tabela, Teşhir Yenileme, Yeni Mağaza)
- **Kaynak:** `urunngkutahyaser_pazarlama.sql` dump dosyası
- **Platform:** Railway PostgreSQL
- **Beklenen Veri:** ~300-500 talep kaydı

## 🎯 Talep Türü Eşleştirmesi

| Eski Sistem | Yeni Sistem ID | Yeni Sistem Adı | İş Akışı |
|-------------|---------------|-----------------|----------|
| Kayar Pano | 1 | Kayar Pano | tip_a |
| Dijital Baskı | 2 | Dijital Baskı | tip_a |
| Cephe/Tabela | 4 | Tabela | tip_a |
| Teşhir Yenileme | 6 | Teşhir Yenileme | tip_b |
| Yeni Mağaza | 7 | Mağaza Projelendirme | tip_b |

## 🛠️ Gerekli Adımlar

### 1. Hazırlık (Local)

```bash
# 1. SQL dosyasını kopyala
cp /path/to/urunngkutahyaser_pazarlama.sql ./database/scripts/

# 2. Veri çıkarma script'ini çalıştır
cd database/scripts
php extract_eski_sistem_data.php urunngkutahyaser_pazarlama.sql

# 3. Çıkarılan veriyi kontrol et
cat extracted_data.php
```

### 2. Local Test (Opsiyonel)

```bash
# Local veritabanı oluştur
mysql -u root -p -e "CREATE DATABASE ngks_test;"

# .env.local dosyası oluştur
cp .env.example .env.local

# .env.local içinde database ayarları:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ngks_test
DB_USERNAME=root
DB_PASSWORD=your_password

# Migration ve seeders çalıştır
php artisan migrate --env=local
php artisan db:seed --env=local
```

### 3. Railway Deployment

```bash
# Backend klasöründe
cd backend

# Composer bağımlılıklarını güncelle
composer install --no-dev --optimize-autoloader

# Laravel optimizasyonları
php artisan config:cache
php artisan route:cache

# Git işlemleri
git add .
git commit -m "feat: Eski sistem veri aktarım seederları eklendi"
git push origin main
```

### 4. Railway Üzerinde Veri Aktarımı

```bash
# Railway CLI ile bağlan
railway login
railway link

# Veritabanı backup al (güvenlik için)
railway db backup

# Temel seeders çalıştır (eğer çalışmadıysa)
railway run php artisan db:seed --class=BolgelerSeeder
railway run php artisan db:seed --class=SehirlerSeeder
railway run php artisan db:seed --class=IlcelerSeeder
railway run php artisan db:seed --class=TalepTurleriSeeder
railway run php artisan db:seed --class=AsamalarSeeder
railway run php artisan db:seed --class=BolgeMimarlariSeeder
railway run php artisan db:seed --class=BayiMagazaSeeder
railway run php artisan db:seed --class=AdminSeeder

# Eski sistem verilerini aktar
railway run php artisan db:seed --class=EskiSistemTaleplerSeeder
railway run php artisan db:seed --class=EskiSistemTalepAsamaGecmisiSeeder
```

### 5. Veri Doğrulama

```bash
# Railway terminal üzerinden kontrol
railway run php artisan tinker

# Tinker içinde:
>>> Talep::count()
>>> TalepAsamaGecmisi::count()
>>> Talep::with('talepTuru')->get()->groupBy('talepTuru.ad')
>>> Talep::where('arsivlendi_mi', false)->count()
```

## 🔧 Seeder Dosyaları

### Ana Seederlar

1. **EskiSistemTaleplerSeeder.php**
   - Eski sistem taleplerinı aktarır
   - Veri dönüştürme kurallarını uygular
   - Eksik alanları otomatik tamamlar

2. **EskiSistemTalepAsamaGecmisiSeeder.php**
   - Her talep için aşama geçmişi oluşturur
   - İş akışı kurallarına uygun zaman damgaları
   - Sistem kullanıcısı ile log kayıtları

### Yardımcı Dosyalar

3. **extract_eski_sistem_data.php**
   - Büyük SQL dosyasından filtrelenmiş veri çıkarır
   - Sadece 5 talep türünü işler
   - PHP array formatında export eder

## ⚠️ Önemli Notlar

### Veri Güvenliği
- **Her zaman backup alın**: Railway üzerinde işlem öncesi backup
- **Aşamalı test**: Local → Staging → Production
- **Rollback planı**: Hata durumunda geri alma prosedürü

### Performance
- **Batch işlemler**: Büyük veri setleri için chunk kullanımı
- **Memory limit**: PHP memory_limit kontrolü
- **Timeout**: Uzun süren işlemler için timeout ayarı

### Veri Bütünlüğü
- **Foreign key kontrolü**: İlişkili tablolar varlık kontrolü
- **Enum değer kontrolü**: magaza_tipi ve is_akisi_tipi
- **Tarih formatı**: Carbon kullanımı ve timezone

## 🚨 Hata Durumları

### Sık Karşılaşılan Hatalar

1. **Foreign Key Constraint**
   ```
   Çözüm: İlişkili tabloların (bayiler, bolgeler) dolu olduğunu kontrol et
   ```

2. **Memory Limit**
   ```
   Çözüm: php.ini memory_limit artır veya chunk kullan
   ```

3. **Timeout**
   ```
   Çözüm: max_execution_time artır veya smaller batches
   ```

### Rollback Prosedürü

```bash
# 1. Railway backup restore
railway db restore <backup_id>

# 2. Veya manuel temizlik
railway run php artisan tinker
>>> DB::table('talep_asama_gecmisi')->truncate();
>>> DB::table('talepler')->truncate();

# 3. Demo verileri yükle
railway run php artisan db:seed --class=DemoTaleplerSeeder
```

## 📊 Beklenen Sonuçlar

- **Aktarılacak Talep:** ~300-500 kayıt
- **Aşama Geçmişi:** ~600-1000 log kaydı
- **İşlem Süresi:** 2-5 dakika
- **Tekrar Edilebilir:** Evet (truncate + insert)

## 📞 Destek

Aktarım sırasında sorun yaşanırsa:

1. **Log kontrolü**: Railway logs kontrol et
2. **Laravel log**: storage/logs/laravel.log
3. **Database log**: Railway dashboard > Database > Logs

---

**Son Güncelleme:** 2025-10-17  
**Versiyon:** 1.0  
**Durum:** Hazır - Test Aşamasında