<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
margin:0;
padding:0;
}

.credencial{
width:54mm;
height:85.6mm;
position:relative;
background-image:url("{{ public_path('image/credencialA.png') }}");
background-size:cover;
background-position:center;
}

/* TITULO */

.titulo{
position:absolute;
top:20mm;
width:100%;
text-align:center;
font-size:12px;
font-weight:bold;
}

/* QR */

.qr{
position:absolute;
top:40mm;
left:17mm;
}

</style>

</head>

<body>

<div class="credencial">

<div class="titulo">
Secretaria de Organizacion y<br>
Vinculacion Sindical
</div>

<div class="qr">
<img src="{{ $qr }}">
</div>

</div>

</body>
</html>