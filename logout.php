<?php

session_start();
unset($_SESSION['user']);
header(header: 'Location: index.php');
