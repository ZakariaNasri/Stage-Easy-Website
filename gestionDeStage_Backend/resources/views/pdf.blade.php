<!DOCTYPE html>
<html>

<head>
    <style>
        @media print {
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
            .token{
                font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            }
            .attestation {
                max-width: none;
                width: 100%;
                height: 100%;
                border: none;
                page-break-after: always;
                box-sizing: border-box;
                padding: 0 40px 40px 40px !important;
                transform: rotate(-90deg);
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .attestation {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 40px 40px 40px !important;
            border: 2px solid #000;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        td.info {
            padding: 10px;
        }

        td.info span {
            font-weight: bold;
        }

        .date {
            text-align: right;
        }

        .date label {
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
        }

        .signature .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="attestation">
        <p class="token" style="font-size: 10px; color:#0000005e;
        font-weight: bold; text-align: center; margin-bottom: 40px;">
            {{$token}}</p>
        <div class="title">Attestation de Stage</div>
        <table>
            <tr>
                <td class="info">
                    Je, soussigné(e)
                    <span>{{$nom_Resp}}</span>, responsable de stage de <span>{{$theme}}</span>.
                </td>
            </tr>
            <tr>
                <td class="info">
                    atteste que l'étudiant(e)
                    <span>{{$nom}} {{$prenom}}</span>, né(e) le <span>{{$dateNaiss}}</span> à <span>{{$lieuNaiss}}</span>
                </td>
            </tr>
            <tr>
                <td class="info">
                    inscrit(e) à <span>l’universite 2 DE CONSTANTINE</span>,
                    a effectué un stage de formation dans
                    <span>{{$diplome}} / {{$specialite}}</span>
                </td>
            </tr>
            <tr>
                <td class="info">
                    à <span>{{$nom_Entreprise}}</span>
                </td>
            </tr>
            <tr>
                <td class="info">
                    Durant la période du
                    <span>{{$dateDeb}}</span> au <span>{{$dateFin}}</span>
                </td>
            </tr>
        </table>
        <div class="date">
            <label>Fait à <span>{{$addr_Entreprise}}</span>,</label>
            <span>le <span>{{$date}}</span></span>
        </div>
        <div class="signature">
            <div class="title">Le Représentant de l'universite 2 de Constantine</div>
            <div class="title">Le Responsable de l'établissement</div>
        </div>
    </div>
</body>

</html>
