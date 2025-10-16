# NGKS Ticaret Pazarlama Sistemi - Railway Edition

Railway cloud platform için optimize edilmiş modern React frontend ve Laravel API backend ile geliştirilmiş talep yönetim sistemi.

## 🚀 Railway Deployment Özellikleri

- **Full-Stack Deployment**: Frontend + Backend tek container'da
- **Otomatik Database Setup**: Migration ve seeder'lar otomatik çalışır
- **Production Optimized**: Cache, compression ve güvenlik ayarları
- **Health Check**: `/api/health` endpoint'i ile otomatik monitoring
- **Zero Config**: Environment variables otomatik ayarlanır

## 🏗️ Teknoloji Stack

### Frontend
- **React 18**: Modern JavaScript framework
- **Vite**: Hızlı build tool
- **Axios**: HTTP client
- **React Router**: SPA routing

### Backend
- **Laravel 9**: PHP web framework
- **MySQL**: Railway PostgreSQL/MySQL database
- **JWT Auth**: Token-based authentication
- **Apache**: Web server with mod_rewrite

### Infrastructure
- **Docker**: Multi-stage build
- **Railway**: Cloud deployment platform
- **Apache**: Production web server

## 📁 Railway Proje Yapısı

```
ngks-ticpaz-railway/
├── Dockerfile                  # Multi-stage build
├── railway.json               # Railway configuration
├── railway/                   # Railway deployment files
│   ├── apache-config.conf     # Apache virtual host
│   └── start.sh              # Startup script
├── backend/                   # Laravel API
└── frontend/                  # React Frontend
```

## 🔧 Railway Deployment

### Otomatik Deployment (Önerilen)

1. **GitHub Repository'yi Railway'e Import Et**
   ```
   https://railway.app → New Project → GitHub Repo
   ```

2. **Environment Variables Ayarla**
   ```
   DATABASE_URL=postgresql://... (Railway otomatik sağlar)
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app.railway.app
   ```

3. **Deploy Et**
   - Railway otomatik olarak Dockerfile'ı detect eder
   - Build ve deploy işlemi başlar
   - Health check ile uygulama durumu kontrol edilir

### Manuel Deployment

```bash
# Railway CLI kur
npm install -g @railway/cli

# Login ol
railway login

# Proje oluştur
railway init

# Environment variables ayarla
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false

# Deploy et
railway up
```

## 🗄️ Database Setup

Railway otomatik olarak şunları yapar:

1. **Database Bağlantısı**: `DATABASE_URL` environment variable'ı kullanır
2. **Migration'lar**: `php artisan migrate --force`
3. **Seeder'lar**: `php artisan db:seed --force`
4. **Cache Optimization**: Production için optimize eder

## 🌐 Endpoint'ler

### Frontend
- **Ana Sayfa**: `https://your-app.railway.app/`
- **Login**: `https://your-app.railway.app/login`
- **Dashboard**: `https://your-app.railway.app/dashboard`

### API
- **Health Check**: `GET /api/health`
- **Login**: `POST /api/auth/login`
- **Talepler**: `GET /api/talepler`
- **Lokasyonlar**: `GET /api/locations/bolgeler`

## 🔑 Demo Kullanıcılar

```
Admin:
Email: admin@test.com
Şifre: 123456

Pazarlama Uzmanı:
Email: pazarlama@test.com
Şifre: 123456

Direktör:
Email: direktor@test.com
Şifre: 123456

Bölge Mimarı:
Email: mimar@test.com
Şifre: 123456
```

## 📊 Monitoring

### Health Check
```bash
curl https://your-app.railway.app/api/health
```

### Logs
Railway dashboard'da real-time logs mevcut:
```
Railway Dashboard → Your Project → Deployments → View Logs
```

## 🔧 Environment Variables

Railway'de ayarlanması gereken değişkenler:

```env
# Otomatik ayarlananlar
DATABASE_URL=postgresql://...
PORT=80

# Manuel ayarlanması gerekenler
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
FRONTEND_URL=https://your-app.railway.app

# Opsiyonel
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
```

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   ```bash
   # Railway logs'da DATABASE_URL kontrol et
   railway logs
   ```

2. **Frontend 404 Error**
   ```bash
   # Apache rewrite rules aktif mi kontrol et
   railway exec ls -la public/
   ```

3. **API Endpoints 404**
   ```bash
   # Routes cache temizle
   railway exec php artisan route:clear
   ```

### Debug Commands

```bash
# Container'a bağlan
railway shell

# Laravel artisan komutları
railway exec php artisan route:list
railway exec php artisan migrate:status
railway exec php artisan config:show database
```

## 🚀 Production Optimization

Railway deployment otomatik olarak şunları yapar:

- ✅ Frontend build optimization (Vite)
- ✅ Laravel config cache
- ✅ Route cache
- ✅ Composer autoload optimization
- ✅ Apache compression (gzip)
- ✅ Security headers
- ✅ PHP OPcache
- ✅ Laravel queue workers (opsiyonel)

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## 👨‍💻 Geliştirici

Ahmed Özdemir - [ahmetozdemir.com.tr](https://ahmetozdemir.com.tr)

---

**Railway Deployment URL**: `https://your-app.railway.app`  
**GitHub Repository**: `https://github.com/zdmr16/ngks-ticpaz-railway`