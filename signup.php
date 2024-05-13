<!-- OKAY NA! -->
<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/signup_view.inc.php';
include("top.html");
?>

    <a class="btn btn-primary shadow" role="button" href="login.php">Log In</a>
</nav>

    <div class="row d-flex justify-content-center">
        <div class="col-md-6 col-xl-4">
            <div class="card mb-5">
                <div class="card-body d-flex flex-column align-items-center"><img src="assets/img/Farm%20tractor%20black%20silhouette%20logo%20(1).png" width="202" height="187">
                    <form class="text-center" action="includes/signup.inc.php" method="post">
                        <div class="mb-3"><input class="form-control" type="text" onkeydown="return /[a-zA-Z]/i.test(event.key)" name="fname" placeholder="First Name"></div>
                        <div class="mb-3"><input class="form-control" type="text" onkeydown="return /[a-zA-Z]/i.test(event.key)" name="lname" placeholder="Last Name"></div>
                        <div class="mb-3"><input class="form-control" type="text" onkeydown="return /[a-zA-Z0-9_]/i.test(event.key)" name="uname" placeholder="Username"></div>
                        <div class="mb-3"><input class="form-control" type="password" name="pwd" placeholder="Password"></div>
                        <button class="btn btn-primary d-block w-100 mb-3" type="submit" name="submit">Sign Up</button>
                    </form>

                    
                    <a href="login.php">Already have an Account?</a>
                </div>
            </div>
        </div>
    </div>

<?php include("bottom.html");?>
