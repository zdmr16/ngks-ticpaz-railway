<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bölgeler Tablosu - Railway Database</title>
</head>
<body>
    <h1>Bölgeler Tablosu</h1>
    
    <p>Toplam Bölge Sayısı: {{ count($bolgeler) }}</p>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bölge Adı</th>
                <th>Oluşturma Tarihi</th>
                <th>Güncelleme Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bolgeler as $bolge)
            <tr>
                <td>{{ $bolge->id }}</td>
                <td>{{ $bolge->ad }}</td>
                <td>{{ $bolge->created_at }}</td>
                <td>{{ $bolge->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>