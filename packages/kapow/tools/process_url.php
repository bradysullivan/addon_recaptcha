<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

if(isset($_POST['kapow_comment']))
{
     include_once "headwinds2lib.php";
     $response = _headwinds2_return_initial_cookie($_POST['kapow_comment'],
                            $_POST['kapow_author'], $_POST['kapow_email']);
    echo json_encode($response);
}
