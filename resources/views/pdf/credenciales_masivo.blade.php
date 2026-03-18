<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>

body{
margin:0;
padding:0;
}

/* HOJA A4 */

.page{
width:210mm;
height:297mm;
display:grid;

/* 2 columnas */
grid-template-columns:repeat(2,1fr);

/* 5 filas */
grid-template-rows:repeat(5,1fr);

gap:5mm;
padding:10mm;
box-sizing:border-box;
}

/* TARJETA PVC */
.credencial{

width:85.6mm;
height:54mm;

position:relative;

}

.fondo{

position:absolute;
top:0;
left:0;

width:100%;
height:100%;

}



/* LOGO */

.logo{
position:absolute;
top:3mm;
left:50%;
transform:translateX(-50%);
height:10mm;
}

/* TITULO EVENTO */

.titulo{
position:absolute;
top:15mm;
width:100%;
text-align:center;
font-size:10px;
font-weight:bold;
}

/* QR */

.qr{
position:absolute;
top:25mm;
left:50%;
transform:translateX(-50%);
}

/* DATOS */

.datos{
position:absolute;
bottom:5mm;
width:100%;
text-align:center;
font-size:8px;
}

</style>






<div class="page">

@foreach($personas as $p)

<div class="credencial">
<img src="{{ public_path('image/credencialM.png') }}" class="fondo">

<img src="{{ public_path('image/logoF.png') }}" class="logo">

<div class="titulo">
{{ $evento }}
</div>

<div class="qr">
<img src="{{ $p->qr }}" width="80">
</div>

<div class="datos">

<b>{{ $p->nombre }} {{ $p->apellido }}</b><br>
CI: {{ $p->ci }}<br>
U.E.: {{ $p->ue }}<br>
Distrito: {{ $p->distrito }}

</div>

</div>

@endforeach

</div>
