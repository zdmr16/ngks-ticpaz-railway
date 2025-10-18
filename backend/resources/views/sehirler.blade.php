<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Şehirler Tablosu - Railway Database</title>
</head>
<body>
    <h1>Şehirler Tablosu</h1>
    
    <p>Toplam Şehir Sayısı: {{ count($sehirler) }}</p>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Şehir Adı</th>
                <th>Bölge ID</th>
                <th>Bölge Adı</th>
                <th>Oluşturma Tarihi</th>
                <th>Güncelleme Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sehirler as $sehir)
            <tr>
                <td>{{ $sehir->id }}</td>
                <td>{{ $sehir->ad }}</td>
                <td>{{ $sehir->bolge_id }}</td>
                <td>{{ $sehir->bolge ? $sehir->bolge->ad : 'Belirtilmemiş' }}</td>
                <td>{{ $sehir->created_at }}</td>
                <td>{{ $sehir->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>