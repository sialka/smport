<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="login-error" onclick="this.classList.add('hidden');"><?= $message ?></div>
