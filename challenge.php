<?php
if (isset($_GET['accessToken'])) {
    $accessToken = $_GET['accessToken'];
    $stepUpUrl = $_GET['stepUpUrl'];
} else {
    echo "Required parameters are missing.";
}
?>

<html>

<head>
    <title>Issuer Challenge</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
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

    .modal-dialog {
        max-width: 500px;
    }

    .iframe-container {
        width: 100%;
        height: 400px;
    }
</style>

<body>
    <!-- Modal -->
    <div class="modal fade" id="stepUpModal" tabindex="-1" role="dialog" aria-labelledby="stepUpModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stepUpModalLabel">Transaction Window</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modalCloseButton">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe name="step-up-iframe" class="iframe-container" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form -->
    <form id="step-up-form" target="step-up-iframe" method="post" action="<?php echo $stepUpUrl; ?>">
        <input type="hidden" name="JWT" value="<?php echo $accessToken; ?>" />
        <input type="hidden" name="MD" value="7037619999950902503"/>
    </form>
</body>

<script>
    // JavaScript variable to control modal closure
    let closeModal = false;

    // Show modal on page load
    $(document).ready(function () {
        $('#stepUpModal').modal('show');
        var stepUpForm = document.querySelector('#step-up-form');
        if (stepUpForm) {
            stepUpForm.submit();
        }
    });

    // Control modal close button visibility
    document.getElementById('modalCloseButton').addEventListener('click', function (event) {
        if (!closeModal) {
            event.preventDefault(); // Prevent modal from closing
            alert('The modal cannot be closed yet!');
        }
    });

    // Example logic to enable modal closure
    setTimeout(() => {
        closeModal = true; // Allow closing the modal after 5 seconds
    }, 5000);
</script>

</html>
