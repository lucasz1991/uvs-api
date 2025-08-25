<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labels - Regalvermietung</title>
    <style>

        @font-face {
            font-family: "Quicksand";
            src: url("../fonts/Quicksand-VariableFont_wght.ttf") format("truetype");
            font-style: normal;
        }
        /* Seite auf die Größe eines Etiketts einstellen */
        @page {
            size: 50.8mm 25.4mm; /* Maße des Etiketts */
            margin: 0;
        }
        html, body, *{
            background:#fff;
            background-color:#fff;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
        
        .label {
            height: 23mm; /* Maße des Etiketts */
            font-size: .7em;
            line-height: .3em;
            padding-left:5px;
            padding-right:5px;
            padding-top:5px;
            text-align:center;
            background:#fff;
            background-color:#fff;
        }
        
        .product-name {
            font-size: 1.1em;
            text-align:left;
            overflow-x:hidden;
            padding-right:15px;
            font-weight: bold;
            width: 170px; 
            line-height: .7em;  
            display: block;
        }
        
        .price {
            float: left; /* Preis nach links floaten */
            margin: 0; /* Entfernt den Standardabstand */
            margin-right: 10px; /* Abstand zum nächsten Element */
            font-size: 1.1em;
            text-align:left;

        }

        .size {
            float: right; /* Preis nach links floaten */
            margin: 0; /* Entfernt den Standardabstand */
            font-size: 1em;
            text-align:right;
        }
        
        .shelve-info {
            position:relative;
            top:-9px;
            float: right;
            border: 0.3px solid #000;
            padding:5px;
            padding-bottom:3px;
            padding-top:9px;
            border-radius:10px;
            font-size: 1em;
            margin: 0; /* Entfernt den Standardabstand */
            margin-left:7px;
        }
        
        .barcode {
            text-align: center;
            font-size: .8em;
            line-height: .4em;
            padding-bottom:10px;
        }
        .barcode-font{
            position:absolute;
            bottom:5px;
            left:50%;
            right:50%;
        }
        .barcode-mini{
            background:#fff;
            background-color:#fff;
            padding-left: 4px;
            padding-right: 4px;
            padding-top: 4px;
            margin-bottom:10px;
            margin-left: auto;
            margin-right: auto;
            display:block;
            width:75px;
            line-height: .8em;
            position:absolute;
            bottom:-10px;
            left:25%;
            right:25%;
        }
        
        /* Clearfix für das Float-Layout */
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    @foreach($labels as $label)
        <?php
            $imageFileBase64= $label->generateBarcode($label->barcode);
        ?>
        <div class="label">
            <p class="product-name">
                {{ strlen($label->product_name) > 35 ? mb_substr($label->product_name, 0, 35) . '...' : $label->product_name }}
            </p>
            <div class="clearfix"></div>
            <!-- Preis und Regalnummer nebeneinander -->
            <div class="price-shelve-container" style="width:100%; ">
                <p class="price"><strong>{{ $label->price }} €</strong></p>
                
                <p class="shelve-info">{{ $label->shelve_floor_number }}</p>
                
                @if(!empty($label->product->size))
                    <p class="size">Gr.: {{ $label->product->size }}</p>
                @endif
                
            </div>
            <div class="clearfix"></div>
            <!-- Barcode -->
            <div class="barcode" style="margin-top:20px;padding-left:2px;">
                <span class="barcode-font">
                    <img width="180px" height="" id="barcodeimg" class="barcodeimg" src="{{ $imageFileBase64 }}" alt="Barcode">
                </span><br>
            </div>
            <div class="barcode-mini" >{{ $label->barcode }}</div>

            <!-- Clearfix anwenden, damit nachfolgende Elemente korrekt angeordnet sind -->
            <div class="clearfix"></div>
        </div>
    @endforeach
</body>
</html>
