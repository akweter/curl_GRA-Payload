<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit GRA Payload</title>
</head>
<body>
    <h2>Submit Invoice</h2>
    <form action="" method="post">
        <label for="invoice_number">Invoice Number:</label>
        <input type="text" id="invoice_number" name="invoice_number" required><br><br>
        <input type="submit" value="Submit">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $invoiceNumber = $_POST["invoice_number"];
    $todayDate = date("Y-m-d");

    $data = [
        "currency" => "GHS",
        "exchangeRate" => 1,
        "invoiceNumber" => $invoiceNumber,
        "totalLevy" => 63.99,
        "userName" => "Kofi Ghana",
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
    $url = "https://vsdcstaging.vat-gh.com/vsdc/api/v1/taxpayer/CXX000000YY-001/invoice";
    $securityKey = "Z60gftKe9sei3xOZhvvDa0StkVILKR3j5MBM9ygi1zg=";
    $headers = [
        "Content-Type: application/json",
        "security_key: ". $securityKey
    ];

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
    } 
    else {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode >= 200 && $httpCode < 300) {
            $formattedResponse = json_encode(json_decode($response), JSON_PRETTY_PRINT);
            echo "<pre>Success: " . $formattedResponse . "</pre>";
        } else {
            echo "Error: HTTP Status Code - " . $httpCode . ", Response: " . $response;
        }
    }
    curl_close($ch);
}
?>
