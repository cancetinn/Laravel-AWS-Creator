# Server Management System

Server Management System, kullanıcıların AWS EC2 örneklerini kolayca oluşturmasına, yönetmesine ve izlemesine olanak tanıyan bir web tabanlı uygulamadır. Bu sistem, kullanıcılara arayüz üzerinden sunucu oluşturma, durum kontrolü ve sunucu yönetim işlemleri gibi özellikler sunar.

## Özellikler

Sunucu Oluşturma: Kullanıcılar, çeşitli özelliklere sahip sunucuları kolayca oluşturabilir.
Sunucu İzleme: Oluşturulan sunucuların durumunu ve performansını izleyebilir.
Sunucu Yönetimi: Sunucuları başlatma, durdurma ve silme işlemlerini gerçekleştirebilir.

### Başlarken

Bu bölüm, projeyi yerel makinenizde başlatmanız için gereken adımları içerir.

### Önkoşullar
Projeyi çalıştırmadan önce sisteminizde aşağıdaki yazılımların kurulu olması gerekmektedir:

```python
PHP (versiyon 7.3 veya üzeri)
Composer
Node.js ve npm
MySQL veya MariaDB
```

# Kurulum

Projeyi yerel olarak kurmak ve çalıştırmak için aşağıdaki adımları takip edin:

### Reposu Klonlayın
```bash
git clone https://github.com/cancetinn/laravel-aws-creator.git
cd laravel-aws-creator
```

### Bağımlılıkları Yükleyin
```bash
composer install
npm install
npm run dev
```

### Veritabanı Ayarlarını Yapılandırın

.env dosyasını düzenleyin ve veritabanı, AWS IAM erişim ayarlarınızı girin.

```python
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### Veritabanı Migrasyonlarını Çalıştırın
```bash
php artisan migrate
```

### Uygulamayı Çalıştırın
```bash
php artisan serve
```

Tarayıcınızda http://localhost:8000 adresini açarak uygulamayı kullanmaya başlayabilirsiniz.
Kullanım

Uygulamayı başlattıktan sonra, kullanıcılar kayıt olabilir ve giriş yapabilir. Ana dashboard üzerinden yeni sunucular oluşturabilir ve mevcut sunucuların yönetimini gerçekleştirebilirler.

### Geliştirme

Bu projeyi geliştirmeye katkıda bulunmak isterseniz, lütfen pull request göndermeden önce bir issue açarak değişikliklerinizi tartışın.

# Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için LICENSE dosyasına bakınız.