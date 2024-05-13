<!-- OKAY NA! -->
<?php 
require_once 'includes/config_session.inc.php';
require_once 'includes/login_view.inc.php';
include("top.html");
?>

    <a class="btn btn-primary shadow" role="button" href="signup.php">Sign Up</a><button class="navbar-toggler" data-bs-toggle="collapse"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
</nav>

    <div class="row d-flex justify-content-center">
        <div class="col-md-6 col-xl-4">
            <div class="card mb-5">
                <div class="card-body d-flex flex-column align-items-center"><img src="assets/img/Farm%20tractor%20black%20silhouette%20logo%20(1).png" width="202" height="187">
                    <form class="text-center" action="includes/login.inc.php" method="post">
                        <div class="mb-3"><input class="form-control" type="text" name="uname" placeholder="Username"></div>
                        <div class="mb-3"><input class="form-control" type="password" name="pwd" placeholder="Password"></div>
                        <div class="mb-3"><button class="btn btn-primary d-block w-100" type="submit">Login</button></div>
                        <p class="text-muted"></p>
                    </form>

                    <p>
                    <?php 
                        check_login_errors();
                    ?>
                    </p>

                    <a href="signup.php">Create Account</a>

                </div>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/Billing-Table-with-Add-Row--Fixed-Header-Feature-Billing-Table-with-Add-Row--Fixed-Header.js"></script>
    <script src="assets/js/bold-and-bright.js"></script>
    <script src="assets/js/Dynamic-Table-dynamic-table.js"></script>
    <script src="assets/js/Dynamically-Add-Remove-Table-Row-style.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="assets/js/Profile-Edit-Form-profile.js"></script>
    <script src="assets/js/Table-with-search-table.js"></script>
</body>

</html>