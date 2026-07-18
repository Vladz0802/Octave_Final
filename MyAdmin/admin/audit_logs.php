<?php

include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/header.php");

?>

<div class="container-fluid">

    <div class="row">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-lg-10 p-4">

            <div class="topbar">

                <h2 class="fw-bold">
                    Audit Logs
                </h2>

                <small>
                    View all administrator activities.
                </small>

            </div>

            <div class="card shadow mt-4">

                <div class="card-body">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Date & Time</th>
                                <th>Administrator</th>
                                <th>Activity</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $logs = mysqli_query($conn,"
                                SELECT audit_logs.*, admins.fullname
                                FROM audit_logs
                                INNER JOIN admins
                                ON audit_logs.admin_id = admins.admin_id
                                ORDER BY log_date DESC
                            ");

                            if(mysqli_num_rows($logs) > 0)
                            {
                                while($row = mysqli_fetch_assoc($logs))
                                {
                            ?>

                            <tr>

                                <td><?php echo date("M d, Y h:i A", strtotime($row['log_date'])); ?></td>

                                <td><?php echo $row['fullname']; ?></td>

                                <td><?php echo $row['activity']; ?></td>

                            </tr>

                            <?php

                                }
                            }
                            else
                            {

                            ?>

                            <tr>

                                <td colspan="3" class="text-center">

                                    No audit logs found.

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

<?php include("../includes/footer.php"); ?>