<?php
$name = $request->get('name', 'World');
?>
Goodbye guys <?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?>