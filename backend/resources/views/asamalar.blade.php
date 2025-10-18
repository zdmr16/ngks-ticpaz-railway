<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Talep Türleri ve Aşamalar - Railway Database</title>
</head>
<body>
    <h1>Talep Türleri ve Aşamalar İlişki Tablosu</h1>
    
    @php
        $toplamSatir = 0;
        $tipA = 0;
        $tipB = 0;
        $tipC = 0;
        foreach($talepTurleri as $talepTuru) {
            $asamaSayisi = count($talepTuru->asamalar);
            $toplamSatir += $asamaSayisi;
            
            if($talepTuru->is_akisi_tipi == 'tip_a') {
                $tipA += $asamaSayisi;
            } elseif($talepTuru->is_akisi_tipi == 'tip_b') {
                $tipB += $asamaSayisi;
            } elseif($talepTuru->is_akisi_tipi == 'tip_c') {
                $tipC += $asamaSayisi;
            }
        }
    @endphp
    
    <div style="margin-bottom: 20px;">
        <p><strong>Database İstatistikleri:</strong></p>
        <ul>
            <li>Toplam Talep Türü: {{ $talepTurleri->count() }} adet</li>
            <li>Toplam İlişki Kaydı: {{ $toplamSatir }} adet</li>
            <li>TIP_A İlişkileri: {{ $tipA }} adet</li>
            <li>TIP_B İlişkileri: {{ $tipB }} adet</li>
            <li>TIP_C İlişkileri: {{ $tipC }} adet</li>
        </ul>
    </div>
    
    <table border="1" cellpadding="5" cellspacing="0">
        <thead style="background-color: #f0f0f0;">
            <tr>
                <th>Talep Türü ID</th>
                <th>Talep Türü Adı</th>
                <th>İş Akışı Tipi</th>
                <th>Aşama ID</th>
                <th>Aşama Adı</th>
                <th>Aşama Sırası</th>
            </tr>
        </thead>
        <tbody>
            @foreach($talepTurleri as $talepTuru)
                @foreach($talepTuru->asamalar as $asama)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $talepTuru->id }}</td>
                    <td>{{ $talepTuru->ad }}</td>
                    <td style="text-align: center;
                        background-color: {{ $talepTuru->is_akisi_tipi == 'tip_a' ? '#e8f5e8' :
                                             ($talepTuru->is_akisi_tipi == 'tip_b' ? '#e8f0ff' : '#fff0e8') }};">
                        {{ strtoupper($talepTuru->is_akisi_tipi) }}
                    </td>
                    <td style="text-align: center; font-weight: bold;">{{ $asama->id }}</td>
                    <td>{{ $asama->ad }}</td>
                    <td style="text-align: center;">{{ $asama->sira }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        <h2>Veri Kaynağı Bilgisi</h2>
        <p>Bu veriler <strong>/load-talep-data</strong> endpoint'i ile database'e INSERT edilmiş verilerdir.</p>
        <p>Talep türleri ve aşamalar arasındaki ilişki <strong>is_akisi_tipi</strong> alanı üzerinden kurulmuştur.</p>
        
        <h3>İş Akışı Tipleri:</h3>
        <ul>
            <li><strong style="color: #2d5a2d;">TIP_A:</strong> Kayar Pano, Dijital Baskı, Dış Dijital Baskı, Tabela, Totem ({{ $tipA/5 }} aşama/tür)</li>
            <li><strong style="color: #2d4a7a;">TIP_B:</strong> Teşhir Yenileme, Mağaza Projelendirme ({{ $tipB/2 }} aşama/tür)</li>
            <li><strong style="color: #7a4a2d;">TIP_C:</strong> Teşhir İade ({{ $tipC/1 }} aşama/tür)</li>
        </ul>
    </div>
</body>
</html>