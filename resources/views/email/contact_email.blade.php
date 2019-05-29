<style>
    body{
        text-align: center;
        padding: 20px;
    }
</style>
<body>
    <div class="container">
        <h2>Wiadomość kontaktowa</h2>
        <p>Od: {{$email}}</p>
        <p>Imie i nazwisko: {{$name}}</p>
        <p>Telefon: <strong>{{$phone}}</strong></p>
    </div>
    <div style="margin-top: 30px">
        <p>{{$m}}</p>
    </div>
</body>