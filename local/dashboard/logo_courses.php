<?php
header("Content-Type: text/plain");

foreach ($_POST["courses"] as $selectedOption)
{
                var_dump( $selectedOption);
}
    
    print_r($_POST["courses"]);


?>