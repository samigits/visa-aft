<?php

function processPayment($cardHolder, $currency, $totalAmount, $ddcReference, $merchantRef)
{
    $url = "http://localhost:3000/payment/combined";

    $data = array(
        "isTransientToken" => $_POST['type_flex'],
        "transientToken" => $_POST['flexresponse'],
        "cardHolder" => $cardHolder,
        "currency" => $currency,
        "totalAmount" => $totalAmount,
        "paReference" => $ddcReference,
        "trxType" => "TRANSIENT",
        "merchantReference" => $merchantRef,
        "isSaveCard" => true
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
    $jsonResp = json_decode($response, true);
    return $jsonResp;
}

function processPaymentUsingSavedCard($cardHolder, $currency, $totalAmount, $ddcReference, $merchantRef)
{
    $url = "http://localhost:3000/payment/pay-with-token";
    $data = array(
        "isTransientToken" => "",
        "transientToken" => "",
        "cardHolder" => $cardHolder,
        "currency" => $currency,
        "totalAmount" => $totalAmount,
        "paReference" => $ddcReference,
        "trxType" => "TOKEN",
        "merchantReference" => $merchantRef,
        "isSaveCard" => false
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
    $jsonResp = json_decode($response, true);
    return $jsonResp;
}

try {
    $paymentToken = $_POST['flexresponse'];
    $cardHolder = $_POST['cardHolderName'];
    $itemPrice = $_POST['itemPrice'];
    $currency = $_POST['currency'];
    $ddcReference = $_POST['ddcReference'];
    $merchantRef = $_POST['merchantReference'];
    $isSavedCard = $_POST['is_saved_card'];
    $reference = mt_rand(100000, 999999);
    $error = "";
    $errorMessage = "";
    $absSuccess = "";
    $enrollment = "";
    if ($isSavedCard === 'true') {
        $enrollment =  processPaymentUsingSavedCard($cardHolder, $currency, $itemPrice, $ddcReference, $merchantRef);
    } else {
        $enrollment = processPayment($cardHolder, $currency, $itemPrice, $ddcReference, $merchantRef);
    }
    echo json_encode($enrollment);
    if ($enrollment['data']['status'] === 'PENDING_AUTHENTICATION') {
        $accessToken = $enrollment['data']['consumerAuthenticationInformation']['accessToken'];
        $stepUpUrl = $enrollment['data']['consumerAuthenticationInformation']['stepUpUrl'];

        echo "\n\n accessToken: " . $accessToken;
        echo "\n\n stepUpUrl: " . $stepUpUrl;

        // Combine both parameters in a single header call
        header("Location: challenge.php?accessToken=" . urlencode($accessToken) . "&stepUpUrl=" . urlencode($stepUpUrl));
        exit; // Ensure no further code is executed after the redirect
    } else {
    }
} catch (Exception $ex) {
    echo "Receipt Exception: " . $ex;
}
?>

<html>

<head>
    <title>Token</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<style>
    .td-1 {
        word-break: break-all;
        word-wrap: break-word;
    }

    .card,
    .card-body {
        margin-top: 2%;
        margin-left: 10%;
        width: 70%;
        border-style: none;
    }

    .card-img-top {
        height: 18rem;
        width: auto;
    }
</style>

<body>
    <div class="card">
        <div class="card-header">
            Order Status
        </div>
        <div class="card-body">
            <h5 class="card-title" style="color:red;">
                <?php
                if ($error === "DECISION_PROFILE_REJECT")
                    echo "Your order is under review, due to decision manager"
                ?>
            </h5>
            <h5 class="card-title" style="color:green;">
                <?php
                if ($absSuccess === true)
                    echo "Transaction is successful,Your order is accepted, :)";
                ?>
            </h5>
            <p class="card-text" style="display:none"><?php echo $errorMessage ?></p>
        </div>
    </div>
</body>

</html>