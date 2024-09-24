<?php 

$statusApp = $aevOptions;        

$css = $statusApp['status_css_envelope'];

$msg = [
    'status_fichas' => $statusApp['status_fichas_save'],
    'status_envelopes' => $statusApp['status_envelopes_save'],
];

echo "<span class='badge ".$css[$status]." m-0 p-1' style='border-radius: 0px'>".$msg[$tipo][$status]."</span>";

?>