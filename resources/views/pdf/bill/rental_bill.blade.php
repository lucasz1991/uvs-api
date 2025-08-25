<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechnung - Regalvermietung</title>
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
            margin-top:150px;
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
    
    <div  class="">
        <div style="float: left; width: 60%; padding: 0;">
            <h3>Rechnung an:</h3>
            <p>
                {{ $user->customer->first_name }} {{ $user->customer->last_name }}<br>
                {{ $user->customer->street }}<br>
                {{ $user->customer->postal_code }} {{ $user->customer->city }}<br>
                {{ $user->customer->state }}<br>
                {{ $user->email }}
            </p>
        </div>
        <div style="float: right; width: 40%; text-align: right;">
            <!-- Rechnungsdaten -->
            <table class="top-details-table">
                <tr>
                    <th>Rechnungsdatum</th>
                    <td>{{ $shelfRental->created_at->format('d.m.Y') }}</td>
                </tr>
                <tr>
                    <th>Rechnungsnummer: </th>
                    <td>{{ $invoice->id}}</td>
                </tr>
                <tr>
                    <th>Kundennummer:</th>
                    <td>{{ $shelfRental->customer->user->id }} </td>
                </tr>
                <tr>
                    <th>Zahlungsweise:</th>
                    <td>{{ $shelfRental->payment_method }}</td>
                </tr>
                <tr>
                    <th>Steuernummer:</th>
                    <td>50/644/01620</td>
                </tr>
            </table>   
        </div>
    </div>
    <div style="clear: both;"></div>
    <div>
        <h2>Rechnung</h2>
    </div>

    <div>
        <h3>Details zur Regalvermietung</h3>
        <table class="details-table">
            <tr>
                <th>Regal Nr.</th>
                <td>{{ $shelf->floor_number }}</td>
            </tr>
            <tr>
                <th>Standort</th>
                <td>{{ $location->name }}</td>
            </tr>
            <tr>
                <th>Zeitraum</th>
                <td>{{ $shelfRental->rental_start }} bis {{ $shelfRental->rental_end }}</td>
            </tr>
            <tr>
                <th>Gesamtpreis</th>
                <td>{{ number_format($shelfRental->total_price, 2, ',', '.') }} €</td>
            </tr>
        </table>
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
