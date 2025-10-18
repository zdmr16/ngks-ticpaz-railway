<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aşamalar Tablosu - Railway Database</title>
</head>
<body>
    <h1>Aşamalar Tablosu</h1>
    
    <p>Toplam Aşama Sayısı: <strong>{{ $asamalar->count() }}</strong> adet</p>
    
    <table border="1" cellpadding="5" cellspacing="0">
        <thead style="background-color: #f0f0f0;">
            <tr>
                <th>ID</th>
                <th>Aşama Adı</th>
                <th>İş Akışı Tipi</th>
                <th>Sıra</th>
                <th>Oluşturulma Tarihi</th>
                <th>Güncellenme Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asamalar as $asama)
            <tr>
                <td style="text-align: center; font-weight: bold;">{{ $asama->id }}</td>
                <td>{{ $asama->ad }}</td>
                <td style="text-align: center;
                    background-color: {{ $asama->is_akisi_tipi == 'tip_a' ? '#e8f5e8' :
                                         ($asama->is_akisi_tipi == 'tip_b' ? '#e8f0ff' : '#fff0e8') }};">
                    {{ strtoupper($asama->is_akisi_tipi) }}
                </td>
                <td style="text-align: center;">{{ $asama->sira }}</td>
                <td style="text-align: center;">{{ $asama->created_at ? $asama->created_at->format('d.m.Y H:i') : '-' }}</td>
                <td style="text-align: center;">{{ $asama->updated_at ? $asama->updated_at->format('d.m.Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        <h2>Database Tablosu Bilgisi</h2>
        <p>Bu sayfa <strong>asamalar</strong> tablosundaki verileri hiçbir ilişki kurmadan doğrudan göstermektedir.</p>
        <p>Veriler <strong>/load-talep-data</strong> endpoint'i ile INSERT edilmiştir.</p>
        
        <h3>Tablo Yapısı:</h3>
        <ul>
            <li><strong>ID:</strong> Birincil anahtar (1-23)</li>
            <li><strong>Aşama Adı:</strong> Aşamanın ismi</li>
            <li><strong>İş Akışı Tipi:</strong> tip_a, tip_b veya tip_c</li>
            <li><strong>Sıra:</strong> Aşamanın sıra numarası (0-8)</li>
            <li><strong>Oluşturulma/Güncellenme Tarihi:</strong> Timestamp alanları</li>
        </ul>
    </div>
</body>
</html>