<?php

session_start();

unset($_SESSION['id']);
unset($_SESSION['name']);

header(header: 'Location: index.php?id=');
