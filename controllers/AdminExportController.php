<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminExportController extends AdminController {
    private $exportSheet;
    private $studentSheet;
    private $ucitelSheet;
    private $filename;
    private $datum_od;
    private $datum_do;
    private $trida;
    private $skolnirok;
    private $dataStudent;
    private $dataUcitel;
    
    public function __construct($dateFrom, $dateTo, $trida, int $skolnirok) {
        $this->datum_od = $dateFrom;
        $this->datum_do = $dateTo;
        $this->trida = $trida;
        $this->skolnirok = $skolnirok;

        $this->exportSheet = new Spreadsheet();
        $this->studentSheet = $this->exportSheet->getActiveSheet();
        $this->studentSheet->setTitle("Studenti");
        $this->filename = 'exports/eval-export-'.date('Y-m-d-H-i-s') . '.xlsx';

        $this->getDataStudent();
        $this->printHeadStudent();
        $this->printDataStudent();

        $this->exportSheet->createSheet();
        $this->exportSheet->setActiveSheetIndex(1);
        $this->ucitelSheet = $this->exportSheet->getActiveSheet();
        $this->ucitelSheet->setTitle("Učitelé");

        $this->getDataUcitel();
        $this->printHeadUcitel();
        $this->printDataUcitel();
        $this->saveSheet();

        header("Location: /administrace/export");
        header("Location: /".$this->filename);
    }

    public function getDataStudent() {
        $dotaz = "SELECT sod.*, so.poradi as poradi
        FROM studenti_odpovedi sod
        INNER JOIN studenti_otazky so ON sod.id_o = so.id";
        $order = "order by sod.trida,sod.id_p,sod.skupina, sod.id_u, sod.datum, so.poradi asc";

        if($this->datum_od == null && $this->datum_do == null && $this->trida == null) {
            $dotaz = $dotaz . " WHERE so.skolnirok like ? " . $order;
            $this->dataStudent = Databaze::dotaz($dotaz, array($this->skolnirok));   

        } else if ($this->datum_od == null && $this->datum_do == null) {
            $dotaz = $dotaz . " WHERE so.skolnirok like ? and sod.trida like ? " . $order;
            $this->dataStudent = Databaze::dotaz($dotaz, array($this->skolnirok, $this->trida));  

        } else if ($this->trida == null) {
            $dotaz = $dotaz . " WHERE sod.datum >= ? and sod.datum <= ? and so.skolnirok like ? " . $order;
            $this->dataStudent = Databaze::dotaz($dotaz, array($this->datum_od, $this->datum_do, $this->skolnirok));

        } else {
            $dotaz = $dotaz . " WHERE sod.datum >= ? and sod.datum <= ? and so.skolnirok like ? and sod.trida like ? " . $order;
            $this->dataStudent = Databaze::dotaz($dotaz, array($this->datum_od, $this->datum_do, $this->skolnirok, $this->trida));    

        }
    }

    public function printHeadStudent() {
        $otazky = Databaze::dotaz("SELECT * from studenti_otazky where skolnirok like ? order by poradi asc", array($this->skolnirok));
        
        $this->studentSheet->setCellValue('A1', 'Třída');
        $this->studentSheet->setCellValue('B1', 'Předmět');
        $this->studentSheet->setCellValue('C1', 'Skupina');
        $this->studentSheet->setCellValue('D1', 'Učitel');
        $this->studentSheet->setCellValue('E1', 'Datum');
        $this->studentSheet->setCellValue('F1', 'Čas');
        $this->studentSheet->setCellValue('G1', 'Celý');
        
        $i = 0;
        
        foreach($otazky as $otazka) {
            $letter = chr(ord('H') + $i);
            $this->studentSheet->setCellValue($letter.'1', $otazka["poradi"]);
            $i++;
        }
    }

    public function printDataStudent() {
        $i = 2;
        $pocetOtazek = 
            Databaze::dotaz("SELECT count(poradi) as pocet
                            FROM studenti_otazky
                            WHERE skolnirok like ?",
                            array($this->skolnirok))[0]["pocet"];
        foreach($this->dataStudent as $jedenDat) {
            $otazka = $jedenDat["poradi"];
            if($otazka == 1) {
                list($datum, $cas) = explode(" ", $jedenDat["datum"]);
                $celyRetezec = $jedenDat["trida"].'-'.$jedenDat["id_p"].'-'.$jedenDat["skupina"].'-'.$jedenDat["id_u"];
                $this->studentSheet->setCellValue('A'.$i, $jedenDat["trida"]);
                $this->studentSheet->setCellValue('B'.$i, $jedenDat["id_p"]);
                $this->studentSheet->setCellValue('C'.$i, $jedenDat["skupina"]);
                $this->studentSheet->setCellValue('D'.$i, $jedenDat["id_u"]);
                $this->studentSheet->setCellValue('E'.$i, $datum);
                $this->studentSheet->setCellValue('F'.$i, $cas);
                $this->studentSheet->setCellValue('G'.$i, $celyRetezec);    
            }
            $letter = chr(ord('H') + $otazka - 1);
            $this->studentSheet->setCellValue($letter . $i, $jedenDat["odpoved"]);
            if($otazka == $pocetOtazek) {
                $i++;
            }
        }

    }

    public function getDataUcitel() {
        $dotaz = "SELECT uod.*, uo.poradi as poradi
        FROM ucitele_odpovedi uod
        INNER JOIN ucitele_otazky uo ON uod.id_o = uo.id";
        $order = "order by uod.trida,uod.id_p,uod.skupina, uod.datum, uo.poradi asc";

        if($this->datum_od == null && $this->datum_do == null && $this->trida == null) {
            $dotaz = $dotaz . " WHERE uo.skolnirok like ? " . $order;
            $this->dataUcitel = Databaze::dotaz($dotaz, array($this->skolnirok));   

        } else if ($this->datum_od == null && $this->datum_do == null) {
            $dotaz = $dotaz . " WHERE uo.skolnirok like ? and uod.trida like ? " . $order;
            $this->dataUcitel = Databaze::dotaz($dotaz, array($this->skolnirok, $this->trida));  

        } else if ($this->trida == null) {
            $dotaz = $dotaz . " WHERE uod.datum >= ? and uod.datum <= ? and uo.skolnirok like ? " . $order;
            $this->dataUcitel = Databaze::dotaz($dotaz, array($this->datum_od, $this->datum_do, $this->skolnirok));

        } else {
            $dotaz = $dotaz . " WHERE uod.datum >= ? and uod.datum <= ? and uo.skolnirok like ? and uod.trida like ? " . $order;
            $this->dataUcitel = Databaze::dotaz($dotaz, array($this->datum_od, $this->datum_do, $this->skolnirok, $this->trida));    

        }
    }

    public function printHeadUcitel() {
        $otazky = Databaze::dotaz("SELECT * from ucitele_otazky where skolnirok like ? order by poradi asc", array($this->skolnirok));
        
        $this->ucitelSheet->setCellValue('A1', 'Třída');
        $this->ucitelSheet->setCellValue('B1', 'Předmět');
        $this->ucitelSheet->setCellValue('C1', 'Skupina');
        $this->ucitelSheet->setCellValue('D1', 'Datum');
        $this->ucitelSheet->setCellValue('E1', 'Čas');
        $this->ucitelSheet->setCellValue('F1', 'Celý');
        
        $i = 0;
        
        foreach($otazky as $otazka) {
            $letter = chr(ord('G') + $i);
            $this->ucitelSheet->setCellValue($letter.'1', $otazka["poradi"]);
            $i++;
        }
    }

    public function printDataUcitel() {
        $i = 2;
        $pocetOtazek = 
            Databaze::dotaz("SELECT count(poradi) as pocet
                            FROM ucitele_otazky
                            WHERE skolnirok like ?",
                            array($this->skolnirok))[0]["pocet"];
        foreach($this->dataUcitel as $jedenDat) {
            $otazka = $jedenDat["poradi"];
            if($otazka == 1) {
                list($datum, $cas) = explode(" ", $jedenDat["datum"]);
                $celyRetezec = $jedenDat["trida"].'-'.$jedenDat["id_p"].'-'.$jedenDat["skupina"];
                $this->ucitelSheet->setCellValue('A'.$i, $jedenDat["trida"]);
                $this->ucitelSheet->setCellValue('B'.$i, $jedenDat["id_p"]);
                $this->ucitelSheet->setCellValue('C'.$i, $jedenDat["skupina"]);
                $this->ucitelSheet->setCellValue('D'.$i, $datum);
                $this->ucitelSheet->setCellValue('E'.$i, $cas);
                $this->ucitelSheet->setCellValue('F'.$i, $celyRetezec);    
            }
            $letter = chr(ord('G') + $otazka - 1);
            $this->ucitelSheet->setCellValue($letter . $i, $jedenDat["odpoved"]);
            if($otazka == $pocetOtazek) {
                $i++;
            }
        }   
    }

    public function saveSheet() {
        $this->exportSheet->setActiveSheetIndex(0);
        $writer = new Xlsx($this->exportSheet);
        if (!is_dir('exports')) {
            mkdir('exports');
        }
        $writer->save($this->filename);
    }
}