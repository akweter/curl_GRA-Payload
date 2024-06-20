<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit GRA Payload</title>
</head>

<body>
    <h2>Send Invoice</h2>
    <form action="" method="post">
        <table>
            <tr>
                <td><label for="invoice_number">Invoice Number:</label></td>
                <td><label for="userName">User Name:</label></td>
            </tr>
            <tr>
                <td><input type="text" id="invoice_number" name="invoice_number" required></td>
                <td><input type="text" id="invoice_number" name="userName" required></td>
                <td><input type="submit" value="Submit Invoice"></td>
            </tr>
        </table>
    </form>

    <?php
        $url = "https://vsdc.persolqa.com/vsdc/api/v1/taxpayer/C0003314324-004/invoice";
        $securityKey = "Du6oZ4A3F2sOlDiHIvWxSK0IiqQuxKkgfGeGwZxSj6fYR/Aku7SYobsPMYMJqJ9j";
        $headers = [
            "Content-Type: application/json",
            "security_key: " . $securityKey
        ];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $invoiceNumber = $_POST["invoice_number"];
            $userName = $_POST["userName"];
            $todayDate = date("Y-m-d");

            $data = [
                "currency" => "GHS",
                "exchangeRate" => 1,
                "invoiceNumber" => $invoiceNumber,
                "totalLevy" => 63.99,
                "userName" => $userName,
                "flag" => "INVOICE",
                "calculationType" => "INCLUSIVE",
                "totalVat" => 169.57,
                "transactionDate" => $todayDate,
                "totalAmount" => 1300,
                "voucherAmount" => "0.0000",
                "businessPartnerName" => "fred(cash customer)",
                "businessPartnerTin" => "C0000000000",
                "saleType" => "NORMAL",
                "discountType" => "GENERAL",
                "discountAmount" => "0.0000",
                "reference" => "",
                "groupReferenceId" => "",
                "purchaseOrderReference" => "",
                "items" => [
                    [
                        "itemCode" => "TXC00389165855",
                        "itemCategory" => "",
                        "expireDate" => "2024-03-01",
                        "description" => "1SFA611130R1101",
                        "quantity" => 10,
                        "levyAmountA" => 26.66,
                        "levyAmountB" => 26.66,
                        "levyAmountC" => 10.66,
                        "levyAmountD" => "",
                        "levyAmountE" => "",
                        "discountAmount" => 0,
                        "batchCode" => "",
                        "unitPrice" => 130
                    ]
                ]
            ];
            $jsonData = json_encode($data);


            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Execute cURL request
            $response = curl_exec($ch);
            if ($response === false) {
                echo "cURL Error: " . curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode >= 200 && $httpCode < 300) {
                    $formattedResponse = json_encode(json_decode($response), JSON_PRETTY_PRINT);
                    echo "
                        <table>
                            <tr>
                                <td><h2>Response</h2></td>
                            </tr>
                            <tr>
                                <td><pre>" . $formattedResponse . "</pre></td>
                            </tr>
                            <tr>
                                <td><h2>Payload</h2><button id='copyPayload'>Copy Payload</button></td>
                            </tr>
                            <tr>
                                <td>" . $jsonData . "</td>
                            </tr>
                        </table>
                    ";
                } else {
                    echo "Error: HTTP Status Code - " . $httpCode . ", Response: " . $response;
                }
            }
            curl_close($ch);
        }
    ?>
    <br>
    <hr>
    <br>
    <p />

    <h2>TIN Validator</h2>
    <form action="" method="get">
        TIN: <input type="text" name="tin" required>
        <button type="submit">Validate</button>
    </form>

    <?php
        if (isset($_GET['tin'])) {
            $tin = $_GET['tin'];
            $endpoint = "https://vsdc.persolqa.com/vsdc/api/v1/taxpayer/C0003314324-004/identification/tin/$tin";
            $securityKey = "Du6oZ4A3F2sOlDiHIvWxSK0IiqQuxKkgfGeGwZxSj6fYR/Aku7SYobsPMYMJqJ9j";
            $headers = [
                "Content-Type: application/json",
                "security_key: " . $securityKey
            ];
            $context = stream_context_create([
                'http' => [
                    'header' => implode("\r\n", $headers)
                ]
            ]);
            $response = file_get_contents($endpoint, false, $context);
            if ($response !== false) {
                echo "<h3>Success!</h3>";
                echo "<big>" . htmlspecialchars($response) . "</big>";
            } else {
                echo "<h3>Error fetching data.</h3>";
            }
        }
    ?>

    <script>
        document.getElementById('copyPayload').addEventListener('click', function() {
            var payload = document.getElementById('payload');
            var range = document.createRange();
            range.selectNode(payload);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            alert('Payload copied to clipboard.');
        });
    </script>
</body>

</html>
