<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Utils;
use \DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * Description of Faq
 *
 * @author user
 */
class ExcelGenerator
{
   public static function Generate($data,$archive_name){
   		$spreadsheet = new Spreadsheet();

        if(count($data) == 0){
            $sheet = $spreadsheet->getActiveSheet();
        	$writer = new Xlsx($spreadsheet);
        	$writer->save($archive_name);
        	return;
        }
        $sheet_index = 0;
        foreach($data as $first){

            $sheet = $spreadsheet->getActiveSheet($sheet_index);
            $column_index = 65;

            $parameters = get_object_vars($first);

            $cont = 1;

            foreach ($parameters as $key => $value) {
                $sheet->setCellValue(chr($column_index).(string)$cont,$key);   
                $column_index++;
            }

            $cont = 2;
            for($i = 0 ; $i < count($data); $i++){
                $column_index = 65;
                $object = get_object_vars($data[$i]);
                foreach ($object as $key => $value) {
                    $sheet->setCellValue(chr($column_index).(string)($cont), $value);
                    $column_index++;
                }
                $cont++;
            }
            $sheet_index++;
        }        

        

        $writer = new Xlsx($spreadsheet);
        $writer->save($archive_name);
        
   }

   public static function Generate_excel_arrays($data,$archive_name){
    $spreadsheet = new Spreadsheet();

        if(count($data) == 0){
            $sheet = $spreadsheet->getActiveSheet();
            $writer = new Xlsx($spreadsheet);
            $writer->save($archive_name);
            return;
        }
        $sheet_index = 0;
        foreach($data as $key => $first){

            if($sheet_index > 0)
                $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex($sheet_index);
            $spreadsheet->getActiveSheet()->setTitle($key);

            $sheet = $spreadsheet->getActiveSheet($sheet_index);
            $column_index = 65;

            $parameters = get_object_vars($first[0]);

            $cont = 1;

            foreach ($parameters as $key => $value) {
                $sheet->setCellValue(chr($column_index).(string)$cont,$key);   
                $column_index++;
            }

            $cont = 2;
            foreach($first as $obj){
                $column_index = 65;

                $object = get_object_vars($obj);

                foreach ($object as $key => $value) {
                    $sheet->setCellValue(chr($column_index).(string)($cont), $value);
                    $column_index++;
                }
                $cont++;
            }
            $sheet_index++;
        }     
        

        $writer = new Xlsx($spreadsheet);
        $writer->save($archive_name);
   }
}
