<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auszahlungsbeleg</title>
    <style>
        /* Farben von MiniFinds */
        :root {
            --green-color: #65765f; /* Beispiel: Grün */
            --beige-color: #e5d4bc; /* Beispiel: Beige */
            --accent-color: #2C3E50; /* Beispiel: Dunkelblau */
            --background-color: #f8f2e9; /* Beispiel: Hellgrau */
            --border-color: #f8f2e9; /* Beispiel: Hellgrau */
            --text-color: #333; 
            --footer-bg-color: #f8f2e9; /* Beispiel: Hellgrau */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            line-height: 1;
            background:#fff;
            background-color:#fff;
        }

        header {
            background: var(--background-color);
            padding: 20px;
            text-align: left;
            color: var(--text-color);;
            border-bottom: 2px solid var(--green-color);
            margin-bottom:10px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .invoice-header .logo {
            width: 150px;
        }

        .content {
            padding: 20px;
        }

        .top-details-table {
            width: 100%;
            border-collapse: collapse;
           padding:0;
           font-size:0.9em;
           margin-top:20px;
        }
        .top-details-table tr:nth-child(even) {
            background-color: #f8f8f8; /* Heller Grauton */
        }

        .top-details-table th, .top-details-table td {
            border: 0px var(--green-color);
            padding: 2px;
            text-align: left;
        }
        .top-details-table td {
            text-align: right;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .details-table th, .details-table td {
            border: 1px var(--green-color);
            padding: 10px;
            text-align: left;
        }

        .details-table th {
            background: var(--green-color);
            color: var(--accent-color);
        }

        footer {
            background: var(--background-color);
            padding: 20px;
            font-size: 12px;
            color: var(--accent-color);
            border-top: 2px solid var(--green-color);
            margin-top:50px;
        }

        .impressum {
            margin-top: 20px;
            font-size: 12px;
            color: var(--accent-color);
        }

        h1, h2 {
            color: var(--green-color);
        }

        .invoice-header img {
            width: 150px;
        }

        .content p {
            color: var(--text-color);
        }

        .content strong {
            color: var(--green-color);
        }
        .header-logo{
            float:left;
            width:120px;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <p style="padding:5px; padding-top:0px; margin-bottom:120px;" >
                <span style="">
                <img class="header-logo" src="https://www.minifinds.de/site-images/minifinds_logo.png" alt="Minifinds Logo">
                </span>
            
            </p>
        </div>
        <div style="clear: both;"></div>
    </header>
    <div style="float: left; width: 60%; padding: 0;">
            <p>Kundennummer: {{ $payout->customer->user->id }} </p>
            <p>
                {{ $payout->customer->first_name }} {{ $payout->customer->last_name }}<br>
                {{ $payout->customer->street }}<br>
                {{ $payout->customer->postal_code }} {{ $payout->customer->city }}<br>
                {{ $payout->customer->state }}<br>
                {{ $payout->email }}
            </p>
    </div>
 
    <div style="clear: both;"></div>
    <div class="header">
        <h2>Auszahlungsbeleg</h2>
    </div>
    <p><span class="bold">Betrag:</span> {{ number_format($payout->amount, 2, ',', '.') }} €</p>
    <p><span class="bold">Auszahlung angefordert am:</span> {{ $payout->created_at->format('d.m.Y H:i') }}</p>
    <p class="bold">Auszahlungsmethode:</p>
    @if(isset($payout->payout_details['paypal_email']))
        <p>PayPal: {{ $payout->payout_details['paypal_email'] }}</p>
    @elseif(isset($payout->payout_details['iban']))
        <p>IBAN: {{ $payout->payout_details['iban'] }}</p>
        <p>BIC: {{ $payout->payout_details['bic'] }}</p>
    @else
        <p>Keine Auszahlungsdetails verfügbar.</p>
    @endif
    <div style="margin-top: 30px;">
        <p style="font-weight: bold;">{{ $payout->shelfRental->sales->count() }} Verkaufte Produkte:</p>
        @if ($payout->shelfRental && $payout->shelfRental->sales->count() > 0)
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f2f2f2; border-bottom: 1px solid #ddd;">
                        <th style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;">Produkt-Nr</th>
                        <th style="padding: 5px; text-align: left; border-bottom: 1px solid #ddd;">Produktname</th>
                        <th style="padding: 5px; text-align: right; border-bottom: 1px solid #ddd;">Preis</th>
                        <th style="padding: 5px; text-align: right; border-bottom: 1px solid #ddd;">Einkünfte (nach Provision)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payout->shelfRental->sales as $sale)
                        <tr>
                            <td style="padding: 5px; border-bottom: 1px solid #ddd;">{{ $sale->product->id }}</td>
                            <td style="padding: 5px; border-bottom: 1px solid #ddd;">{{ $sale->product->name }}</td>
                            <td style="padding: 5px; text-align: right; border-bottom: 1px solid #ddd;">{{ number_format($sale->sale_price, 2, ',', '.') }} €</td>
                            <td style="padding: 5px; text-align: right; border-bottom: 1px solid #ddd;">{{ number_format($sale->net_sale_price, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f2f2f2; font-weight: bold;">
                        <td colspan="3" style="padding: 5px; text-align: right; border-top: 1px solid #ddd;">Gesamtsumme der Auszahlung:</td>
                        <td style="padding: 5px; text-align: right; border-top: 1px solid #ddd;">
                            {{ number_format($payout->amount, 2, ',', '.') }} €
                        </td>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>Keine Verkäufe gefunden.</p>
        @endif
    </div>

    
    <footer>
        <p>Vielen Dank für deine Buchung bei MiniFinds!</p>
        <div class="impressum">
            <strong>MiniFinds GbR</strong><br>
            Christin Dudek & Joana Avanyoh<br>
            Schwarzbuchenweg 49, 22391 Hamburg<br>
            E-Mail: info@minifinds.de<br>
        </div>
    </footer>
</body>
</html>
