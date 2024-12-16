<?php
try {
    //credentials for secure acceptance payment
    $profile_id = '<-- replace with your profile id -->';
    $access_key = '<-- replace with your access key -->';
    $transaction_uuid = uniqid();
    $reference_number = rand(1000000, 9999999);

    //order details
    $item_price = $_POST['itemPrice'];
    $currency = $_POST['currency'];
    $img_url = $_POST['displayUrl'];

    //channel identifier flags
    $flex = false;
    $sach = false;
    $direct = true;

    //generate cc default
    $url = "http://localhost:3000/flex-microform/captureContextFromSdk";
    $data = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
    $jsonData = json_decode($response, true);
    if ($jsonData && isset($jsonData['data'])) {
        $captureContext = $jsonData['data'];
    }
} catch (Exception $e) {
    echo 'Exception occured: ' . $e->getMessage() . "<br/>";
}



if (array_key_exists('flex_active', $_POST)) {
    echo "#############";
}
if (array_key_exists('flex_inactive', $_POST)) {
    echo "************";
}

?>
<html lang="en">

<head>
    <title>Sample Checkout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script type="text/javascript"
        src="https://h.online-metrix.net/fp/tags.js?org_id=1snn5n9w&session_id=merchantIdSessionId">
    </script>
    <style>
        button {
            cursor: pointer;
        }

        li {
            margin: 2%;
            margin-top: 20%;
        }

        #number-container,
        #securityCode-container {
            height: 38px;
        }

        .flex-microform-focused {
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .card {
            flex-direction: row;
            border-style: none;
            width: 100%;
            background-color: #FCFBF4;
            margin-left: 0%;
            margin-top: 5%;
        }

        .card img {
            width: 25%;
        }

        .option-box {
            margin-top: 25%;
            margin-left: 5%;
            padding-top: 2%;
        }
    </style>
</head>
<noscript>
    <iframe style="width: 100px; height: 100px;
border: 0; position: absolute; top: -5000px;"
        src="https://h.online-metrix.net/fp/tags.js?org_id=1snn5n9w&session_id=merchantIdSessoinId"></iframe>
</noscript>
<div class="container">
    <div class="row">
        <div class="col" style="background:#FCFBF4">
            <div class="card" id="flex-container">
                <div class="card-body">
                    <div class="card">
                        <img src="<?php echo $img_url ?>" class="card-img-top" alt="Image description">
                        <div class="card-body">
                            <p class="card-text">Some descriptions will be here in the future, but don't take this as a granted promise.</p>
                            <h5 class="card-title"><?php echo $item_price . "  " . $currency ?></h5>
                        </div>
                    </div>
                    <h1>Checkout</h1>
                    <div id="errors-output" style="color:red" role="alert"></div>
                    <form id="my-sample-form" action="token.php" method="post">
                        <div class="form-group">
                            <input type="hidden" id="price" name="price" value="<?php echo $item_price ?>" />
                            <input type="hidden" id="currency" name="currency" value="<?php echo $currency ?>" />
                            <input type="hidden" id="imageSource" name="imageSource" value="<?php echo $img_url ?>" />
                            <label for="cardholderName">Name</label>
                            <input id="cardholderName" class="form-control" name="cardholderName" placeholder="Name on the card">
                            <label id="cardNumber-label">Card Number</label>
                            <div id="number-container" class="form-control"></div>
                            <label for="securityCode-container">Security Code</label>
                            <div id="securityCode-container" class="form-control"></div>
                            <input type="hidden" id="token" name="token" value="false" />
                            <input type="hidden" id="captureContext" name="captureContext" value="<?php echo $captureContext; ?>" />
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="expMonth">Expiry month</label>
                                <select id="expMonth" class="form-control">
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="expYear">Expiry year</label>
                                <select id="expYear" class="form-control">
                                    <option>2025</option>
                                    <option>2026</option>
                                    <option>2027</option>
                                    <option>2028</option>
                                    <option>2029</option>
                                    <option>2030</option>
                                    <option>2031</option>
                                    <option>2032</option>
                                </select>
                            </div>
                        </div>

                        <button type="button" id="pay-button" class="btn btn-outline-success" style="width:120px">Pay</button>
                        <input type="hidden" id="flexresponse" name="flexresponse">
                    </form>
                </div>
            </div>
            <div class="card" id="sach-container">
                <div class="card-body">
                    <div class="card">
                        <img src="<?php echo $img_url ?>" class="card-img-top" alt="Image description">
                        <div class="card-body">
                            <p class="card-text">Some descriptions will be here in the future, but don't take this as a granted promise.</p>
                            <h5 class="card-title"><?php echo $item_price . "  " . $currency ?></h5>
                        </div>
                    </div>
                    <h1>Checkout</h1>
                    <div id="output-errors" style="color:red" role="alert"></div>
                    <form action="confirm.php" method="post">

                        <input type="hidden" id="price" name="price" value="<?php echo $item_price ?>" />
                        <input type="hidden" id="currency" name="currency" value="<?php echo $currency ?>" />
                        <input type="hidden" id="imageSource" name="imageSource" value="<?php echo $img_url ?>" />

                        <input type="hidden" name="access_key" value="<?php echo $access_key; ?>">
                        <input type="hidden" name="reference_number" value="123456">
                        <input type="hidden" name="amount" size="25" value="<?php echo $item_price ?>">
                        <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">
                        <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
                        <!-- <input type="hidden" name="override_custom_receipt_page" value="https://localhost/receipt.php" /> -->
                        <input type="hidden" name="signed_field_names" value="currency,access_key,reference_number,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,amount,bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_city,bill_to_address_country,bill_to_address_state,bill_to_address_postal_code">
                        <input type="hidden" name="unsigned_field_names" value="">
                        <input type="hidden" name="signed_date_time" value="<?php echo gmdate("Y-m-d\TH:i:s\Z"); ?>">
                        <input type="hidden" name="locale" value="en">
                        <input type="hidden" name="transaction_type" value="sale">
                        <input type="hidden" name="bill_to_forename" value="NOREAL">
                        <input type="hidden" name="bill_to_surname" value="NAME">
                        <input type="hidden" name="bill_to_email" value="null@cybersource.com" />
                        <input type="hidden" name="bill_to_address_line1" value="1295 Charleston Road" />
                        <input type="hidden" name="bill_to_address_city" value="Mountain View" />
                        <input type="hidden" name="bill_to_address_country" value="US" />
                        <input type="hidden" name="bill_to_address_state" value="CA" />
                        <input type="hidden" name="bill_to_address_postal_code" value="94043" />
                        <input type="hidden" name="currency" size="25" value="<?php echo $currency ?>">
                        <button type="submit" class="btn btn-outline-success" style="width:120px">Pay</button>
                    </form>
                </div>
            </div>
            <div class="card" id="direct-container">
                <div class="card-body">
                    <div class="card">
                        <img src="<?php echo $img_url ?>" class="card-img-top" alt="Image description">
                        <div class="card-body">
                            <p class="card-text">Some descriptions will be here in the future, but don't take this as a granted promise.</p>
                            <h5 class="card-title"><?php echo $item_price . "  " . $currency ?></h5>
                        </div>
                    </div>
                    <h1>Checkout</h1>
                    <div id="output-errors" style="color:red" role="alert"></div>
                    <form action="token.php" method="post">
                        <div class="form-group">
                            <input type="hidden" id="type_direct" name="type_direct" value="type_direct" />
                            <input type="hidden" id="price" name="price" value="<?php echo $item_price ?>" />
                            <input type="hidden" id="currency" name="currency" value="<?php echo $currency ?>" />
                            <input type="hidden" id="imageSource" name="imageSource" value="<?php echo $img_url ?>" />
                            <input type="hidden" id="token" name="token" value="false" />
                            <label for="cardholderName">Name</label>
                            <input id="cardholderName" name="cardholderName" class="form-control" placeholder="Name on the card">
                            <label>Card Number</label>
                            <input class="form-control" placeholder="Enter pan number">
                            <label for="securityCode-container">Security Code</label>
                            <input class="form-control" placeholder="CVV or CVN">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="monthExp">Expiry month</label>
                                <select id="monthExp" class="form-control">
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="yearExp">Expiry year</label>
                                <select id="yearExp" class="form-control">
                                    <option>2025</option>
                                    <option>2026</option>
                                    <option>2027</option>
                                    <option>2028</option>
                                    <option>2029</option>
                                    <option>2030</option>
                                    <option>2031</option>
                                    <option>2032</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-success" style="width:120px">Pay</button>
                    </form>
                </div>
            </div>
            <div class="card" id="saved-card-container">
                <div class="card-body">
                    <div class="card">
                        <img src="<?php echo $img_url ?>" class="card-img-top" alt="Image description">
                        <div class="card-body">
                            <p class="card-text">Some descriptions will be here in the future, but don't take this as a granted promise.</p>
                            <h5 class="card-title"><?php echo $item_price . "  " . $currency ?></h5>
                        </div>
                    </div>
                    <form id="my-sample-form" action="token.php" method="post">
                        <div class="form-group">
                            <input type="hidden" id="price" name="price" value="<?php echo $item_price ?>" />
                            <input type="hidden" id="currency" name="currency" value="<?php echo $currency ?>" />
                            <input type="hidden" id="imageSource" name="imageSource" value="<?php echo $img_url ?>" />
                            <input type="hidden" id="token" name="token" value="true" />
                            <input type="hidden" id="cardholderName" name="cardholderName" class="form-control" value="John Doe" />
                        </div>

                        <button type="submit" id="pay-button" class="btn btn-outline-success" style="width:220px">Pay with saved card 4000****1000</button>
                        <input type="hidden" id="flexresponse" name="flexresponse">
                    </form>
                </div>
            </div>
        </div>
        <div clas="col">
            <ul class="nav flex-column option-box">
                <li class="nav-item">
                    <div id="active_flex">
                        <button class='btn btn-outline-dark' name='flex_active' id="flex_active" style='margin-left:5px;width:200px'>Flex Microform</button>
                    </div>
                </li>
                <li class="nav-item">
                    <div id="active_sach"><button class='btn btn-outline-dark' name='sach_active' id="sach_active" style='margin-left:5px;width:200px'>Secure Acceptance</button></div>
                </li>
                <li class="nav-item">
                    <div id="active_direct"><button class='btn btn-outline-dark' name='sc_active' id="sc_active" style='margin-left:5px;width:200px'>Use Saved Card</button></div>
                </li>
                <li class="nav-item">
                    <div id="active_direct"><button class='btn btn-outline-dark' name='direct_active' id="direct_active" style='margin-left:5px;width:200px'>Direct API</button></div>
                </li>
            </ul>
        </div>
    </div>
</div>
<script src="https://flex.cybersource.com/cybersource/assets/microform/0.11/flex-microform.min.js"></script>


<script>
    //page togglers
    var flex = true;
    var sahc = false;
    var direct = false;

    var toggleFlex = document.getElementById('flex_active');
    var toggleSach = document.getElementById('sach_active');
    var toggleDirect = document.getElementById('direct_active');
    var toggleSC = document.getElementById('sc_active');

    document.getElementById('sach-container').style.display = "none";
    document.getElementById('direct-container').style.display = "none";
    document.getElementById('saved-card-container').style.display = "none";
    toggleFlex.style.boxShadow = "10px 5px 5px green";
    toggleFlex.addEventListener('click', () => {
        sectionNavigator('flex_active')
    })

    toggleSach.addEventListener('click', () => {
        sectionNavigator('sach_active')
    })

    toggleDirect.addEventListener('click', () => {
        sectionNavigator('direct_active')
    })

    toggleSC.addEventListener('click', () => {
        sectionNavigator('sc_active')
    })

    function sectionNavigator(sectContainer) {
        document.getElementById(sectContainer).style.boxShadow = "10px 5px 5px green";

        if (sectContainer === 'flex_active') {
            var btnContainer = document.getElementById('active_flex');
            document.getElementById('sach-container').style.display = "none";
            document.getElementById('direct-container').style.display = "none";
            document.getElementById('saved-card-container').style.display = "none";
            document.getElementById('flex-container').style.display = "";

            document.getElementById('direct_active').style.boxShadow = "";
            document.getElementById('sach_active').style.boxShadow = '';
            document.getElementById('sc_active').style.boxShadow = "";
        } else if (sectContainer === 'sach_active') {
            var btnContainer = document.getElementById('sach_active');
            document.getElementById('sach-container').style.display = "";
            document.getElementById('flex-container').style.display = "none";
            document.getElementById('direct-container').style.display = "none";
            document.getElementById('saved-card-container').style.display = "none";

            document.getElementById('direct_active').style.boxShadow = "";
            document.getElementById('flex_active').style.boxShadow = ''
            document.getElementById('sc_active').style.boxShadow = "";
        } else if (sectContainer === 'direct_active') {
            var btnContainer = document.getElementById('active_direct');
            document.getElementById('sach-container').style.display = "none";
            document.getElementById('flex-container').style.display = "none";
            document.getElementById('direct-container').style.display = "";
            document.getElementById('saved-card-container').style.display = "none";

            document.getElementById('flex_active').style.boxShadow = "";
            document.getElementById('sach_active').style.boxShadow = ''
            document.getElementById('sc_active').style.boxShadow = "";
        } else if (sectContainer === 'sc_active') {
            console.log("*&*&*(");
            var btnContainer = document.getElementById('active_direct');
            document.getElementById('sach-container').style.display = "none";
            document.getElementById('flex-container').style.display = "none";
            document.getElementById('direct-container').style.display = "none";
            document.getElementById('saved-card-container').style.display = "";

            document.getElementById('flex_active').style.boxShadow = "";
            document.getElementById('sach_active').style.boxShadow = ''
            document.getElementById('direct_active').style.boxShadow = "";
        }
    }
    // JWK is set up on the server side route for /
    var form = document.querySelector('#my-sample-form');
    var payButton = document.querySelector('#pay-button');
    var flexResponse = document.querySelector('#flexresponse');
    var expMonth = document.querySelector('#expMonth');
    var expYear = document.querySelector('#expYear');
    var errorsOutput = document.querySelector('#errors-output');

    // the capture context that was requested server-side for this transaction
    var captureContext = document.getElementById("captureContext").value;
    console.log("captureContext", captureContext)
    // custom styles that will be applied to each field we create using Microform
    var myStyles = {
        'input': {
            'font-size': '15px',
            'font-family': 'helvetica, tahoma, calibri',
            'color': '#555'
        },
        ':focus': {
            'color': 'black'
        },
        ':disabled': {
            'cursor': 'not-allowed'
        },
        'valid': {
            'color': '#3c763d'
        },

        'invalid': {
            'color': '#a94442'
        }
    };
    // setup
    var flex = new Flex(captureContext);
    var microform = flex.microform({
        styles: myStyles
    });
    var number = microform.createField('number', {
        placeholder: 'Enter card number'
    });
    var securityCode = microform.createField('securityCode', {
        placeholder: '•••'
    });

    number.load('#number-container');
    securityCode.load('#securityCode-container');


    payButton.addEventListener('click', function() {

        var options = {
            expirationMonth: document.querySelector('#expMonth').value,
            expirationYear: document.querySelector('#expYear').value
        };

        microform.createToken(options, function(err, token) {
            if (err) {
                // handle error
                console.log("what is going on ===== error")
                console.error(err);
                errorsOutput.textContent = err.message;
            } else {
                // At this point you may pass the token back to your server as you wish.
                // In this example we append a hidden input to the form and submit it.
                console.log("Transient Token", JSON.stringify(token));
                flexResponse.value = JSON.stringify(token);
                form.submit();
            }
        });
    });
</script>
</body>

</html>