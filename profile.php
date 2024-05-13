<?php 
session_start();

if (!isset($_SESSION["user_id"])) {
    header('location: login.php');
    exit;
}

    require_once 'includes/config_session.inc.php';            
    require_once 'includes/profile_view.inc.php';
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>profile</title>
    <link rel="icon" type="image/png" sizes="500x500" href="assets/img/Farm_tractor_black_silhouette_logo__2_-removebg-preview.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="assets/css/Login-Form-Basic-icons.css">
    <link rel="stylesheet" href="assets/css/Profile-Edit-Form.css">
</head>

<body>
        <nav class="navbar navbar-expand-md bg-body navbar-shrink navbar-light" id="mainNav" style="padding: 0;padding-bottom: 5px;">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center">
                    <span>
                        <picture><img src="assets/img/Farm_tractor_black_silhouette_logo__2_-removebg-preview.png" width="72" height="68"></picture>
                        Tracktor
                    </span>
                </a>

                <div class="collapse navbar-collapse flex-grow-0 order-md-first" id="navcol-1">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-bar-left">
                                    <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5ZM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5Z"></path>
                                </svg>Back
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-none d-md-block">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="includes/profile_delete.php">
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <form class="text-center" action="includes/profile.inc.php" method="post">
            <div class="mb-3"><input class="form-control" type="text" onkeydown="return /[a-zA-Z]/i.test(event.key)" name="fname" placeholder="<?php output_firstname()?>"></div>
            <div class="mb-3"><input class="form-control" type="text" onkeydown="return /[a-zA-Z]/i.test(event.key)" name="lname" placeholder="<?php output_lastname()?>"></div>
            <div class="mb-3"><input class="form-control" type="text" onkeydown="return /[a-zA-Z0-9_]/i.test(event.key)" name="uname" placeholder="<?php output_username()?>"></div>
            <div class="mb-3"><input class="form-control" type="password" name="pwd" placeholder="Password"></div>
            <div class="col-md-12 content-right">
                <button class="btn btn-primary form-btn" type="submit">SAVE </button>
                <button class="btn btn-danger form-btn" type="reset">CANCEL </button>
            </div>
        </form>


</body>


<script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/bold-and-bright.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="assets/js/Profile-Edit-Form-profile.js"></script>
</body>

</html>
