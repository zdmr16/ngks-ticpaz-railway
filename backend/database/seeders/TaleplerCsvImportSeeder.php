<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Talep;
use App\Models\BolgeMimari;

class TaleplerCsvImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Önce talepler tablosunu temizle
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('talepler')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Talepler tablosu temizlendi.\n";

        // CSV verilerini array olarak tanımla
        $csvData = [
            [1,1,3,1,1,2,10,4,'kendi_magazasi','Afyonkarahisar Showroom','Slim görsel - yaz? ve logo','2025-10-07 00:00:00','2025-08-14 00:00:00'],
            [2,9,7,78,959,2,10,4,'kendi_magazasi','Aksaray Anadolu AŞ','Lightbox görsel','2025-05-20 00:00:00','2025-04-22 00:00:00'],
            [3,9,7,78,959,1,10,4,'kendi_magazasi','Aksaray Anadolu AŞ','BL Serisi','2025-04-22 00:00:00','2025-04-22 00:00:00'],
            [4,9,7,78,959,2,10,4,'kendi_magazasi','Aksaray Anadolu AŞ','','2025-05-20 00:00:00','2025-04-25 00:00:00'],
            [5,9,13,69,128,2,1,6,'kendi_magazasi','Ankara Showroom','lightbox','2025-04-25 00:00:00','2025-03-21 00:00:00'],
            [6,9,13,69,128,2,11,1,'kendi_magazasi','Ankara Showroom','ödül pano',null,'2025-06-02 00:00:00'],
            [7,6,14,58,960,2,10,4,'kendi_magazasi','Antalya Showroom','','2025-05-21 00:00:00','2025-04-25 00:00:00'],
            [8,6,14,58,960,2,10,4,'kendi_magazasi','Antalya Showroom','','2025-06-23 00:00:00','2025-06-23 00:00:00'],
            [9,6,14,58,960,2,11,1,'kendi_magazasi','Antalya Showroom','Ng Slim Görsel',null,'2025-09-10 00:00:00'],
            [10,9,17,75,961,1,11,1,'kendi_magazasi','Arslanlar Yapı Seramik','',null,'2025-09-11 00:00:00'],
            [11,7,22,63,1007,2,11,1,'kendi_magazasi','Aydın Seramik','Banko logo ve bayi adı',null,'2025-04-25 00:00:00'],
            [12,7,22,63,1007,4,11,1,'kendi_magazasi','Aydın Seramik','Tabela çalışması',null,'2025-04-25 00:00:00'],
            [13,6,23,60,964,2,10,4,'kendi_magazasi','Aymer Yapı','Kayar pano görseli','2025-08-14 00:00:00','2025-08-14 00:00:00'],
            [14,6,23,60,964,2,11,1,'kendi_magazasi','Aymer Yapı','Talisi talay yapı dış cephe cam görsel',null,'2025-04-25 00:00:00'],
            [15,4,27,44,965,4,11,3,'kendi_magazasi','Batman Güven Yapı','Tabela Sökümü','2025-04-24 00:00:00','2025-04-24 00:00:00'],
            [16,9,31,81,561,4,1,6,'kendi_magazasi','Beyazsaray İnşaat','Talisi Ak Group tabelası','2025-04-30 00:00:00','2025-04-22 00:00:00'],
            [17,9,31,81,561,1,11,1,'kendi_magazasi','Beyazsaray İnşaat','Talisi Merkez Seramik kayar pano',null,'2025-05-02 00:00:00'],
            [18,9,31,81,561,1,10,4,'kendi_magazasi','Beyazsaray İnşaat','','2025-05-12 00:00:00','2025-05-12 00:00:00'],
            [19,9,31,81,561,2,11,1,'kendi_magazasi','Beyazsaray İnşaat','Kayar pano görseli 125x240','2025-05-12 00:00:00','2025-05-12 00:00:00'],
            [20,9,31,81,561,1,10,4,'kendi_magazasi','Beyazsaray İnşaat','Busan Showroom 120x280','2025-09-30 00:00:00','2025-05-13 00:00:00'],
            [21,9,31,81,561,4,10,4,'kendi_magazasi','Beyazsaray İnşaat','Talisi Merkez Seramik tabela','2025-07-22 00:00:00','2025-07-08 00:00:00'],
            [22,9,31,81,561,1,10,4,'kendi_magazasi','Beyazsaray İnşaat','Talisi Ak Group','2025-08-06 00:00:00','2025-06-17 00:00:00'],
            [23,9,31,81,561,1,10,4,'kendi_magazasi','Beyazsaray İnşaat','BL Serisi ilave kanat','2025-09-11 00:00:00','2025-08-20 00:00:00'],
            [24,4,35,43,280,4,11,1,'kendi_magazasi','Bulutbey İnşaat','Cephe logo yap?m? ve totem kaset de?i?imi',null,'2025-08-21 00:00:00'],
            [25,7,37,66,90,4,11,3,'kendi_magazasi','Çaba Konut Yapı','Totem kasedinin kompozit ışık kutu harf olması','2025-07-17 00:00:00','2025-07-17 00:00:00'],
            [26,7,37,66,90,2,11,1,'kendi_magazasi','Çaba Konut Yapı','dış cephe cam görsel',null,'2025-10-07 00:00:00'],
            [27,1,39,4,968,2,11,1,'kendi_magazasi','Çalıklar İnşaat','dış cephe cam görsel',null,'2025-05-26 00:00:00'],
            [28,1,39,4,968,1,11,1,'kendi_magazasi','Çalıklar İnşaat','Depo Ma?aza BL serisi',null,'2025-09-16 00:00:00'],
            [29,4,43,48,969,4,1,6,'kendi_magazasi','Çizgi Mimarlık Dekorasyon','Talisi Anl İnşaat','2025-08-27 00:00:00','2025-07-11 00:00:00'],
            [30,9,45,69,128,1,11,1,'kendi_magazasi','Decoprime','',null,'2025-10-09 00:00:00'],
            [31,9,46,76,689,1,10,4,'kendi_magazasi','Dekoyap','Talisi Svs Mermer','2025-08-25 00:00:00','2025-06-18 00:00:00'],
            [32,9,46,76,689,4,11,1,'kendi_magazasi','Dekoyap','Talisi Svs Mermer Totem',null,'2025-07-22 00:00:00'],
            [33,9,46,76,689,4,10,4,'kendi_magazasi','Dekoyap','Talisi As Yap? dan sökülen tabelan?n bir k?sm?n?n Gül?ehir Gürbüzler Yap? tali bayiye uygulanmas?','2025-09-12 00:00:00','2025-09-12 00:00:00'],
            [34,9,47,69,1010,1,11,1,'kendi_magazasi','DMC','BL Serisi',null,'2025-09-04 00:00:00'],
            [35,9,47,69,1010,1,10,4,'kendi_magazasi','DMC','','2025-09-19 00:00:00','2025-09-19 00:00:00'],
            [36,9,47,69,1010,4,10,4,'kendi_magazasi','DMC','Cephe ?zgara , logo , görseller ve totem','2025-09-17 00:00:00','2025-09-17 00:00:00'],
            [37,9,47,69,1010,4,11,3,'kendi_magazasi','DMC','','2025-04-22 00:00:00','2025-04-22 00:00:00'],
            [38,9,47,69,1010,4,11,3,'kendi_magazasi','DMC','cephe üzerine bayi adı ve toteme ilave bayi adı kaset yapımı','2025-05-09 00:00:00','2025-05-09 00:00:00'],
            [39,9,47,69,1010,4,11,1,'kendi_magazasi','DMC','Şanlıurfa Showroom',null,'2025-06-25 00:00:00'],
            [40,9,47,69,1010,1,10,4,'kendi_magazasi','DMC','','2025-07-08 00:00:00','2025-07-08 00:00:00'],
            [41,9,47,69,1010,2,11,1,'kendi_magazasi','DMC','',null,'2025-09-15 00:00:00'],
            [42,9,47,69,1010,4,11,1,'kendi_magazasi','DMC','Cephe kompozit boyama ve Görsel değişimi',null,'2025-07-21 00:00:00'],
            [43,9,47,69,1010,1,11,1,'kendi_magazasi','DMC','BL Serisi',null,'2025-08-20 00:00:00'],
            [44,9,47,69,1010,4,11,1,'kendi_magazasi','DMC','Dmc talisi Ta? Yap? Ad?yaman',null,'2025-09-12 00:00:00'],
            [45,9,47,69,1010,1,10,4,'kendi_magazasi','DMC','','2025-09-19 00:00:00','2025-09-19 00:00:00'],
            [46,1,52,5,628,4,11,1,'kendi_magazasi','Demirhanlar Seramik','Tabela logo ar?zas?',null,'2025-08-22 00:00:00'],
            [47,1,52,5,628,4,11,1,'kendi_magazasi','Demirhanlar Seramik','',null,'2025-10-09 00:00:00'],
            [48,3,58,20,972,1,10,4,'kendi_magazasi','Duranlar Yapı','','2025-05-06 00:00:00','2025-04-22 00:00:00'],
            [49,1,64,4,25,1,10,4,'kendi_magazasi','Estetik Yapı','tali bayisi için, çek istendi','2025-04-25 00:00:00','2025-03-22 00:00:00'],
            [50,1,64,4,25,4,10,4,'kendi_magazasi','Estetik Yapı','tali bayisi için, üretimde','2025-04-25 00:00:00','2025-04-24 00:00:00'],
            [51,1,64,4,25,2,10,4,'kendi_magazasi','Estetik Yapı','Talisi Ercan Yapı logo ve ödül pano','2025-05-27 00:00:00','2025-05-12 00:00:00'],
            [52,5,65,52,57,2,10,4,'kendi_magazasi','ETD Yapı','NG VE NG KUTAHYA LOGO','2025-05-05 00:00:00','2025-04-30 00:00:00'],
            [53,5,65,52,57,2,11,1,'kendi_magazasi','ETD Yapı','Cephe giri? kap? üzeri görsel',null,'2025-09-02 00:00:00'],
            [54,1,66,4,10,2,10,4,'kendi_magazasi','Etiler Merkez Showroom','İstanbul İstinyede bulunan proje cephesi mesh görsel','2025-04-28 00:00:00','2025-04-28 00:00:00'],
            [55,2,68,6,978,1,11,3,'kendi_magazasi','Femoza Yapı','','2025-03-22 00:00:00','2025-04-25 00:00:00'],
            [56,2,68,6,978,4,11,2,'kendi_magazasi','Femoza Yapı','talisi için','2025-03-22 00:00:00','2025-03-22 00:00:00'],
            [57,2,68,6,978,4,10,4,'kendi_magazasi','Femoza Yapı','Talisi Kerem Yapı tabela','2025-04-30 00:00:00','2025-04-22 00:00:00'],
            [58,2,68,6,978,2,11,1,'kendi_magazasi','Femoza Yapı','Talisi Kerem Yapı cephe cam görsel',null,'2025-05-12 00:00:00'],
            [59,2,68,6,978,1,10,4,'kendi_magazasi','Femoza Yapı','Talisi Mekanl?lar ?n?aat','2025-09-01 00:00:00','2025-06-26 00:00:00'],
            [60,2,68,6,978,4,10,4,'kendi_magazasi','Femoza Yapı','Talisi Mekanl?lar ?n?aat','2025-08-14 00:00:00','2025-08-14 00:00:00'],
            [61,2,68,6,978,4,11,1,'kendi_magazasi','Femoza Yapı','Tabela Sökümü',null,'2025-06-26 00:00:00'],
            [62,2,68,6,978,4,10,4,'kendi_magazasi','Femoza Yapı','Tabela Söküm','2025-07-08 00:00:00','2025-07-08 00:00:00'],
            [63,7,69,61,668,1,10,4,'kendi_magazasi','Fethiye Yapı Malz.','BL Serisi','2025-10-03 00:00:00','2025-08-26 00:00:00'],
            [64,5,70,54,374,1,11,3,'kendi_magazasi','Gelişim İnşaat','','2025-06-23 00:00:00','2025-06-23 00:00:00'],
            [65,1,71,4,979,1,11,1,'kendi_magazasi','Gerber Yapı','tolgaya gönderildi, taç logo',null,'2025-04-24 00:00:00'],
            [66,7,72,63,151,4,10,4,'kendi_magazasi','Göbekli Yapı','totem kaset kompozit değişimi','2025-07-03 00:00:00','2025-05-07 00:00:00'],
            [67,2,73,7,193,4,10,4,'kendi_magazasi','Gökfa İnşaat','Seramik Yapı dan sökülen totem taşıması','2025-06-10 00:00:00','2025-04-25 00:00:00'],
            [68,5,75,56,480,1,11,3,'kendi_magazasi','Güler Seramik','','2025-06-12 00:00:00','2025-06-12 00:00:00'],
            [69,5,75,56,480,1,10,4,'kendi_magazasi','Güler Seramik','çek istendi','2025-08-29 00:00:00','2025-04-25 00:00:00'],
            [70,1,79,3,923,1,11,3,'kendi_magazasi','Gürdemir İnşaat','','2025-06-27 00:00:00','2025-06-26 00:00:00'],
            [71,1,79,3,923,2,11,1,'kendi_magazasi','Gürdemir İnşaat','',null,'2025-07-07 00:00:00'],
            [72,5,81,56,485,4,11,1,'kendi_magazasi','Hak Seramik','Özy?ld?r?mdan bulunan  totemin ta??nmas?',null,'2025-08-26 00:00:00'],
            [73,5,81,56,485,1,10,4,'kendi_magazasi','Hak Seramik','BL Serisi','2025-09-17 00:00:00','2025-09-17 00:00:00'],
            [74,7,85,63,158,4,11,3,'kendi_magazasi','Helvacı Yapı','','2025-04-22 00:00:00','2025-04-22 00:00:00'],
            [75,7,85,63,158,4,1,6,'kendi_magazasi','Helvacı Yapı','Kuşadası Tabela Söküm','2025-08-07 00:00:00','2025-08-07 00:00:00'],
            [76,7,85,63,158,1,10,4,'kendi_magazasi','Helvacı Yapı','Kayar pano , çift yönlü pano ve ayaklar?n bayiden al?nmas?','2025-09-18 00:00:00','2025-09-18 00:00:00'],
            [77,1,89,3,981,1,10,4,'kendi_magazasi','İtimat Yapı','Talisi Akan Yapı','2025-08-06 00:00:00','2025-07-08 00:00:00'],
            [78,1,89,3,981,4,1,6,'kendi_magazasi','İtimat Yapı','Talisi Novaser tabela','2025-08-27 00:00:00','2025-07-23 00:00:00'],
            [79,1,89,3,981,1,10,4,'kendi_magazasi','İtimat Yapı','Talisi Novaser','2025-08-06 00:00:00','2025-08-06 00:00:00'],
            [80,1,89,3,981,4,10,4,'kendi_magazasi','İtimat Yapı','Talisi Akan Yap?','2025-08-29 00:00:00','2025-08-29 00:00:00'],
            [81,7,90,66,86,2,10,4,'kendi_magazasi','İzmir Showroom','Ödül pano , logo , Slim uygulama','2025-09-30 00:00:00','2025-08-25 00:00:00'],
            [82,7,90,66,86,2,11,1,'kendi_magazasi','İzmir Showroom','',null,'2025-09-05 00:00:00'],
            [83,3,91,30,851,4,1,6,'kendi_magazasi','Kaçkarlar İnşaat','Talisi Değişim Banyo tabelası','2025-08-27 00:00:00','2025-04-22 00:00:00'],
            [84,3,91,30,851,1,10,4,'kendi_magazasi','Kaçkarlar İnşaat','Talisi Değişim Banyo kayar pano','2025-05-02 00:00:00','2025-05-02 00:00:00'],
            [85,3,91,30,851,1,10,4,'kendi_magazasi','Kaçkarlar İnşaat','Talisi Değişim Banyo kayar pano','2025-08-08 00:00:00','2025-08-08 00:00:00'],
            [86,3,91,30,851,1,10,4,'kendi_magazasi','Kaçkarlar İnşaat','BL Serisi','2025-09-26 00:00:00','2025-09-02 00:00:00'],
            [87,5,92,53,804,1,10,4,'kendi_magazasi','Kadıoğlu Yapı Elemanları','','2025-08-21 00:00:00','2025-08-12 00:00:00'],
            [88,1,100,4,7,1,10,4,'kendi_magazasi','Keskin Yapı','KAYAR PANO TAÇ LOGO','2025-05-06 00:00:00','2025-05-06 00:00:00'],
            [89,1,100,4,7,2,11,1,'kendi_magazasi','Keskin Yapı','Yüzey teknolojileri ve R de?er panolar?',null,'2025-09-01 00:00:00'],
            [90,1,100,4,7,2,10,4,'kendi_magazasi','Keskin Yapı','Bak?rköy sat?? masas? arkas? logo','2025-10-03 00:00:00','2025-09-04 00:00:00'],
            [91,1,101,4,4,1,11,1,'kendi_magazasi','Keskin Yapı - Avcılar','',null,'2025-04-25 00:00:00'],
            [92,1,101,4,4,4,11,1,'kendi_magazasi','Keskin Yapı - Avcılar','ü harfi yanmıyor',null,'2025-03-22 00:00:00'],
            [93,4,108,43,1011,4,10,4,'kendi_magazasi','Malçok İnşaat','Duvar ızgara tabela çalışması','2025-06-12 00:00:00','2025-05-26 00:00:00'],
            [94,2,109,7,1014,1,10,4,'kendi_magazasi','Mecidiyeli Ticaret','BL Serisi','2025-04-24 00:00:00','2025-04-22 00:00:00'],
            [95,7,110,66,103,1,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','BL Serisi','2025-05-15 00:00:00','2025-05-15 00:00:00'],
            [96,7,110,66,103,1,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','Halkapınar 163x323 pano','2025-05-15 00:00:00','2025-05-15 00:00:00'],
            [97,7,110,66,103,1,11,1,'kendi_magazasi','Mehmet Ceylan Yapı','',null,'2025-05-20 00:00:00'],
            [98,7,110,66,103,2,11,1,'kendi_magazasi','Mehmet Ceylan Yapı','iç mekan görsel',null,'2025-06-26 00:00:00'],
            [99,7,110,66,103,2,11,1,'kendi_magazasi','Mehmet Ceylan Yapı','Banko logo',null,'2025-07-01 00:00:00'],
            [100,7,110,66,103,2,11,3,'kendi_magazasi','Mehmet Ceylan Yapı','Banko logo','2025-07-02 00:00:00','2025-07-02 00:00:00'],
            [101,7,110,66,103,4,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','Talisi Onur Yıldız','2025-08-26 00:00:00','2025-07-11 00:00:00'],
            [102,7,110,66,103,4,11,1,'kendi_magazasi','Mehmet Ceylan Yapı','Talisi As Yapı',null,'2025-07-21 00:00:00'],
            [103,7,110,66,103,4,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','Talisi Kahramanlar Tabela','2025-10-15 00:00:00','2025-07-22 00:00:00'],
            [104,7,110,66,103,4,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','Karşıyaka Showroom','2025-07-25 00:00:00','2025-07-25 00:00:00'],
            [105,7,110,66,103,2,11,3,'kendi_magazasi','Mehmet Ceylan Yapı','Stone logo ve yüzey teknololjileri','2025-09-02 00:00:00','2025-08-25 00:00:00'],
            [106,7,110,66,103,4,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','Talisi Sahil Yap? tabela ve görseller','2025-10-09 00:00:00','2025-08-25 00:00:00'],
            [107,7,110,66,103,2,11,1,'kendi_magazasi','Mehmet Ceylan Yapı','Ahşap serilerin yazıları',null,'2025-10-07 00:00:00'],
            [108,7,110,66,103,2,10,4,'kendi_magazasi','Mehmet Ceylan Yapı','Branda bask?','2025-08-11 00:00:00','2025-08-08 00:00:00'],
            [109,2,114,7,200,1,11,2,'kendi_magazasi','Mertaş','','2025-04-22 00:00:00','2025-04-25 00:00:00'],
            [110,2,117,8,988,1,10,4,'kendi_magazasi','Motif Yapı','BL Serisi','2025-10-06 00:00:00','2025-08-28 00:00:00'],
            [111,9,121,69,128,1,10,4,'kendi_magazasi','NBA Cihangir','BL Serisi - Çek istendi','2025-04-28 00:00:00','2025-04-22 00:00:00'],
            [112,6,126,58,214,1,10,4,'kendi_magazasi','Opal Yapı','','2025-05-06 00:00:00','2025-04-28 00:00:00'],
            [113,2,127,17,1015,1,1,6,'kendi_magazasi','Osmanlar Yapı','Talisi Koçak Yapı','2025-08-27 00:00:00','2025-05-28 00:00:00'],
            [114,1,133,1,390,2,11,1,'kendi_magazasi','Özşah Yapı','120x280 görseli',null,'2025-05-02 00:00:00'],
            [115,1,133,1,390,4,11,1,'kendi_magazasi','Özşah Yapı','',null,'2025-09-02 00:00:00'],
            [116,1,133,1,390,1,11,1,'kendi_magazasi','Özşah Yapı','BL Serisi Silivri',null,'2025-09-03 00:00:00'],
            [117,5,134,56,485,4,11,1,'kendi_magazasi','Özyıldırım Boya Seramik','Tabela Sökümü',null,'2025-08-26 00:00:00'],
            [118,5,138,57,995,1,10,4,'kendi_magazasi','Ramazan Kaya - Eylül Yapı','BL Serisi','2025-10-03 00:00:00','2025-09-01 00:00:00'],
            [119,2,139,11,903,1,11,1,'kendi_magazasi','Sakarya Dönmez Yapı','Talisi Hakan Yap? BL serisi',null,'2025-08-15 00:00:00'],
            [120,2,139,11,903,4,10,4,'kendi_magazasi','Sakarya Dönmez Yapı','Talisi Hakan Yap? Yalova','2025-10-08 00:00:00','2025-09-03 00:00:00'],
            [121,9,144,69,994,2,11,1,'kendi_magazasi','Sen Yapı','Ng Stone Görsel','2025-04-22 00:00:00','2025-04-22 00:00:00'],
            [122,9,144,69,994,2,11,1,'kendi_magazasi','Sen Yapı','Ng Stone Görsel',null,'2025-08-04 00:00:00'],
            [123,1,147,4,27,4,11,1,'kendi_magazasi','Seratime','Tabela Cotto Lambiri',null,'2025-09-03 00:00:00'],
            [124,1,147,4,27,4,10,4,'kendi_magazasi','Seratime','Kartal şube','2025-08-19 00:00:00','2025-07-21 00:00:00'],
            [125,1,147,4,27,4,10,4,'kendi_magazasi','Seratime','Şile Showroom Tabela','2025-09-10 00:00:00','2025-07-21 00:00:00'],
            [126,1,147,4,27,4,10,4,'kendi_magazasi','Seratime','Talisi Forte Yap?','2025-08-19 00:00:00','2025-08-13 00:00:00'],
            [127,1,147,4,27,1,10,4,'kendi_magazasi','Seratime','Talisi Forte Yap? BL Serisi','2025-10-15 00:00:00','2025-09-30 00:00:00'],
            [128,1,147,4,27,2,11,1,'kendi_magazasi','Seratime','Talisi Forte Yap?',null,'2025-09-30 00:00:00'],
            [129,1,147,4,27,2,11,1,'kendi_magazasi','Seratime','',null,'2025-08-06 00:00:00'],
            [130,1,147,4,27,1,11,1,'kendi_magazasi','Seratime','BL Serisi',null,'2025-08-19 00:00:00'],
            [131,3,152,21,810,4,11,3,'kendi_magazasi','Step Yapı','','2025-04-10 00:00:00','2025-04-26 00:00:00'],
            [132,3,152,21,810,1,10,4,'kendi_magazasi','Step Yapı','','2025-05-28 00:00:00','2025-04-26 00:00:00'],
            [133,3,152,21,810,1,11,1,'kendi_magazasi','Step Yapı','',null,'2025-05-02 00:00:00'],
            [134,3,152,21,810,4,10,4,'kendi_magazasi','Step Yapı','Talisi Apamall tabela söküm','2025-08-26 00:00:00','2025-08-22 00:00:00'],
            [135,1,157,4,37,1,10,4,'kendi_magazasi','Taşkent Yapı','revize çizim yapıldı','2025-05-16 00:00:00','2025-04-16 00:00:00'],
            [136,1,157,4,37,2,10,4,'kendi_magazasi','Taşkent Yapı','30m x 3m duvar vinil','2025-09-17 00:00:00','2025-08-06 00:00:00'],
            [137,1,157,4,37,2,11,1,'kendi_magazasi','Taşkent Yapı','',null,'2025-09-02 00:00:00'],
            [138,1,158,4,21,1,11,3,'kendi_magazasi','Tatarlı İnşaat','Çek bekliyor','2025-04-28 00:00:00','2025-04-28 00:00:00'],
            [139,1,165,4,22,1,11,3,'kendi_magazasi','Türkmenler','','2025-03-22 00:00:00','2025-04-28 00:00:00'],
            [140,1,165,4,22,2,11,1,'kendi_magazasi','Türkmenler','Banko logo','2025-04-22 00:00:00','2025-04-22 00:00:00'],
            [141,1,165,4,22,2,10,4,'kendi_magazasi','Türkmenler','Performa görseli  , logo , yazılar','2025-08-11 00:00:00','2025-04-25 00:00:00'],
            [142,1,165,4,22,4,11,1,'kendi_magazasi','Türkmenler','Talisi Özcanlar Group tabela',null,'2025-05-06 00:00:00'],
            [143,1,165,4,22,1,10,4,'kendi_magazasi','Türkmenler','Talisi İmaj İstanbul','2025-09-25 00:00:00','2025-05-26 00:00:00'],
            [144,1,165,4,22,2,11,1,'kendi_magazasi','Türkmenler','Ceka Mimarlık cephe cam görsel',null,'2025-05-29 00:00:00'],
            [145,1,165,4,22,4,1,6,'kendi_magazasi','Türkmenler','İmaj İstanbul Tabela','2025-08-27 00:00:00','2025-05-29 00:00:00'],
            [146,1,165,4,22,4,10,4,'kendi_magazasi','Türkmenler','Rotavit Seramik tabela','2025-08-29 00:00:00','2025-05-29 00:00:00'],
            [147,1,165,4,22,1,11,1,'kendi_magazasi','Türkmenler','Talisi Özcanlar Group',null,'2025-05-30 00:00:00'],
            [148,1,165,4,22,1,11,3,'kendi_magazasi','Türkmenler','Talisi Dilek Ticaret','2025-06-11 00:00:00','2025-06-04 00:00:00'],
            [149,1,165,4,22,4,1,6,'kendi_magazasi','Türkmenler','Talisi Metro Seramik','2025-08-27 00:00:00','2025-06-12 00:00:00'],
            [150,1,165,4,22,2,11,1,'kendi_magazasi','Türkmenler','Avcılar paslanmaz logo',null,'2025-06-16 00:00:00'],
            [151,1,165,4,22,4,10,4,'kendi_magazasi','Türkmenler','Talisi Dilek Ticaret','2025-06-24 00:00:00','2025-06-24 00:00:00'],
            [152,1,165,4,22,2,11,1,'kendi_magazasi','Türkmenler','Avcılar isimlik',null,'2025-06-25 00:00:00'],
            [153,1,165,4,22,2,10,4,'kendi_magazasi','Türkmenler','Kayar Pano üzeri logo ve yazılar','2025-07-04 00:00:00','2025-06-27 00:00:00'],
            [154,1,165,4,22,4,11,1,'kendi_magazasi','Türkmenler','Talisi İstabanyo',null,'2025-07-17 00:00:00'],
            [155,1,165,4,22,2,10,4,'kendi_magazasi','Türkmenler','dış cephe cam görsel','2025-07-22 00:00:00','2025-07-22 00:00:00'],
            [156,1,165,4,22,2,10,4,'kendi_magazasi','Türkmenler','Güngören pileksi yaz?lar','2025-10-03 00:00:00','2025-08-20 00:00:00'],
            [157,1,165,4,22,2,11,1,'kendi_magazasi','Türkmenler','Talisi Özcanlar Group Ng Wood ve Ng Kütahya logo',null,'2025-08-26 00:00:00'],
            [158,1,165,4,22,2,10,4,'kendi_magazasi','Türkmenler','Talisi ?maj ?stanbul banko logo','2025-10-06 00:00:00','2025-08-26 00:00:00'],
            [159,1,165,4,22,1,11,1,'kendi_magazasi','Türkmenler','BL Serisi Baheçlievler',null,'2025-08-27 00:00:00'],
            [160,1,165,4,22,2,11,3,'kendi_magazasi','Türkmenler','Talisi Metro Seramik vitrin görselleri','2025-09-04 00:00:00','2025-09-01 00:00:00'],
            [161,1,165,4,22,1,11,1,'kendi_magazasi','Türkmenler','BL Serisi Güngören',null,'2025-09-03 00:00:00'],
            [162,2,169,16,448,4,11,1,'kendi_magazasi','Uslu Ticaret','',null,'2025-07-25 00:00:00'],
            [163,1,178,4,23,1,11,1,'kendi_magazasi','Yılmazlar Banyo','',null,'2025-07-09 00:00:00'],
            [164,1,178,4,23,1,10,4,'kendi_magazasi','Yılmazlar Banyo','BL Serisi','2025-08-06 00:00:00','2025-07-18 00:00:00'],
            [165,1,178,4,23,2,11,1,'kendi_magazasi','Yılmazlar Banyo','Talisi Özcanlar Group Ng Güneşi ve Logo',null,'2025-08-08 00:00:00'],
            [166,1,178,4,23,1,11,1,'kendi_magazasi','Yılmazlar Banyo','Talisi Seralife',null,'2025-08-11 00:00:00'],
            [167,1,178,4,23,1,10,4,'kendi_magazasi','Yılmazlar Banyo','Talisi Seralife BL Serisi','2025-09-16 00:00:00','2025-08-12 00:00:00'],
            [168,1,178,4,23,1,11,1,'kendi_magazasi','Yılmazlar Banyo','Talisi Likit Mimarlık',null,'2025-05-30 00:00:00'],
            [169,2,179,18,1002,2,11,1,'kendi_magazasi','Yılmazlar Yapı Malzemeleri','','2025-09-03 00:00:00','2025-07-25 00:00:00'],
            [170,2,179,18,1002,4,11,1,'kendi_magazasi','Yılmazlar Yapı Malzemeleri','Cephe Ng Slim kaplama',null,'2025-09-03 00:00:00'],
            [171,5,181,55,1003,1,11,3,'kendi_magazasi','Yücesoy Seramik','çek istendi','2025-04-16 00:00:00','2025-04-25 00:00:00'],
            [172,5,181,55,1003,2,10,4,'kendi_magazasi','Yücesoy Seramik','logo ve Atatürk imza-söz','2025-08-18 00:00:00','2025-08-18 00:00:00'],
            [173,4,184,49,1018,4,11,1,'kendi_magazasi','Alkanlar İnşaat','Talisi Geç ?n?aat tabela',null,'2025-09-03 00:00:00'],
            [174,2,185,8,242,2,1,6,'kendi_magazasi','Aşuroğlu İnşaat','iç mekan görsel ve logo','2025-04-22 00:00:00','2025-03-22 00:00:00'],
            [175,2,185,8,242,2,10,4,'kendi_magazasi','Aşuroğlu İnşaat','Performa görsel ve logo','2025-08-14 00:00:00','2025-07-02 00:00:00'],
            [176,9,186,75,961,1,10,4,'kendi_magazasi','Azizoğlu','','2025-10-15 00:00:00','2025-10-15 00:00:00'],
            [177,2,189,8,242,1,1,6,'kendi_magazasi','Biryılmaz Yapı','sipariş verildi','2025-04-25 00:00:00','2025-04-16 00:00:00'],
            [178,2,189,8,242,2,11,1,'kendi_magazasi','Biryılmaz Yapı','',null,'2025-05-02 00:00:00'],
            [179,2,189,8,242,4,10,4,'kendi_magazasi','Biryılmaz Yapı','Depo Totem Branda Değişimi','2025-09-11 00:00:00','2025-06-26 00:00:00'],
            [180,4,194,43,1011,2,11,1,'kendi_magazasi','Loft Design','Lightbox görsel','2025-09-02 00:00:00','2025-05-15 00:00:00'],
            [181,4,195,49,1018,4,11,2,'kendi_magazasi','Miray Yapı','tolga\'da','2025-03-22 00:00:00','2025-04-24 00:00:00'],
            [182,4,195,49,1018,1,11,1,'kendi_magazasi','Miray Yapı','BL Serisi',null,'2025-05-20 00:00:00'],
            [183,4,195,49,1018,2,11,1,'kendi_magazasi','Miray Yapı','Görsel',null,'2025-06-27 00:00:00'],
            [184,4,195,49,1018,2,10,4,'kendi_magazasi','Miray Yapı','','2025-08-14 00:00:00','2025-07-22 00:00:00'],
            [185,9,198,74,1022,1,11,3,'kendi_magazasi','Uçar Yapı','336.000','2025-09-30 00:00:00','2025-09-30 00:00:00']
        ];

        // Her bölge için ilk bölge mimarını bul
        $bolgeMimarlari = [];
        $bolgeler = [1,2,3,4,5,6,7,8,9];
        
        foreach($bolgeler as $bolgeId) {
            $bolgeMimari = BolgeMimari::whereHas('atamalari', function($query) use ($bolgeId) {
                $query->where('bolge_id', $bolgeId);
            })->first();
            
            if($bolgeMimari) {
                $bolgeMimarlari[$bolgeId] = $bolgeMimari->id;
            } else {
                // Eğer o bölge için mimar yoksa 1 numaralı mimarı default olarak ata
                $bolgeMimarlari[$bolgeId] = 1;
            }
        }

        // Önce problematik ID'leri tespit et ve düzelt
        $maxIlceId = DB::table('ilceler')->max('id');
        $maxBayiId = DB::table('bayiler')->max('id');
        $maxSehirId = DB::table('sehirler')->max('id');
        $maxTalepTuruId = DB::table('talep_turleri')->max('id');
        $maxAsamaId = DB::table('asamalar')->max('id');
        
        echo "Mevcut max ID'ler - İlçe: {$maxIlceId}, Bayi: {$maxBayiId}, Şehir: {$maxSehirId}, Talep Türü: {$maxTalepTuruId}, Aşama: {$maxAsamaId}\n";

        // Verileri insert et
        $insertedCount = 0;
        $skippedCount = 0;
        
        foreach($csvData as $index => $row) {
            // CSV format: [talep_id,bolge_id,bayi_id,sehir_id,ilce_id,talep_turu_id,guncel_asama_id,talep_durum,magaza_tipi,magaza_adi,aciklama,guncel_asama_tarihi,created_at]
            
            $bolgeId = $row[1];
            $bayiId = $row[2];
            $sehirId = $row[3];
            $ilceId = $row[4];
            $talepTuruId = $row[5];
            $asamaId = $row[6];
            
            // ID'leri kontrol et ve gerekirse düzelt
            if ($ilceId > $maxIlceId) {
                echo "Satır " . ($index + 1) . ": İlçe ID {$ilceId} mevcut değil, 1 ile değiştiriliyor\n";
                $ilceId = 1;
            }
            if ($bayiId > $maxBayiId) {
                echo "Satır " . ($index + 1) . ": Bayi ID {$bayiId} mevcut değil, 1 ile değiştiriliyor\n";
                $bayiId = 1;
            }
            if ($sehirId > $maxSehirId) {
                echo "Satır " . ($index + 1) . ": Şehir ID {$sehirId} mevcut değil, 1 ile değiştiriliyor\n";
                $sehirId = 1;
            }
            if ($talepTuruId > $maxTalepTuruId) {
                echo "Satır " . ($index + 1) . ": Talep Türü ID {$talepTuruId} mevcut değil, 1 ile değiştiriliyor\n";
                $talepTuruId = 1;
            }
            if ($asamaId > $maxAsamaId) {
                echo "Satır " . ($index + 1) . ": Aşama ID {$asamaId} mevcut değil, 1 ile değiştiriliyor\n";
                $asamaId = 1;
            }

            // Foreign key constraint'lerini kontrol et
            $ilceExists = DB::table('ilceler')->where('id', $ilceId)->exists();
            $bayiExists = DB::table('bayiler')->where('id', $bayiId)->exists();
            $sehirExists = DB::table('sehirler')->where('id', $sehirId)->exists();
            $talepTuruExists = DB::table('talep_turleri')->where('id', $talepTuruId)->exists();
            $asamaExists = DB::table('asamalar')->where('id', $asamaId)->exists();
            
            if (!$ilceExists || !$bayiExists || !$sehirExists || !$talepTuruExists || !$asamaExists) {
                echo "Satır " . ($index + 1) . ": Eksik foreign key referanslar, atlanıyor\n";
                $skippedCount++;
                continue;
            }
            
            $bolgeMimariId = $bolgeMimarlari[$bolgeId] ?? 1;
            
            try {
                DB::table('talepler')->insert([
                    'bolge_id' => $bolgeId,
                    'bolge_mimari_id' => $bolgeMimariId,
                    'bayi_id' => $bayiId,
                    'sehir_id' => $sehirId,
                    'ilce_id' => $ilceId,
                    'talep_turu_id' => $talepTuruId,
                    'guncel_asama_id' => $asamaId,
                    'magaza_tipi' => $row[8],
                    'magaza_adi' => $row[9],
                    'aciklama' => $row[10] ?: '', // Boş string olarak kaydet
                    'guncel_asama_tarihi' => $row[11] === 'NULL' || $row[11] === null ? now() : $row[11],
                    'guncel_asama_aciklamasi' => null,
                    'arsivlendi_mi' => false,
                    'arsivlenme_tarihi' => null,
                    'created_at' => $row[12],
                    'updated_at' => now()
                ]);
                
                $insertedCount++;
            } catch (\Exception $e) {
                echo "Satır " . ($index + 1) . " insert edilemedi: " . $e->getMessage() . "\n";
                $skippedCount++;
            }
        }

        echo "Toplam {$insertedCount} adet talep başarıyla aktarıldı.\n";
        echo "Atlanan kayıt sayısı: {$skippedCount}\n";
    }
}