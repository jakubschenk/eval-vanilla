<?php

class AdminImportController {

    private $logfile;
    private $xml_filepath;
    private $datum;
    private $import;

    public function __construct() {
        $this->datum = date("Ymd_h-i-s");
        if(!is_dir("import_cache")) {
            mkdir("import_cache");
        }
        $this->logfile = fopen("import_cache/" . $this->datum . "-importlog.txt", "w");
        fwrite($this->logfile, "-- zacatek importu -- ");
        fwrite($this->logfile, "datum: " . $this->datum);

        $this->nahratSoubor();

        $this->skolnirok = $_POST['rok'];
        $this->import = new XMLImport($this->xml_filepath, $this->skolnirok, $this->logfile);
    }


    public function nahratSoubor() {
        if(isset($_FILES['xmlfile'])) {
            $file_name = $_FILES['xmlfile']['name'];
            $file_tmp = $_FILES['xmlfile']['tmp_name'];
            $file_type = $_FILES['xmlfile']['type'];

            if($file_type != "text/xml") {
                $this->neplatnySoubor($this->logfile, $this->xml_filepath);
            }

            $this->xml_filepath = "import_cache/" . $this->datum . "-import.xml";
            move_uploaded_file($file_tmp, $this->xml_filepath);
        }
    }

    public static function neplatnySoubor($log, $xml) {
        fwrite($log, "neplatny import soubor, ukoncuji import");
        fclose($log);
        unlink($xml);
        header("Location: /administrace/import?neplatnysoubor"); 
    }
}