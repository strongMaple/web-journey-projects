<?php
if (!is_admin_logged_in()) {
    header("Location: ../index.php");
    exit;
}
