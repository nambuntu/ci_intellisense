<?php
/**
 * Code template generator for CI.
 * @param unknown $haystack
 * @param unknown $needle
 * @return boolean
 * @author NamNguyen (namnvhue@gmail.com)
 */

function startsWith($haystack, $needle)
{
  return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  return (substr($haystack, -$length) === $needle);
}

$path = array(
    '../application/models',
    '../application/libraries',
);
$controller_fp = fopen('fake_controller.php', 'w');
$model_fp = fopen('fake_model.php', 'w');
fwrite($controller_fp, 
"<?php 
class CI_Controller extends Template {	\n");

fwrite($model_fp,
"<?php \n
class CI_Model extends Template {	\n");

$ignore_list = array('2dbarcodes.php');
foreach($path as $lib_path) {
  $it = new RecursiveDirectoryIterator($lib_path);
  foreach(new RecursiveIteratorIterator($it) as $file) {
    $file_name = basename($file);
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    if($file_name != '.' && $file_name != '..' && $extension == 'php'
        && !in_array($file_name, $ignore_list)) {

      $obj_name = str_replace('.' . $extension, '', $file_name);
      $obj_name = str_replace('-', '', $obj_name);
      $obj_name = str_replace('.', '', $obj_name);
      $str =
      "/**\n" .
      "*\n" .
      "* @var " . ucfirst($obj_name) . "\n" . 
      "*/\n" .
      "public $" . strtolower($obj_name) . ";\n\n";
      
      fwrite($controller_fp, $str);
      fwrite($model_fp, $str);
    }
  }
}
fwrite($controller_fp, "\n}");
fclose($controller_fp);

fwrite($model_fp, "\n}");
fclose($model_fp);

echo "Done.";