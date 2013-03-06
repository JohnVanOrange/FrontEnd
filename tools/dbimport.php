<?php
namespace JohnVanOrange\jvo;

require_once('../vendor/autoload.php');
require_once('../settings.inc');

class DBImport extends Base {

 public function __construct($options=array()) {
  parent::__construct();
 }
 
 public function run($sql) {
  $result = $this->db->exec($sql);
  if ($result === FALSE) {
   $error = $this->db->errorInfo();
   throw new \Exception ($error[2]);
  }
  return $result;
 }

}

$import = new DBImport();
try {
 if (!$argv[1]) throw new \Exception('No filename of SQL file specified');
 $result = $import->run(file_get_contents($argv[1]));
 echo "Success!\nRows affected: " . $result . "\n";
}
catch(\Exception $e) {
  echo "Error occured: " . $e->getMessage() . "\n";
}

?>