<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check-In Document</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .header {
            display: flex;
            justify-content: space-between;
        }
        .logo {
            width: 100px; /* Adjust as needed */
        }
        .company-info, .transaction-info {
            font-size: 0.9em;
        }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        .details {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        .flex-table {
            margin-top : 15px;
            width: 100%; /* Ensures the table stretches to full width */
            table-layout: fixed; /* Ensures even distribution of column widths */
        }

        .flex-table td.text-center {
            text-align: center;
            width: 33.33%; /* Divides the table into three equal columns */
        }

        .flex-table, .flex-table tr, .flex-table td {
            border: none; /* Removes all borders */
        }

    </style>
</head>
<body>
<div class="header">
    <div class="company-info">
        <p><strong>Furnizor:</strong> Probitas</p>
        <p><strong>Telefon:</strong> 0744138843</p>
        <p><strong>Codul Fiscal:</strong> 4753829</p>
        <p><strong>Numar Registru:</strong> 4753829</p>
        <p><strong>IBAN:</strong> 4753829</p>
        <p><strong>Punct de lucru:</strong> Jud. Vaslui, Loc. Vaslui</p>
    </div>
    <div class="transaction-info">
        <p><strong>Referinta Tranzactie:</strong> 2004</p>
    </div>
</div>

<h1 class="title">Proces verbal de Primire-Predare in vederea depozitarii anvelope extra-sezon</h1>

<div class="details">
    <p>Subsemnatul <strong>{{$tyre->client->name}}</strong>, posesor al autovehiculului cu numarul de inmatriculare {{$tyre->car_number}}, am predat la <strong>{{auth()->user()->name}}</strong> reprezentant al SC.PROBITAS.SRL :</p>
    <p>Bucati Anvelope : {{$tyre->quantity}}</p>
    <p>Marca : {{$tyre->model}}</p>
    <p>Dimensiune : {{$tyre->size}} </p>
    <p>Observatii : {{$tyre->observations}}</p>
    <br>
    <p>
        [GDPR*] Prin semnarea acestui document, clientul isi expirma acordul pentru procesarea datelor personale care vor fi folosite in procesul de trasabilitate a serviciilor oferite de SC.PROBITAS.SRL.
    </p>
</div>

<table class="flex-table" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="text-center">Data :
            <br>..........<br>
            {{ now()->format('d-m-Y H:i') }}
        </td>
        <td class="text-center">
            Am predat
            <br>..........<br>
            {{ $tyre->client->name }}
        </td>
        <td class="text-center">
            Am primit
            <br>..........<br>
            {{ auth()->user()->name }}
        </td>
    </tr>
</table>

</body>
</html>
