<?php

echo "
<form action='k.php' method='get'>
<input type='text' name='id'>   
<input type='submit'>

</form>" ;
$t = "hey fam æøæøæ <div> s </div> FAM SQUAD"; 
echo $t;
echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
return;
echo "fam";



?>