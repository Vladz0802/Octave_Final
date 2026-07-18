<?php
include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/header.php");
include("../includes/functions.php");

if (isset($_POST['save_admin'])) {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $check = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");

    if (mysqli_num_rows($check) > 0) {

        echo "<script>alert('Username already exists.');</script>";

    } else {

        $insert = mysqli_query($conn, "
            INSERT INTO admins(fullname,email,username,password,role)
            VALUES('$fullname','$email','$username','$password','$role')
        ");

        if ($insert) {

            addAuditLog($conn, $_SESSION['admin'], "Added Admin");

            echo "<script>
                    alert('Admin added successfully.');
                    window.location='admin_users.php';
                  </script>";

        } else {

            echo "<script>alert('Failed to add admin.');</script>";

        }

    }

}

if (isset($_POST['update_admin'])) {

    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $check = mysqli_query($conn, "
        SELECT *
        FROM admins
        WHERE username='$username'
        AND admin_id != '$admin_id'
    ");

    if (mysqli_num_rows($check) > 0) {

        echo "<script>alert('Username already exists.');</script>";

    } else {

        $update = mysqli_query($conn, "
            UPDATE admins
            SET
                fullname='$fullname',
                email='$email',
                username='$username',
                role='$role'
            WHERE admin_id='$admin_id'
        ");

        if ($update) {

            addAuditLog($conn, $_SESSION['admin'], "Updated Admin");

            echo "<script>
                    alert('Admin updated successfully.');
                    window.location='admin_users.php';
                  </script>";

        } else {

            echo "<script>alert('Failed to update admin.');</script>";

        }

    }

}

if (isset($_GET['delete_id'])) {

    $delete_id = $_GET['delete_id'];

    if ($delete_id == $_SESSION['admin']) {

        echo "<script>
                alert('You cannot delete your own account while you are logged in.');
                window.location='admin_users.php';
              </script>";

    } else {

        $delete = mysqli_query($conn, "
            DELETE FROM admins
            WHERE admin_id='$delete_id'
        ");

        if ($delete) {

            addAuditLog($conn, $_SESSION['admin'], "Deleted Admin");

            echo "<script>
                    alert('Admin deleted successfully.');
                    window.location='admin_users.php';
                  </script>";

        } else {

            echo "<script>
                    alert('Failed to delete admin.');
                  </script>";

        }

    }

}

$edit = null;

if (isset($_GET['edit_id'])) {

    $edit_id = $_GET['edit_id'];

    $result = mysqli_query($conn, "SELECT * FROM admins WHERE admin_id='$edit_id'");

    if (mysqli_num_rows($result) > 0) {
        $edit = mysqli_fetch_assoc($result);
    }
}


?>

<div class="container-fluid">

    <div class="row">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-lg-10 p-4">

            <div class="topbar d-flex justify-content-between align-items-center">

                <div>

                    <h2 class="fw-bold">
                        Admin Users
                    </h2>

                    <small>
                        Manage administrator accounts.
                    </small>

                </div>

                <button
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#addAdminModal">

                    <i class="bi bi-person-plus"></i>

                    Add Admin

                </button>

            </div>

            <div class="card shadow mt-4">

                <div class="card-body">

                    <form method="GET">

                        <div class="row mb-3">

                            <div class="col-md-4">

                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search Admin">

                            </div>

                            <div class="col-md-2">

                                <button class="btn btn-primary">

                                    Search

                                </button>

                            </div>

                        </div>

                    </form>

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th width="180">Actions</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if(isset($_GET['search']))
                            {
                                $search = mysqli_real_escape_string($conn, $_GET['search']);

                                $sql = mysqli_query($conn,"
                                    SELECT *
                                    FROM admins
                                    WHERE fullname LIKE '%$search%'
                                    OR username LIKE '%$search%'
                                    OR email LIKE '%$search%'
                                ");
                            }
                            else
                            {
                                $sql = mysqli_query($conn, "SELECT * FROM admins");
                            }

                            if(mysqli_num_rows($sql) > 0)
                            {
                                while($row = mysqli_fetch_assoc($sql))
                                {
                            ?>

                            <tr>

                                <td><?php echo $row['fullname']; ?></td>

                                <td><?php echo $row['email']; ?></td>

                                <td><?php echo $row['username']; ?></td>

                                <td>

                                    <span class="badge bg-primary">

                                        <?php echo $row['role']; ?>

                                    </span>

                                </td>

                                <td>

                  <a href="admin_users.php?edit_id=<?= $row['admin_id']; ?>" class="btn btn-warning btn-sm">
                  <i class="bi bi-pencil"></i>
                 </a>

                  <a
                    href="admin_users.php?delete_id=<?= $row['admin_id']; ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this admin?');">

                   <i class="bi bi-trash"></i>

                </a>

                                </td>

                            </tr>

                            <?php

                                }
                            }
                            else
                            {

                            ?>

                            <tr>

                                <td colspan="5" class="text-center">

                                    No admin users found.

                                </td>

                            </tr>

                            <?php

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/add_admin_modal.php"); ?>
<?php include("../includes/edit_admin_modal.php"); ?>

<?php if ($edit) { ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = new bootstrap.Modal(document.getElementById("editAdminModal"));
    modal.show();
});
</script>
<?php } ?>

<?php include("../includes/footer.php"); ?>