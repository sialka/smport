<li class='list-group-item no-border bg-light text-dark'>
  <i class="fa-solid fa-clock text-dark"></i>
  Horário: <strong><?= $hora ?> </strong>
</li>

<?php
  foreach($igrejas as $igreja){

    $texto = '';
    $icons = '';

    // gps
    if($igreja[2] != ''){
      $url = $igreja[2];
      $icons .= '<i class="fa-solid fa-map-location-dot text-danger"></i> ';
    }else{
      $url = "#";
      $icons .= '<i class="fa-solid fa-map-location-dot text-light"></i> ';
    }

    // anciao
    if($igreja[1] != '0'){
      $icons .= '<i class="fa-solid fa-shield text-warning"></i>';
    }else{
      $icons .= '<i class="fa-solid fa-shield text-light"></i>';
    }

    echo "<a href='{$url}' class='no-decoration'>";
    echo "<li class='list-group-item no-border text-left text-dark'>";
    echo "<div class='d-flex justify-content-between'>";
    echo "<div><i class='fa-solid fa-caret-right text-primary'></i> {$igreja[0]}</div>";
    echo "<div>{$icons}</div>";
    echo "</div>";
    echo "</li>";
    echo "</a>";

  }
?>
