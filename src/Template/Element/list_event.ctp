<?php if(count($dados) == 0){ ?>


<li class='list-group-item no-border no-radius text-dark p-2 text-center text-uppercase'>
  <i class="fa-solid fa-triangle-exclamation text-warning"></i>
  <?= $msg; ?>
</li>

<?php }else{ ?>

<ul class="list-group no-radius mb-1">
<?php
foreach($dados as $datas => $locais){
  echo "<li class='list-group-item no-border no-radius bg-light text-dark'>Data: <strong>{$datas}</strong></li>";
  foreach($locais as $local){

    echo "<li class='list-group-item no-border no-radius text-left text-dark'>";
    echo "<i class='fa-solid fa-circle-right text-success'></i> ";
    echo "<strong>{$local}</strong>";
    echo "</li>";
  }
}
?>
</ul>

<?php } ?>