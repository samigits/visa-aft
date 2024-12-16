<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sample Checkout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <style>
        body {
            background: #E0E0E0;
        }

        .details {
            border: 1.5px solid grey;
            color: #212121;
            width: 100%;
            height: auto;
            box-shadow: 0px 0px 10px #212121;
        }

        .cart {
            background-color: #212121;
            color: white;
            margin-top: 10px;
            font-size: 12px;
            font-weight: 900;
            width: 100%;
            height: 39px;
            padding-top: 9px;
            box-shadow: 0px 5px 10px #212121;
        }

        .card {
            width: fit-content;
        }

        .card-body {
            width: fit-content;
        }

        .btn {
            border-radius: 0;
        }

        .img-thumbnail {
            border: none;
        }

        .card {
            box-shadow: 0 20px 40px rgba(0, 0, 0, .2);
            border-radius: 5px;
            padding-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class='container-fluid'>
        <div class="card mx-auto col-md-3 col-10 mt-5">
            <img class="mx-auto img-thumbnail" src="https://www.pngmart.com/files/6/Watch-PNG-Pic.png" width="auto" height="auto" />
            <div class="card-body text-center mx-auto">
                <div class='cvp'>
                    <form action="checkout.php" method="post">
                        <h5 class="card-title font-weight-bold">Yail wrist watch</h5>
                        <p class="card-text">0 USD</p>
                        <a href="#" class="btn details px-auto">view details</a><br />
                        <input type="hidden" name="itemPrice" id="itemPrice" value="0.00" />
                        <input type="hidden" name="currency" id="currency" value="USD" />
                        <input type="hidden" name="displayUrl" id="displayUrl" value="https://www.pngmart.com/files/6/Watch-PNG-Pic.png" />
                        <button type="submit" id="payButton" name="payButton" class="btn btn-outline-dark cart px-auto">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mx-auto col-md-3 col-10 mt-5">
            <img class="mx-auto img-thumbnail" src="https://www.pngmart.com/files/6/Watch-Background-PNG.png" width="auto" height="auto" />
            <div class="card-body text-center mx-auto">
                <div class='cvp'>
                    <form method="post" action="checkout.php">
                        <h5 class="card-title font-weight-bold">Navy Smart watch</h5>
                        <p class="card-text">12.00 USD</p>
                        <a href="#" class="btn details px-auto">view details</a><br />
                        <input type="hidden" name="itemPrice" id="itemPrice" value="12.00" />
                        <input type="hidden" name="currency" id="currency" value="USD" />
                        <input type="hidden" name="displayUrl" id="displayUrl" value="https://www.pngmart.com/files/6/Watch-Background-PNG.png" />
                        <button type="submit" class="btn btn-outline-dark cart px-auto">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mx-auto col-md-3 col-10 mt-5">
            <img class="mx-auto img-thumbnail" src="https://www.pngmart.com/files/6/Watch-PNG-Transparent.png" width="auto" height="auto" />
            <div class="card-body text-center mx-auto">
                <div class='cvp'>
                    <form method="post" action="checkout.php">
                        <h5 class="card-title font-weight-bold">Rolex Golden watch</h5>
                        <p class="card-text">45.00 USD</p>
                        <a href="#" class="btn details px-auto">view details</a><br />
                        <input type="hidden" name="itemPrice" id="itemPrice" value="45.00" />
                        <input type="hidden" name="currency" id="currency" value="USD" />
                        <input type="hidden" name="displayUrl" id="displayUrl" value="https://www.pngmart.com/files/6/Watch-PNG-Transparent.png" />
                        <button type="submit" class="btn btn-outline-dark cart px-auto">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>