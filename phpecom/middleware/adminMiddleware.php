<?php
include('../functions/myfunctions.php');

if(isset($_SESSION['auth']))
{
    if($_SESSION['role_as'] == 0)
    {
        redirect('../index.php', "No Authorization");
      
    }
   ;
}
else
{
    redirect('../login.php', "Login to continue");

}

?>