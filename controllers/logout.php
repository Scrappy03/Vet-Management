<?php
// Process user session termination
logout_user();

header("Location: home");
exit;