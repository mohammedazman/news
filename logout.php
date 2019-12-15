<?php
session_start();
require_once 'session.class.php';

Session::destroy();

header('Location: index.php?msg=You have been logged out');