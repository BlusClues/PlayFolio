<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div id="modal-signin" class='card'>
                    <div class="card-header text-center bg-white" id='layout-title'>
                        <h1>Playfolio</h1>
                    </div>
                    <div class="card-body">
                        <h2 class="text-center">Sign In</h2>
                        <!--message to tell the user if they have typed the wrong credentials-->
                        <?php
                        if (!empty($incorrect_credentials) && $incorrect_credentials == true) {
                            if (!empty($error_msg_username) && !empty($error_msg_password)) {
                                echo $error_msg_username . $error_msg_password . '</br>';
                            }
                        } else
                            $incorrect_credentials = false;
                        ?>
                        <form action="controller.php" method="post">
                            <input type='hidden' name='page' value='StartPage'>
                            <input type='hidden' name='command' value='SignIn'>
                            <div style="margin-bottom: 15px">
                                <label for='signin-username' class="form-label">Username:</label>
                                <input type='text' name='username' id='signin-username' class="form-control" required>
                            </div>
                            <div style="margin-bottom: 15px">
                                <label for='signin-password' class="form-label">Password:</label>
                                <input type='password' name='password' id="signin-password" class="form-control" required>
                            </div>

                            <input id='submit-signin' type='submit' value='Sign In' class='btn btn-primary'>
                            <input id='move-signup' type='button' value='Create an Account' class='btn btn-secondary'>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class='card' id='modal-signup' style="display: none">
                    <div class="card-header text-center bg-white" id='layout-title'>
                        <h1>Playfolio</h1>
                    </div>
                    <div class="card-body">
                        <h2 class="text-center">Sign Up</h2>
                        <!--checks if the username is already in use-->
                        <?php
                        if (!empty($username_used) && $username_used == true) {
                            if (!empty($error_msg_username)) {
                                echo $error_msg_username . '</br>';
                            }
                        } else
                            $username_used = false;
                        ?>
                        <form action="controller.php" method="post">
                            <input type='hidden' name='page' value='StartPage'>
                            <input type='hidden' name='command' value='SignUp'>

                            <div style="margin-bottom: 15px">
                                <label for='signup-username' class="form-label">Username:</label>
                                <input type='text' name='username' class="form-control" id="signup-username" required>
                            </div>
                            <div style="margin-bottom: 15px">
                                <label for='signup-password' class="form-label">Password:</label>
                                <input type='password' name='password' class="form-control" id="signup-password" required>
                            </div>
                            <div style="margin-bottom: 15px">
                                <label for='signup-email' class="form-label">Email:</label>
                                <input type='email' name='email' class="form-control" id="signup-email" required>
                            </div>

                            <input id='submit-signup' type='submit' value="Sign Up" class='btn btn-primary'>
                            <input id='move-signin' type='button' value='Back to Sign in' class='btn btn-secondary'>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </body>

    <script>
        $(document).ready(function(){
            $('#modal-signin').show();
            $('#modal-signup').hide();

            $('#move-signup').click(function(){
                $('#modal-signup').show();
                $('#modal-signin').hide();
            });

            $('#move-signin').click(function(){
                $('#modal-signin').show();
                $('#modal-signup').hide();
            });
        })
    </script>
</html>