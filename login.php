<?php
session_start();

include("./servidor.php");

extract($_POST);

$sql = " select  * from usuarios " ;
$sql .= " where cpf = '".$cpf."' and  senha ='".$senha."'";

$resultado  = mysqli_query($banco, $sql );

echo mysqli_num_rows($resultado);


 if(mysqli_num_rows($resultado) == 1){

   $campo = mysqli_fetch_array($resultado);


   if($campo["Cli_Codigo"] == 1){
      

      $_SESSION["login"]["id"] = $campo["Cli_Codigo"];
      $_SESSION["login"]["user"] = $campo["Cli_Nome"];

      header("Location:adm.php");
   }
   else{
      echo "errrrrroooooouuu";
   }
   }else{
      unset($_SESSION["login"]);
      header("location:index.php");
      echo "<script type='text/javascript'>
            alert('Login efetuado);
            </script>";
   }