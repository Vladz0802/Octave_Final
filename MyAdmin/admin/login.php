<html>

<head>

    <meta charset="UTF-8">

    <title>MyAdmin | Administrator Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="container">

    <div class="row justify-content-center align-items-center vh-100">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <img src="../uploads/logo.png" alt="Octave logo" width="56" height="56" class="mb-2">
                        <h2 class="fw-bold">Octave</h2>

                        <p class="text-muted">

                            Administrator Login

                        </p>

                    </div>

                    <form action="" method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Username

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="username"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Password

                            </label>

                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                required>

                        </div>

                        <button
                            class="btn btn-primary w-100"
                            name="login">

                            Login

                        </button>

                    </form>

                    <hr>

                    <p class="text-center text-muted">

                        Authorized Personnel Only

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<footer class="text-center text-secondary small py-3">
    Disclaimer: This website is a student academic project created for educational purposes only.
    No real transactions, payments, or musical instruments are processed or sold.
</footer>

</body>

</html>

<?php

session_start();

include("../includes/db_connection.php");
include("../includes/functions.php");



if(isset($_SESSION['admin'])){

    echo "<script>

            window.location='dashboard.php';

          </script>";

}

if(isset($_POST['login'])){

    $username=$_POST['username'];

    $password=$_POST['password'];

    $sql="SELECT * FROM admins
          WHERE username='$username'
          AND password='$password'";

    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){

    $row=mysqli_fetch_assoc($result);

    $_SESSION['admin']=$row['admin_id'];
    $_SESSION['fullname']=$row['fullname'];
    $_SESSION['role']=$row['role'];

    addAuditLog($conn, $_SESSION['admin'], "Logged In");

    echo "<script>

            alert('Login Successful.');

            window.location='dashboard.php';

          </script>";

}

    else{

        echo "<script>

                alert('Invalid Username or Password.');

              </script>";

    }

}

?>