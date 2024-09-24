<?php 

if($status == 'Ativo') { 
  echo "<span class='badge bg-success text-white px-2' style='border-radius: 0px;'>". $status."</span>";
}

if($status == 'Inativo') { 
  echo "<span class='badge bg-danger text-white px-2' style='border-radius: 0px;'>".$status."</span>";
} 

?>