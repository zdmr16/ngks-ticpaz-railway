<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bayiler Tablosu - Railway Database</title>
</head>
<body>
    <h1>Bayiler Tablosu</h1>
    
    <p>Toplam Bayi Sayısı: {{ count($bayiler) }}</p>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Sahip Adı</th>
                <th>Sahip Telefon</th>
                <th>Sahip Email</th>
                <th>Şehir ID</th>
                <th>İlçe ID</th>
                <th>Aktif</th>
                <th>Oluşturma Tarihi</th>
                <th>Güncelleme Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bayiler as $bayi)
            <tr>
                <td>{{ $bayi->id }}</td>
                <td>{{ $bayi->ad }}</td>
                <td>{{ $bayi->sahip_adi }}</td>
                <td>{{ $bayi->sahip_telefon }}</td>
                <td>{{ $bayi->sahip_email }}</td>
                <td>{{ $bayi->sehir_id }}</td>
                <td>{{ $bayi->ilce_id }}</td>
                <td>{{ $bayi->aktif ? 'Evet' : 'Hayır' }}</td>
                <td>{{ $bayi->created_at }}</td>
                <td>{{ $bayi->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>