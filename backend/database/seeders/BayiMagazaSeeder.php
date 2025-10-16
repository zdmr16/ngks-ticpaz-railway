<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bayi;
use App\Models\BayiMagazasi;
use App\Models\Sehir;
use App\Models\Ilce;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BayiMagazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('BayiMagazaSeeder başlatıldı');
        
        $data = [
            ['bayi_adi' => 'Ada Yapı Malzemeleri', 'magaza_adi' => 'Ada Yapı Malzemeleri', 'sehir' => 'İSTANBUL', 'ilce' => 'Silivri'],
            ['bayi_adi' => 'AHM Doğan Yapı', 'magaza_adi' => 'AHM Doğan Yapı Malzemeleri', 'sehir' => 'GİRESUN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aksaray Anadolu AŞ', 'magaza_adi' => 'Aksaray Anadolu AŞ', 'sehir' => 'AKSARAY', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aksu Yapı', 'magaza_adi' => 'Aksu Yapı', 'sehir' => 'KONYA', 'ilce' => 'Akşehir'],
            ['bayi_adi' => 'Aktif İnşaat', 'magaza_adi' => 'Aktif İnşaat', 'sehir' => 'ORDU', 'ilce' => 'Ünye'],
            ['bayi_adi' => 'Alara Yapı Malzemeleri', 'magaza_adi' => 'Alara Yapı Malzemeleri', 'sehir' => 'ANTALYA', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Mecidiyeköy Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Ankara Showroom', 'magaza_adi' => 'Ankara Showroom', 'sehir' => 'ANKARA', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'Antalya Showroom', 'magaza_adi' => 'Antalya Showroom', 'sehir' => 'ANTALYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Arı İnşaat', 'magaza_adi' => 'Arı İnşaat', 'sehir' => 'İSTANBUL', 'ilce' => 'Beyoğlu'],
            ['bayi_adi' => 'Arslan Ticaret', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'GİRESUN', 'ilce' => 'Bulancak'],
            ['bayi_adi' => 'Arslan Ticaret', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'GİRESUN', 'ilce' => 'Bulancak'],
            ['bayi_adi' => 'Arslanlar Yapı Seramik', 'magaza_adi' => 'Arslanlar Yapı Seramik', 'sehir' => 'SİVAS', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Artdecor Yapı Malzemeleri', 'magaza_adi' => 'Artdecor Yapı Malzemeleri', 'sehir' => 'İZMİR', 'ilce' => 'Alsancak'],
            ['bayi_adi' => 'Asilas Yapı', 'magaza_adi' => 'Asilas Yapı', 'sehir' => 'ANTALYA', 'ilce' => 'Döşemealtı'],
            ['bayi_adi' => 'Atıl İnşaat', 'magaza_adi' => 'Atıl İnşaat', 'sehir' => 'İSTANBUL', 'ilce' => 'Sancaktepe'],
            ['bayi_adi' => 'Atılım Yapı', 'magaza_adi' => 'Atılım Yapı', 'sehir' => 'TRABZON', 'ilce' => 'Ortahisar'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Isparta Şube', 'sehir' => 'ISPARTA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Alanya Şube', 'sehir' => 'ANTALYA', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Aysa Yapı', 'magaza_adi' => 'Yalıkavak Şube', 'sehir' => 'MUĞLA', 'ilce' => 'Bodrum'],
            ['bayi_adi' => 'Aysa Yapı', 'magaza_adi' => 'Konacık Şube', 'sehir' => 'MUĞLA', 'ilce' => 'Bodrum'],
            ['bayi_adi' => 'Balçıklar Yapı', 'magaza_adi' => 'Balçıklar Yapı', 'sehir' => 'KOCAELİ', 'ilce' => 'Derince'],
            ['bayi_adi' => 'Balsera A.Ş.', 'magaza_adi' => 'Balsera A.Ş.', 'sehir' => 'ANKARA', 'ilce' => 'Yenimahalle'],
            ['bayi_adi' => 'Batman Güven Yapı', 'magaza_adi' => 'Batman Güven Yapı', 'sehir' => 'BATMAN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Baysak Yapı', 'magaza_adi' => 'Baysak Yapı', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Çerkezköy'],
            ['bayi_adi' => 'Beşel Yapı Malzemeleri', 'magaza_adi' => 'Beşel Yapı Malzemeleri', 'sehir' => 'KOCAELİ', 'ilce' => 'Başiskele'],
            ['bayi_adi' => 'Beyaz 33 Seramik', 'magaza_adi' => 'Beyaz 33 Seramik', 'sehir' => 'MERSİN', 'ilce' => 'Yenişehir'],
            ['bayi_adi' => 'Beyazsaray İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'KONYA', 'ilce' => 'Karatay'],
            ['bayi_adi' => 'Beyazsaray İnşaat', 'magaza_adi' => 'Merkez Showroom', 'sehir' => 'KONYA', 'ilce' => 'Selçuklu'],
            ['bayi_adi' => 'Biliciler Ticaret', 'magaza_adi' => 'Biliciler Ticaret', 'sehir' => 'ANKARA', 'ilce' => 'Polatlı'],
            ['bayi_adi' => 'BKA Yapı Tasarım', 'magaza_adi' => 'Edirne Mağaza', 'sehir' => 'EDİRNE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'BKA Yapı Tasarım', 'magaza_adi' => 'Tekirdağ Mağaza', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Çorlu'],
            ['bayi_adi' => 'Bmy Yapı', 'magaza_adi' => 'Bmy Yapı', 'sehir' => 'OSMANİYE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Bursa Showroom', 'magaza_adi' => 'Bursa Showroom', 'sehir' => 'BURSA', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Çaba Konut Yapı', 'magaza_adi' => 'Çaba Konut Yapı', 'sehir' => 'İZMİR', 'ilce' => 'Buca'],
            ['bayi_adi' => 'Çağdaş Yapı Malz.', 'magaza_adi' => 'Çağdaş Yapı Malz.', 'sehir' => 'İSTANBUL', 'ilce' => 'Eyüp'],
            ['bayi_adi' => 'Çalıklar İnşaat', 'magaza_adi' => 'Kemerburgaz Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Kemerburgaz'],
            ['bayi_adi' => 'Çalıklar İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Kağıthane'],
            ['bayi_adi' => 'Çalışkanlar İnşaat', 'magaza_adi' => 'Çalışkanlar İnşaat', 'sehir' => 'KOCAELİ', 'ilce' => 'Gebze'],
            ['bayi_adi' => 'Çamoluk Yapı', 'magaza_adi' => 'Çamoluk Yapı Malzemeleri', 'sehir' => 'İSTANBUL', 'ilce' => 'Üsküdar'],
            ['bayi_adi' => 'Çizgi Mimarlık Dekorasyon', 'magaza_adi' => 'Çizgi Mimarlık Dekorasyon', 'sehir' => 'MARDİN', 'ilce' => 'Artuklu'],
            ['bayi_adi' => 'Çolakoğlu Fatih Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Bahçelievler'],
            ['bayi_adi' => 'Çolakoğlu Fatih Yapı', 'magaza_adi' => 'Avcılar Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Avcılar'],
            ['bayi_adi' => 'Decoprime', 'magaza_adi' => 'Decoprime', 'sehir' => 'ANKARA', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'Dekoyap', 'magaza_adi' => 'Kayseri Şube', 'sehir' => 'KAYSERİ', 'ilce' => 'Kocasinan'],
            ['bayi_adi' => 'Dekoyap', 'magaza_adi' => 'Malatya Şube', 'sehir' => 'MALATYA', 'ilce' => 'Yeşilyurt'],
            ['bayi_adi' => 'Demaş Yapı', 'magaza_adi' => 'Demaş Yapı', 'sehir' => 'ANTALYA', 'ilce' => 'Demre'],
            ['bayi_adi' => 'Demir Ticaret', 'magaza_adi' => 'Demir Ticaret', 'sehir' => 'KÜTAHYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Demirhanlar Seramik', 'magaza_adi' => 'Kocaeli Şube', 'sehir' => 'KOCAELİ', 'ilce' => 'Gebze'],
            ['bayi_adi' => 'Demirhanlar Seramik', 'magaza_adi' => 'İstanbul Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Ataşehir'],
            ['bayi_adi' => 'Des Seramik Yapı', 'magaza_adi' => 'Des Seramik Yapı Malzemeleri', 'sehir' => 'AMASYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'DG Seramik İnşaat', 'magaza_adi' => 'DG Seramik İnşaat', 'sehir' => 'İZMİR', 'ilce' => 'Urla'],
            ['bayi_adi' => 'Doktor Deco Design', 'magaza_adi' => 'Doktor Deco Design', 'sehir' => 'İZMİR', 'ilce' => 'Güzelbahçe'],
            ['bayi_adi' => 'Dönmez Yapı Grubu', 'magaza_adi' => 'Dönmez Yapı Grubu', 'sehir' => 'ADANA', 'ilce' => 'Seyhan'],
            ['bayi_adi' => 'Duranlar Yapı', 'magaza_adi' => 'Duranlar Yapı', 'sehir' => 'ÇORUM', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Ekol Yapı Malzemeleri', 'magaza_adi' => 'Ekol Yapı Malzemeleri', 'sehir' => 'İSTANBUL', 'ilce' => 'Maltepe'],
            ['bayi_adi' => 'Enver Mete İnşaat', 'magaza_adi' => 'Enver Mete İnşaat', 'sehir' => 'İZMİR', 'ilce' => 'Aliağa'],
            ['bayi_adi' => 'Eralp İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'DENİZLİ', 'ilce' => 'Pamukkale'],
            ['bayi_adi' => 'Eralp İnşaat', 'magaza_adi' => 'Showroom Mağaza 1', 'sehir' => 'DENİZLİ', 'ilce' => 'Merkezefendi'],
            ['bayi_adi' => 'Eralp İnşaat', 'magaza_adi' => 'Showroom Mağaza 2', 'sehir' => 'DENİZLİ', 'ilce' => 'Merkezefendi'],
            ['bayi_adi' => 'Erdi Yapı', 'magaza_adi' => 'Bolu Şube', 'sehir' => 'BOLU', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Erdi Yapı', 'magaza_adi' => 'Düzce Şube', 'sehir' => 'DÜZCE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Erpek Mimarlık', 'magaza_adi' => 'Erpek Mimarlık', 'sehir' => 'MUĞLA', 'ilce' => 'Ortaca'],
            ['bayi_adi' => 'Estetik Yapı', 'magaza_adi' => 'Estetik Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Kartal'],
            ['bayi_adi' => 'ETD Yapı', 'magaza_adi' => 'ETD Yapı', 'sehir' => 'ADANA', 'ilce' => 'Seyhan'],
            ['bayi_adi' => 'Etiler Merkez Showroom', 'magaza_adi' => 'Etiler Merkez Showroom', 'sehir' => 'İSTANBUL', 'ilce' => 'Beşiktaş'],
            ['bayi_adi' => 'Evci Yapı', 'magaza_adi' => 'Evci Yapı', 'sehir' => 'YALOVA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Çanakkale Şube', 'sehir' => 'ÇANAKKALE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Eskişehir Şube', 'sehir' => 'ESKİŞEHİR', 'ilce' => 'Tepebaşı'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'BURSA', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Pendik Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Pendik'],
            ['bayi_adi' => 'Fethiye Yapı Malz.', 'magaza_adi' => 'Fethiye Yapı Malz.', 'sehir' => 'MUĞLA', 'ilce' => 'Fethiye'],
            ['bayi_adi' => 'Gelişim İnşaat', 'magaza_adi' => 'Gelişim İnşaat', 'sehir' => 'KAHRAMANMARAŞ', 'ilce' => 'Elbistan'],
            ['bayi_adi' => 'Gerber Yapı', 'magaza_adi' => 'Gerber Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Halkalı'],
            ['bayi_adi' => 'Göbekli Yapı', 'magaza_adi' => 'İzmir Şube', 'sehir' => 'İZMİR', 'ilce' => 'Gaziemir'],
            ['bayi_adi' => 'Granitaş İnşaat', 'magaza_adi' => 'Granitaş İnşaat', 'sehir' => 'ERZURUM', 'ilce' => 'Yakutiye'],
            ['bayi_adi' => 'Güler Seramik', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'HATAY', 'ilce' => 'Antakya'],
            ['bayi_adi' => 'Güler Seramik', 'magaza_adi' => 'İskenderun Şube', 'sehir' => 'HATAY', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Gümüş Yapı', 'magaza_adi' => 'Gümüş Yapı', 'sehir' => 'OSMANİYE', 'ilce' => 'Düziçi'],
            ['bayi_adi' => 'Güneşli Showroom', 'magaza_adi' => 'Güneşli Showroom', 'sehir' => 'İSTANBUL', 'ilce' => 'Bağcılar'],
            ['bayi_adi' => 'Gür-Av İnşaat Malz.', 'magaza_adi' => 'Gür-Av İnşaat Malz.', 'sehir' => 'MERSİN', 'ilce' => 'Silifke'],
            ['bayi_adi' => 'Gürdemir İnşaat', 'magaza_adi' => 'Gürdemir İnşaat', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Marmaraereğlisi'],
            ['bayi_adi' => 'Hak Seramik', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'HATAY', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Hak Seramik', 'magaza_adi' => 'İskenderun Şube', 'sehir' => 'HATAY', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Halil Sepet Ticaret', 'magaza_adi' => 'Halil Sepet Ticaret', 'sehir' => 'KÜTAHYA', 'ilce' => 'Tavşanlı'],
            ['bayi_adi' => 'Hancıoğlu Mühendislik', 'magaza_adi' => 'Hancıoğlu Mühendislik', 'sehir' => 'TRABZON', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Hedef Yapı', 'magaza_adi' => 'Hedef Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Sultanbeyli'],
            ['bayi_adi' => 'Hitit Yapı', 'magaza_adi' => 'Hitit Yapı', 'sehir' => 'ANTALYA', 'ilce' => 'Kaş'],
            ['bayi_adi' => 'Hüseyin Kadayıfoğlu İnşaat', 'magaza_adi' => 'Hüseyin Kadayıfoğlu İnşaat', 'sehir' => 'HATAY', 'ilce' => 'Dörtyol'],
            ['bayi_adi' => 'İda Güven Yapı', 'magaza_adi' => 'İda Güven Yapı', 'sehir' => 'AKSARAY', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'İtimat Yapı', 'magaza_adi' => 'İtimat Yapı', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Süleymanpaşa'],
            ['bayi_adi' => 'İzmir Showroom', 'magaza_adi' => 'İzmir Showroom', 'sehir' => 'İZMİR', 'ilce' => 'Bayraklı'],
            ['bayi_adi' => 'Kaçkarlar İnşaat', 'magaza_adi' => 'Kaçkarlar İnşaat', 'sehir' => 'RİZE', 'ilce' => 'Ardeşen'],
            ['bayi_adi' => 'Kadıoğlu Yapı Elemanları', 'magaza_adi' => 'Kadıoğlu Yapı Elemanları', 'sehir' => 'OSMANİYE', 'ilce' => 'Kadirli'],
            ['bayi_adi' => 'Kahvecioğlu Seramik', 'magaza_adi' => 'Kahvecioğlu Seramik', 'sehir' => 'BURSA', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Kalfa İnşaat', 'magaza_adi' => 'Kalfa İnşaat', 'sehir' => 'ANTALYA', 'ilce' => 'Kepez'],
            ['bayi_adi' => 'Kalfalar Yapı', 'magaza_adi' => 'Kalfalar Yapı', 'sehir' => 'TRABZON', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Kayanar İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'MUĞLA', 'ilce' => 'Marmaris'],
            ['bayi_adi' => 'Kayanar İnşaat', 'magaza_adi' => 'Datça Şube', 'sehir' => 'MUĞLA', 'ilce' => 'Datça'],
            ['bayi_adi' => 'Kayanar İnşaat', 'magaza_adi' => 'Muğla Şube', 'sehir' => 'MUĞLA', 'ilce' => 'Menteşe'],
            ['bayi_adi' => 'Kepez Yapı Market', 'magaza_adi' => 'Kepez Yapı Market', 'sehir' => 'ANKARA', 'ilce' => 'Etimesgut'],
            ['bayi_adi' => 'Keskin Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Bakırköy'],
            ['bayi_adi' => 'Keskin Yapı', 'magaza_adi' => 'Avcılar Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Avcılar'],
            ['bayi_adi' => 'Kmz Korkmaz İnşaat', 'magaza_adi' => 'Kmz Korkmaz İnşaat', 'sehir' => 'SAKARYA', 'ilce' => 'Adapazari'],
            ['bayi_adi' => 'Komutlar İnşaat', 'magaza_adi' => 'Komutlar İnşaat', 'sehir' => 'TRABZON', 'ilce' => 'Vakfıkebir'],
            ['bayi_adi' => 'Kule Seramik', 'magaza_adi' => 'Kule Seramik', 'sehir' => 'ANTALYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Lazulit Yapı', 'magaza_adi' => 'Lazulit Yapı', 'sehir' => 'KOCAELİ', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Lider Özen İnşaat', 'magaza_adi' => 'Lider Özen İnşaat', 'sehir' => 'MALATYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Makuloğlu Ticaret', 'magaza_adi' => 'Eynesil Şube', 'sehir' => 'GİRESUN', 'ilce' => 'Eynesil'],
            ['bayi_adi' => 'Makuloğlu Ticaret', 'magaza_adi' => 'Görele Şube', 'sehir' => 'GİRESUN', 'ilce' => 'Görele'],
            ['bayi_adi' => 'Mehmet Ceylan Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İZMİR', 'ilce' => 'Konak'],
            ['bayi_adi' => 'Mehmet Ceylan Yapı', 'magaza_adi' => 'Çamdibi Şube', 'sehir' => 'İZMİR', 'ilce' => 'Bornova'],
            ['bayi_adi' => 'Mehmet Ceylan Yapı', 'magaza_adi' => 'Karşıyaka Şube', 'sehir' => 'İZMİR', 'ilce' => 'Karşıyaka'],
            ['bayi_adi' => 'Mencan İnşaat', 'magaza_adi' => 'Mencan İnşaat', 'sehir' => 'İZMİR', 'ilce' => 'Karşıyaka'],
            ['bayi_adi' => 'Merkez Showroom', 'magaza_adi' => 'Merkez Showroom', 'sehir' => 'KÜTAHYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Mert Ticaret', 'magaza_adi' => 'Mert Ticaret', 'sehir' => 'MANİSA', 'ilce' => 'Yunusemre'],
            ['bayi_adi' => 'Mete Yapı', 'magaza_adi' => 'Mete Yapı', 'sehir' => 'İZMİR', 'ilce' => 'Yenişehir'],
            ['bayi_adi' => 'Motif Yapı', 'magaza_adi' => 'Motif Yapı', 'sehir' => 'BURSA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Muhittin Demirli Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Pendik'],
            ['bayi_adi' => 'Muhittin Demirli Yapı', 'magaza_adi' => 'Kurtköy Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Pendik'],
            ['bayi_adi' => 'Muzaffer Seramik', 'magaza_adi' => 'Muzaffer Seramik', 'sehir' => 'BURSA', 'ilce' => 'İnegöl'],
            ['bayi_adi' => 'Naki Demir İnşaat', 'magaza_adi' => 'Naki Demir İnşaat', 'sehir' => 'ANKARA', 'ilce' => 'Yenimahalle'],
            ['bayi_adi' => 'NBA Cihangir', 'magaza_adi' => 'Çukurambar Şube', 'sehir' => 'ANKARA', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'NBA Cihangir', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'ANKARA', 'ilce' => 'Keçiören'],
            ['bayi_adi' => 'Neyzen Yapı', 'magaza_adi' => 'Neyzen Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Esenyurt'],
            ['bayi_adi' => 'Nova Seramik', 'magaza_adi' => 'Nova Seramik', 'sehir' => 'BURSA', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Nuryapı', 'magaza_adi' => 'Nuryapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Kadıköy'],
            ['bayi_adi' => 'Oktaylar İnşaat', 'magaza_adi' => 'Oktaylar İnşaat', 'sehir' => 'RİZE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Opal Yapı', 'magaza_adi' => 'Alanya Şube', 'sehir' => 'ANTALYA', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Opal Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'ANTALYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Öncüler Yapı', 'magaza_adi' => 'Öncüler Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Beykoz'],
            ['bayi_adi' => 'Öz Turanlar İnşaat', 'magaza_adi' => 'Öz Turanlar İnşaat', 'sehir' => 'BATMAN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Özbekler Yapı Malz.', 'magaza_adi' => 'Özbekler Yapı Malz.', 'sehir' => 'BAYBURT', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Özdemirler İnşaat', 'magaza_adi' => 'Özdemirler İnşaat', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Özşah Yapı Mağaza', 'magaza_adi' => 'Edirne Şube', 'sehir' => 'EDİRNE', 'ilce' => 'Keşan'],
            ['bayi_adi' => 'Özşah Yapı Malz.', 'magaza_adi' => 'İstanbul Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Silivri'],
            ['bayi_adi' => 'Özyıldırım Boya Seramik', 'magaza_adi' => 'Özyıldırım Boya Seramik', 'sehir' => 'HATAY', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Pınar Yapı', 'magaza_adi' => 'Pınar Yapı', 'sehir' => 'UŞAK', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'PİA Yapı Center', 'magaza_adi' => 'PİA Yapı Center', 'sehir' => 'KARAMAN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Pol-Art İnşaat', 'magaza_adi' => 'Pol-Art İnşaat', 'sehir' => 'ANKARA', 'ilce' => 'Ulus'],
            ['bayi_adi' => 'Ramazan Kaya - Eylül Yapı', 'magaza_adi' => 'Ramazan Kaya - Eylül Yapı', 'sehir' => 'KİLİS', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Sakarya Dönmez Yapı', 'magaza_adi' => 'Sakarya Dönmez Yapı', 'sehir' => 'SAKARYA', 'ilce' => 'Adapazarı'],
            ['bayi_adi' => 'Samsun Showroom', 'magaza_adi' => 'Samsun Showroom', 'sehir' => 'SAMSUN', 'ilce' => 'Tekkeköy'],
            ['bayi_adi' => 'Saşa Yapı', 'magaza_adi' => 'Saşa Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Büyükçekmece'],
            ['bayi_adi' => 'Sema Yapı', 'magaza_adi' => 'Sema Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Büyükçekmece'],
            ['bayi_adi' => 'Sen Yapı', 'magaza_adi' => 'Sen Yapı', 'sehir' => 'ANKARA', 'ilce' => 'Ulus'],
            ['bayi_adi' => 'Seramik Yapı', 'magaza_adi' => 'Seramik Yapı', 'sehir' => 'İZMİR', 'ilce' => 'Narlıdere'],
            ['bayi_adi' => 'Seramikev', 'magaza_adi' => 'Seramikev (Decotive)', 'sehir' => 'İSTANBUL', 'ilce' => 'Beylikdüzü'],
            ['bayi_adi' => 'Seratime Yapı', 'magaza_adi' => 'Maltepe Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Maltepe'],
            ['bayi_adi' => 'Seratime Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Ümraniye'],
            ['bayi_adi' => 'Seratime Yapı', 'magaza_adi' => 'Zekeriyaköy Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Sarıyer'],
            ['bayi_adi' => 'Sevil Yapı Market', 'magaza_adi' => 'Sevil Yapı Market', 'sehir' => 'ANTALYA', 'ilce' => 'Kaş'],
            ['bayi_adi' => 'Seycan Seramik', 'magaza_adi' => 'Seycan Seramik', 'sehir' => 'İSTANBUL', 'ilce' => 'Zeytinburnu'],
            ['bayi_adi' => 'Söylemez İnşaat', 'magaza_adi' => 'Söylemez İnşaat', 'sehir' => 'MUŞ', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Step Yapı', 'magaza_adi' => 'Step Yapı', 'sehir' => 'SAMSUN', 'ilce' => 'Atakum'],
            ['bayi_adi' => 'Şahinser Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'ÇORUM', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Şahinser Yapı', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'ÇORUM', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Şentürk İnşaat', 'magaza_adi' => 'Şentürk İnşaat', 'sehir' => 'KARS', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Talay Yapı', 'magaza_adi' => 'Talay Yapı', 'sehir' => 'BURDUR', 'ilce' => 'Bucak'],
            ['bayi_adi' => 'Tarsu Seramik', 'magaza_adi' => 'Tarsu Seramik', 'sehir' => 'MERSİN', 'ilce' => 'Tarsus'],
            ['bayi_adi' => 'Taşkent Yapı', 'magaza_adi' => 'Taşkent Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Ümraniye'],
            ['bayi_adi' => 'Tatarlı İnşaat', 'magaza_adi' => 'Tatarlı İnşaat', 'sehir' => 'İSTANBUL', 'ilce' => 'Gaziosmanpaşa'],
            ['bayi_adi' => 'Tulum Yapı', 'magaza_adi' => 'Tulum Yapı', 'sehir' => 'ISPARTA', 'ilce' => 'Şarkikaraağaç'],
            ['bayi_adi' => 'Tunalar Seramik', 'magaza_adi' => 'Tunalar Seramik', 'sehir' => 'KONYA', 'ilce' => 'Ereğli'],
            ['bayi_adi' => 'Tuncaylar Yapı Market', 'magaza_adi' => 'Tuncaylar Yapı Market', 'sehir' => 'ERZİNCAN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Turanlar Yapı', 'magaza_adi' => 'Turanlar Yapı', 'sehir' => 'KOCAELİ', 'ilce' => 'İzmit'],
            ['bayi_adi' => 'Turkuaz Royal İnşaat', 'magaza_adi' => 'Turkuaz Royal İnşaat', 'sehir' => 'MANİSA', 'ilce' => 'Akhisar'],
            ['bayi_adi' => 'Türkmenler Yapı', 'magaza_adi' => 'Güngören Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Güngören'],
            ['bayi_adi' => 'Türkmenler Yapı', 'magaza_adi' => 'Zeytinburnu Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Zeytinburnu'],
            ['bayi_adi' => 'Uğur Yapı', 'magaza_adi' => 'Uğur Yapı', 'sehir' => 'BURDUR', 'ilce' => 'Gölhisar'],
            ['bayi_adi' => 'Uludağ Yapı Market', 'magaza_adi' => 'Uludağ Yapı Market', 'sehir' => 'TOKAT', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Uludoğanlar İnşaat', 'magaza_adi' => 'Uludoğanlar İnşaat', 'sehir' => 'ANKARA', 'ilce' => 'Altındağ'],
            ['bayi_adi' => 'Uslu Ticaret', 'magaza_adi' => 'Uslu Ticaret', 'sehir' => 'KARABÜK', 'ilce' => 'Safranbolu'],
            ['bayi_adi' => 'Uyumazlar Yapı', 'magaza_adi' => 'Güneşli Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Bağcılar'],
            ['bayi_adi' => 'Uyumazlar Yapı', 'magaza_adi' => 'Şişli Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Varay İnşaat', 'magaza_adi' => 'Varay İnşaat', 'sehir' => 'BİNGÖL', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yasin Yapı Malzemeleri', 'magaza_adi' => 'Yasin Yapı Malzemeleri', 'sehir' => 'İSTANBUL', 'ilce' => 'Bahçelievler'],
            ['bayi_adi' => 'Yaşar Ticaret', 'magaza_adi' => 'Yaşar Ticaret', 'sehir' => 'BİTLİS', 'ilce' => 'Tatvan'],
            ['bayi_adi' => 'Yazar Kollektif', 'magaza_adi' => 'Yazar Kollektif', 'sehir' => 'MUĞLA', 'ilce' => 'Milas'],
            ['bayi_adi' => 'Yener Seramik', 'magaza_adi' => 'Yener Seramik', 'sehir' => 'ORDU', 'ilce' => 'Gülyalı'],
            ['bayi_adi' => 'Yeşilyurt Yapı', 'magaza_adi' => 'Yeşilyurt Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Ümraniye'],
            ['bayi_adi' => 'Yıldız Yapı', 'magaza_adi' => 'Yıldız Yapı', 'sehir' => 'İSTANBUL', 'ilce' => 'Arnavutköy'],
            ['bayi_adi' => 'Yılmazlar Banyo', 'magaza_adi' => 'Caddebostan Showroom', 'sehir' => 'İSTANBUL', 'ilce' => 'Kadıköy'],
            ['bayi_adi' => 'Yılmazlar Banyo', 'magaza_adi' => 'Güneşli Showroom', 'sehir' => 'İSTANBUL', 'ilce' => 'Güneşli'],
            ['bayi_adi' => 'Yılmazlar Banyo', 'magaza_adi' => 'Mecidiyeköy Showroom', 'sehir' => 'İSTANBUL', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Yılmazlar Yapı', 'magaza_adi' => 'Yılmazlar Yapı Malzemeleri', 'sehir' => 'KASTAMONU', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yurdagül Boya', 'magaza_adi' => 'Yurdagül Boya', 'sehir' => 'KÜTAHYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yücesoy Seramik', 'magaza_adi' => 'Gaziantep Şube', 'sehir' => 'GAZİANTEP', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yücesoy Seramik', 'magaza_adi' => 'Mersin Şube', 'sehir' => 'MERSİN', 'ilce' => 'Mezitli'],
            ['bayi_adi' => 'Zafer İnşaat', 'magaza_adi' => 'Zafer İnşaat', 'sehir' => 'VAN', 'ilce' => 'Merkez'],
        ];

        $bayiSayisi = 0;
        $magazaSayisi = 0;
        $hatalar = [];

        foreach ($data as $index => $row) {
            try {
                $satir = $index + 2; // CSV header'ı için +2
                
                // Şehir bulma
                $sehir = Sehir::where('ad', 'LIKE', '%' . trim($row['sehir']) . '%')->first();
                if (!$sehir) {
                    $hatalar[] = "Satır {$satir}: Şehir bulunamadı: " . $row['sehir'];
                    continue;
                }

                // İlçe bulma veya oluşturma
                $ilce = Ilce::where('sehir_id', $sehir->id)
                            ->where('ad', 'LIKE', '%' . trim($row['ilce']) . '%')
                            ->first();
                
                if (!$ilce) {
                    $ilce = Ilce::create([
                        'sehir_id' => $sehir->id,
                        'ad' => trim($row['ilce'])
                    ]);
                }

                // Bayi bulma veya oluşturma
                $bayi = Bayi::where('ad', trim($row['bayi_adi']))->first();
                if (!$bayi) {
                    $bayi = Bayi::create([
                        'ad' => trim($row['bayi_adi']),
                        'sehir_id' => $sehir->id,
                        'ilce_id' => $ilce->id,
                        'aktif' => true
                    ]);
                    $bayiSayisi++;
                    Log::info('Yeni bayi oluşturuldu: ' . $bayi->ad);
                }

                // Mağaza oluşturma
                $magaza = BayiMagazasi::where('bayi_id', $bayi->id)
                                     ->where('ad', trim($row['magaza_adi']))
                                     ->first();
                
                if (!$magaza) {
                    BayiMagazasi::create([
                        'bayi_id' => $bayi->id,
                        'ad' => trim($row['magaza_adi']),
                        'sehir_id' => $sehir->id,
                        'ilce_id' => $ilce->id,
                        'aktif' => true
                    ]);
                    $magazaSayisi++;
                    Log::info('Yeni mağaza oluşturuldu: ' . trim($row['magaza_adi']) . ' - ' . $bayi->ad);
                }

            } catch (\Exception $e) {
                $hatalar[] = "Satır {$satir}: " . $e->getMessage();
                Log::error("BayiMagazaSeeder hatası - Satır {$satir}: " . $e->getMessage());
            }
        }

        // Sonuç raporu
        $toplamBayi = Bayi::count();
        $toplamMagaza = BayiMagazasi::count();
        
        echo "=== SEEDER TAMAMLANDI ===\n";
        echo "Oluşturulan bayi sayısı: {$bayiSayisi}\n";
        echo "Oluşturulan mağaza sayısı: {$magazaSayisi}\n";
        echo "Toplam bayi sayısı: {$toplamBayi}\n";
        echo "Toplam mağaza sayısı: {$toplamMagaza}\n";
        
        if (!empty($hatalar)) {
            echo "HATALAR (" . count($hatalar) . " adet):\n";
            foreach (array_slice($hatalar, 0, 10) as $hata) {
                echo "  - {$hata}\n";
            }
            if (count($hatalar) > 10) {
                echo "  ... ve " . (count($hatalar) - 10) . " hata daha\n";
            }
        }

        Log::info('BayiMagazaSeeder tamamlandı. Bayiler: ' . $toplamBayi . ', Mağazalar: ' . $toplamMagaza);
    }
}