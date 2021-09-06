<h3>Function name: {{ $functionName }}</h3>
<hr>
<?php
$callFunction = call_user_func($functionName);
dd(call_user_func($functionName));
?>