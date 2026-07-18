<?php

function addAuditLog($conn, $admin_id, $activity)
{
    $activity = mysqli_real_escape_string($conn, $activity);

    mysqli_query($conn, "
        INSERT INTO audit_logs (admin_id, activity)
        VALUES ('$admin_id', '$activity')
    ");
}