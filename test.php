<?php


$t = "hey fam æøæøæ <div> s </div> FAM SQUAD"; 
echo $t;
echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8');

echo "fam";

echo "sup dude" . $t . "fam";


?>