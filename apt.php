<?php
// recebendo parametros da linha de comando

$argumento0 = $argv[0];
//$argumento_1 = $argv[1];
//$argumento_2 = $argv[2];

//echo 'argumentos';
echo $argumento0;
echo "<br>";

// Recommended
parse_str($argumento0, $parametros);

echo $parametros['adv'];  // value
echo "<br>";
echo $parametros['cmp'];  // value
echo "<br>";
echo $parametros['ipa'];  // value
echo "<br>";
echo $parametros['dgt'];  // value
echo "<br>";

if ( !$parametros['adv'] || !$parametros['cmp'] ||
     !$parametros['ipa'] || !$parametros['dgt'] )
   {
      echo '** Faltando Parâmetros **';
      exit(1);
   }
   else
   {
      echo 'Parâmetros = OK';
   }
echo "<br>";

echo "continua execução";
echo "<br>";

$count_file = $adv."_".$cmp;

$apt_file = $adv."_".$cmp.".apt";

echo $count_file;

echo "<br>";

echo $apt_file;

echo "<br>";


//ini_set('register_argc_argv', 1);

//$inipath = php_ini_loaded_file();

//phpinfo();

//if ($inipath) {
//    echo 'Loaded php.ini: ' . $inipath;
//} else {
//    echo 'A php.ini file is not loaded';
//}

//var_dump($argv);

  // settings
$count_path = 'count/';
$count_file = $count_path . $count_file;
$count_lock = $count_path . 'count_lock';

$apt_file = $count_path . $apt_file;

// aquire non-blocking exlusive lock for this thread
// thread 1 creates count/count_lock0/
// thread 2 creates count/count_lock1/
$i = 0;
while (file_exists($count_lock . $i) || !@mkdir($count_lock . $i)) {
    $i++;
    if ($i > 200) {
        exit($count_lock . $i . ' writable?');
    }
}

// set count per thread
// thread 1 updates count/count.0
// thread 2 updates count/count.1
$count = intval(@file_get_contents($count_file . $i));
$count++;
//sleep(3);

file_put_contents($count_file . $i, $count);

//file_put_contents($count_file . $i, $argumento_0);

//file_put_contents($count_file . $i, $argumento_1);

//file_put_contents($count_file . $i, $argumento_2);


// remove lock
rmdir($count_lock . $i);

// grava evento de abertura

$msg = date("Ymd").";".strftime("%H:%M:%S").";".$adv.";".$cmp.";".$ipa.";".$dgt. "\r\n";

if (!$fp = fopen($apt_file, "a")) {

   exit(2);

}
else
{
   flock($fp, LOCK_EX);
   if (fwrite($fp, $msg) === FALSE) {
    }
  flock($fp, LOCK_UN);
  fclose($fp);
}
?>
