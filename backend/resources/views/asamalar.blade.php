<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aşamalar Tablosu - Railway Database</title>
</head>
<body>
    <h1>Aşamalar Tablosu</h1>
    
    <p>Toplam Aşama Sayısı: {{ count($asamalar) }}</p>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>İş Akışı Tipi</th>
                <th>Aşama Adı</th>
                <th>Sıra</th>
                <th>Oluşturma Tarihi</th>
                <th>Güncelleme Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asamalar as $asama)
            <tr>
                <td>{{ $asama->id }}</td>
                <td>{{ $asama->is_akisi_tipi }}</td>
                <td>{{ $asama->ad }}</td>
                <td>{{ $asama->sira }}</td>
                <td>{{ $asama->created_at }}</td>
                <td>{{ $asama->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>