<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura Electrónica {{env()}}</title>
    <style>
        /* Encabezado */
        #header-987abc {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        #logo-123def {
            color: red;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 5px;
        }

        #company-info-456ghi {
            font-size: 9px;
            line-height: 1.3;
            max-width: 500px;
        }

        #factura-box-789jkl {
            background: #f8f8f8;
            padding: 15px;
            text-align: center;
            min-width: 200px;
        }

        /* Información del cliente */
        #client-container-112mno {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        #client-info-223pqr {
            flex: 2;
            padding: 15px;
            background: #f8f8f8;
        }

        #fecha-info-334stu {
            flex: 1;
            padding: 15px;
            background: #f8f8f8;
        }

        /* Tabla de productos */
        #table-products-445vwx {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }

        #th-header-556yz {
            background: #f8f8f8;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        #td-data-667abc {
            padding: 8px;
            border: 1px solid #ddd;
        }

        /* Totales */
        #total-container-778def {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        #totals-889ghi {
            width: 200px;
        }

        #totals-889ghi table {
            margin-bottom: 0;
        }

        #totals-889ghi td {
            padding: 5px 8px;
        }

        #totals-889ghi #td-last-990jkl {
            text-align: right;
            width: 100px;
        }

        /* Texto en letras */
        #letras-112mno {
            background: #f8f8f8;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Footer */
        #footer-qr-223pqr {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        #qr-code-334stu {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
        }

        #footer-text-445vwx {
            font-size: 9px;
            margin-top: 20px;
            line-height: 1.3;
        }

        #text-bold-556yz {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="header-987abc">
        <div>
            <div id="logo-123def">COTELPERU</div>
            <div id="company-info-456ghi">
                COMPAÑÍA DE SERVICIOS Y TELECOMUNICACIONES DEL PERU S.A.C<br>
                AV. LA CULTURA NRO. 1977<br>
                SAN SEBASTIÁN - CUSCO - CUSCO<br>
                Celular facturación: 965763556 - Celular Cobranzas: 944268243 - Soporte técnico: 941408885<br>
                Email: facturacion@cotelperu.com
            </div>
        </div>
        <div id="factura-box-789jkl">
            <div id="text-bold-556yz">RUC 20603900855</div>
            <div id="text-bold-556yz">FACTURA</div>
            <div id="text-bold-556yz">ELECTRÓNICA</div>
            <div id="text-bold-556yz">FFF1-000134</div>
        </div>
    </div>

    <div id="client-container-112mno">
        <div id="client-info-223pqr">
            <div id="text-bold-556yz">DATOS DEL CLIENTE</div>
            <div>RUC: 30771</div>
            <div>DENOMINACIÓN: NUBEFACT SA</div>
            <div>DIRECCIÓN: CALLE LIBERTAD 116 MIRAFLORES - LIMA - PERU</div>
        </div>
        <div id="fecha-info-334stu">
            <div>FECHA EMISIÓN: 12/05/2020</div>
            <div>FECHA DE VENC.: 12/05/2020</div>
            <div>MONEDA: SOLES</div>
        </div>
    </div>

    <table id="table-products-445vwx">
        <thead>
            <tr>
                <th id="th-header-556yz">CANT.</th>
                <th id="th-header-556yz">UM</th>
                <th id="th-header-556yz">CÓD.</th>
                <th id="th-header-556yz">DESCRIPCIÓN</th>
                <th id="th-header-556yz">V/U</th>
                <th id="th-header-556yz">P/U</th>
                <th id="th-header-556yz">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="td-data-667abc">1</td>
                <td id="td-data-667abc">NIU</td>
                <td id="td-data-667abc">001</td>
                <td id="td-data-667abc">DETALLE DEL PRODUCTO</td>
                <td id="td-data-667abc">590.000</td>
                <td id="td-data-667abc">590.000</td>
                <td id="td-data-667abc">590.00</td>
            </tr>
            <tr>
                <td id="td-data-667abc">5</td>
                <td id="td-data-667abc">ZZ</td>
                <td id="td-data-667abc">001</td>
                <td id="td-data-667abc">DETALLE DEL SERVICIO</td>
                <td id="td-data-667abc">20.000</td>
                <td id="td-data-667abc">23.600</td>
                <td id="td-data-667abc">118.00</td>
            </tr>
        </tbody>
    </table>

    <div id="total-container-778def">
        <div id="totals-889ghi">
            <table>
                <tr>
                    <td>GRAVADA</td>
                    <td>S/ 600.00</td>
                </tr>
                <tr>
                    <td>IGV 18.00 %</td>
                    <td>S/ 108.00</td>
                </tr>
                <tr>
                    <td id="text-bold-556yz">TOTAL</td>
                    <td id="td-last-990jkl">S/ 708.00</td>
                </tr>
            </table>
        </div>
    </div>

    <div id="letras-112mno">
        IMPORTE EN LETRAS: SETECIENTOS OCHO CON 00/100 SOLES
    </div>

    <div id="footer-qr-223pqr">
        <div id="footer-text-445vwx">
            GUÍA DE REMISIÓN REMITENTE: ( 23 )<br>
            Representación impresa de la FACTURA ELECTRÓNICA, visita www.nubefact.com/20603900855<br>
            Autorizado mediante Resolución de Intendencia No.034-005-0005315<br>
            Resumen: b+VNv+nEl9ywGPdMExFSEdpHlKzJHDZosj/3ewZnp0=
        </div>
        <div id="qr-code-334stu">
            <img src="/api/placeholder/100/100" alt="Código QR">
        </div>
    </div>
</body>

</html>