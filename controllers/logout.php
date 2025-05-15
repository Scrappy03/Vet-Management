<?php
// Process user session termination
logout_user();

header("Location: index.php?msg=logout_success");
exit;