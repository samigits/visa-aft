<?php
function decideTransient()
{
    if (isset($_POST['type_direct']) || $_POST['token'] === 'true') {
        return false;
    } else {
        return true;
    }
}

$itemPrice = $_POST['price'];
$currency = $_POST['currency'];
$imgUrl = $_POST['imageSource'];
$cardHolder = $_POST['cardholderName'];
$transient = decideTransient();
$isSavedToken = $_POST['token'];

function verifyToken()
{
    $url = "http://localhost:3000/flex-microform/verifyToken";
    $data = array("flexToken" => $_POST['flexresponse']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec(($ch));
    $resObject = json_decode($response, true);
    return $resObject;
}

function payerAuthenticationSetup($transientToken, $isSavedToken)
{
    $url = "http://localhost:3000/payment/setup";
    //provide payment instrument if payment is from token
    $data = array("isTransientToken" => decideTransient(), "transientToken" => $transientToken, "isSavedToken" => false, "paymentInstrument" => "28DC99F0516D90C6E063AF598E0AFE5F");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $authSetup = json_decode($response, true);
    return $authSetup;
}

function payerAuthenticationSetupWithSavedCard()
{
    $url = "http://localhost:3000/payment/setup";
    //provide payment instrument if payment is from token
    $data = array("isSavedToken" => true, "paymentInstrument" => "--- replace this with your own token ----");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $authSetup = json_decode($response, true);
    return $authSetup;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isTransinet = decideTransient();
    if ($transient) {
        $tvRes = verifyToken();
        $jti = $tvRes["data"]["payload"]["jti"];
        $jsonRsp = payerAuthenticationSetup($jti, $isSavedToken);
        if ($jsonRsp && $jsonRsp["data"]["status"] === "COMPLETED") {
            $accessToken = $jsonRsp["data"]["consumerAuthenticationInformation"]["accessToken"];
            $ddcUrl = $jsonRsp["data"]["consumerAuthenticationInformation"]["deviceDataCollectionUrl"];
            $ddcReference = $jsonRsp["data"]["consumerAuthenticationInformation"]["referenceId"];
            $merchantReference = $jsonRsp["data"]["clientReferenceInformation"]["code"];
        } else {
            echo "<p>unable to process 3D Secure Setup</p>";
        }
    } else if ($isSavedToken === 'true') {
        $jsonRsp = payerAuthenticationSetupWithSavedCard();
        if ($jsonRsp && $jsonRsp["data"]["status"] === "COMPLETED") {
            $accessToken = $jsonRsp["data"]["consumerAuthenticationInformation"]["accessToken"];
            $ddcUrl = $jsonRsp["data"]["consumerAuthenticationInformation"]["deviceDataCollectionUrl"];
            $ddcReference = $jsonRsp["data"]["consumerAuthenticationInformation"]["referenceId"];
            $merchantReference = $jsonRsp["data"]["clientReferenceInformation"]["code"];
        } else {
            echo "<p>unable to process 3D Secure Setup</p>";
        }
    } else {
        $jsonRsp = payerAuthenticationSetup("", "");
        if ($jsonRsp && $jsonRsp["data"]["status"] === "COMPLETED") {
            $accessToken = $jsonRsp["data"]["consumerAuthenticationInformation"]["accessToken"];
            $ddcUrl = $jsonRsp["data"]["consumerAuthenticationInformation"]["deviceDataCollectionUrl"];
            $ddcReference = $jsonRsp["data"]["consumerAuthenticationInformation"]["referenceId"];
        } else {
            echo "<p>unable to process 3D Secure Setup</p>";
        }
    }
}
?>

<html lang="en">

<head>
    <title>Token</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <style>
        .td-1 {
            word-break: break-all;
            word-wrap: break-word;
        }

        .card,
        .card-body {
            margin-top: 2%;
            border-style: none;
        }

        .card-img-top {
            height: 18rem;
            width: auto;
        }
    </style>
</head>

<body>
    <div class="container card">
        <div class="card-body">
            <form id="my-token-form" action="receipt.php" method="post">
                <h3>Confirm your order ):</h3>
                <div class="row">
                    <div class="col-sm-3 mb-3 mb-sm-0">
                        <img src="<?php echo $imgUrl ?>" class="card-img-top" alt="...">
                    </div>
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $itemPrice . " " . $currency ?></h5>
                                <p class="card-text">Some descriptions will be here in the future, but don't take this as a granted promise</p>
                                <input type="hidden" id="flexresponse" class="form-control" name="flexresponse" value='<?php echo $jti; ?>' />
                                <input type="hidden" id="cardHolderName" calss="form-control" name="cardHolderName" value='<?php echo $cardHolder; ?>' />
                                <input type="hidden" id="itemPrice" class="form-control" name="itemPrice" value='<?php echo $itemPrice; ?>' />
                                <input type="hidden" id="currency" class="form-control" name="currency" value='<?php echo $currency; ?>' />
                                <input type="hidden" id="ddcReference" calss="form-control" name="ddcReference" value='<?php echo $ddcReference; ?>' />
                                <input type="hidden" id="merchantReference" class="form-control" name="merchantReference" value="<?php echo $merchantReference; ?>" />
                                <input type="hidden" id="type_flex" name="type_flex" class="form-control" value='<?php echo decideTransient(); ?>' />
                                <input type="hidden" id="is_saved_card" name="is_saved_card" class="form_control" value="<?php echo $isSavedToken; ?>" />
                                <button type="submit" id="pay-button" name="payButton" class="btn btn-outline-dark" style="margin-top: 10%">Confirm Order</button>
                                <button type="submit" id="pay-with-card" name="pay-with-card" class="btn btn-outline-danger" style="margin-top: 10%;display:none;">Pay ****4242</button>

                                <?php
                                if ($transient) {
                                    echo "<br/><br/><div><p><strong>Payer is Authenticating with Transient Token</strong> </p></div>";
                                } else {

                                    echo "<br/><br/><div><p><strong>Payer is Authenticating with card ****4242</strong></p></div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <iframe id="cardinal_collection_iframe" name="collectionIframe" height="10" width="10" style="display: none;"></iframe>
    <form id="cardinal_collection_form" method="POST" target="collectionIframe" action=<?php echo $ddcUrl ?>>
        <input id="cardinal_collection_form_input" type="hidden" name="JWT" value="<?php echo $accessToken ?>">
    </form>
    <script>
        window.onload = function() {
            var cardinalCollectionForm = document.querySelector('#cardinal_collection_form');
            if (cardinalCollectionForm) {
                console.log("Collection Result:")
                cardinalCollectionForm.submit();
            }

        }

        window.addEventListener("message", function(event) {
            if (event.origin === "https://centinelapistag.cardinalcommerce.com") {
                console.log(event.data);
                document.getElementById("pay-button").disabled = false;
            }
        }, false);
        document.getElementById("pay-button").disabled = true;
        setTimeout(function() {
            document.getElementById("pay-button").disabled = false
        }, 10000)
    </script>
</body>

</html>