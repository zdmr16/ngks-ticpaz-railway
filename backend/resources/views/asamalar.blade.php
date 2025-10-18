<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Talep Türleri ve Aşamalar - Railway Database</title>
</head>
<body>
    <h1>Talep Türleri ve Aşamalar Tablosu</h1>
    
    @php
        $toplamSatir = 0;
        foreach($talepTurleri as $talepTuru) {
            $toplamSatir += count($talepTuru->asamalar);
        }
    @endphp
    
    <p>Toplam Satır Sayısı: {{ $toplamSatir }}</p>
    
    <table border="1">
        <thead>
            <tr>
                <th>Talep Türü ID</th>
                <th>Talep Türü Adı</th>
                <th>Aşama ID</th>
                <th>Aşama Adı</th>
            </tr>
        </thead>
        <tbody>
            @foreach($talepTurleri as $talepTuru)
                @foreach($talepTuru->asamalar as $asama)
                <tr>
                    <td>{{ $talepTuru->id }}</td>
                    <td>{{ $talepTuru->ad }}</td>
                    <td>{{ $asama->id }}</td>
                    <td>{{ $asama->ad }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>