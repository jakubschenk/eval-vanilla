<?php

echo "WE IN BOYS";
echo '<br>';
$user = $_SESSION['jmeno'];
echo "Vitej" . $user . '<br>';
echo '<a href="auth/google/logout">logout</a>';