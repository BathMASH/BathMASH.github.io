<?php

//For each language available in trans we build the page for that language
$transDir = glob("../trans/*", GLOB_ONLYDIR);
foreach($transDir as $langName){

  echo("../lang/".basename($langName)."/index.html\n");
  //open file for output 
  $output = fopen("../lang/".basename($langName)."/index.html", "w");
  $outputED = fopen("../lang/".basename($langName)."/".basename($langName)."-math-dic.html", "w");

  if (!$output) {echo "Could not open file to write";}
  if (!$outputED) {echo "Could not open file to write";}  

  //header//
  fputs($output,"<!DOCTYPE html>\n<html lang=\"en\">\n");
  fputs($outputED,"<!DOCTYPE html>\n<html lang=\"en\">\n");
  fputs($output,"<head>\n<meta charset=\"utf-8\">\n");
  fputs($outputED,"<head>\n<meta charset=\"utf-8\">\n");
  fputs($output,
  "<link href=\"../../helpers/screen.css\" rel=\"stylesheet\" media=\"screen\" type=\"text/css\"/>");
  fputs($output,
  "<link href=\"../../helpers/print.css\" rel=\"stylesheet\" media=\"print\" type=\"text/css\"/>");
  //www.kryogenix.org/code/browser/sorttable/
  fputs($output,
  "<script type=\"text/javascript\" src=\"../../helpers/sorttable.js\"></script>");
  fputs($output,"</head>\n\n");
  fputs($outputED,"</head>\n\n");

  //body//
  fputs($output,"<body>\n\n");
  fputs($outputED,"<body>\n\n");

  //head//
  fputs($output,"<div id=\"header\" align=\"right\"><a href=\"http://www.sigma-network.ac.uk/\"><img src=\"../../logos/sigma-logo.png\" alt=\"Sigma\" width=\"200\" /></a><a href=\"http://www.bath.ac.uk/study/mash/\"><img src=\"../../logos/mashlogo200.gif\" alt=\"MASH\" width=\"200\" /></a></div>");

  //content
  $outputLang = fopen("../lang/".basename($langName)."/lang", "r");
  if (!$outputLang) {echo "There is a missing language information file for ".basename($langName).".\n";}
  $local = fgets($outputLang);
  fclose($outputLang);
  $outputLang = fopen("../lang/".basename($langName)."/langEN", "r");
  if (!$outputLang) {echo "There is a missing language information file for ".basename($langName).".\n";}
  $localEN = fgets($outputLang);
  fclose($outputLang);

  fputs($output,"<h1>$local / $localEN</h1>\n");
  fputs($outputED,"<h1>$local / $localEN</h1>\n");

  //eventually we need code that will run for all src files and to all output languages
  $files = glob('../src/*.{txt}', GLOB_BRACE);
  foreach($files as $inputENname){

    fputs($output,"<h2>".ucwords(str_replace("-"," / ",basename($inputENname,".txt")))."</h2>\n");
    fputs($outputED,"<h2>".ucwords(str_replace("-"," / ",basename($inputENname,".txt")))."</h2>\n");

    //Table for this file
    fputs($output,"<table border=\"1\" class=\"sortable\" id=\"".basename($inputENname,".txt")."\">\n<thead><tr><td><a href=\"#\">English</a></td><td lang=\"".basename($langName)."\"><a href=\"#\">$local</a></td></tr></thead>\n<tbody>\n");
    fputs($outputED,"<table border=\"1\">\n<tr><td><b>English</b></td><td lang=\"".basename($langName)."\"><b>$local</b></td></tr>\n");

    //Open files for this table
    $inputEN = fopen($inputENname,"r");
    $inputTL = fopen("../trans/".basename($langName)."/".basename($inputENname),"r");
    if (!$inputEN) {echo "Could not open English file to read";}
    if (!$inputTL) {echo "Could not open $localEN file to read";}

    //Populate the table
    while (!feof($inputEN)){
      $EN = fgets($inputEN);
      $TL = fgets($inputTL);
      //We would like to be able to tell the difference between untranslated and unchanged but we cannot
      //unless identical strings are marked as translated by the proofreader - we should tell the 
      //proofreaders to do this. 
      if ($EN != $TL){
      	 fputs($output,"<tr><td>$EN</td><td lang=\"".basename($langName)."\">$TL</td></tr>\n"); 
       	 fputs($outputED,"<tr><td>$EN</td><td lang=\"".basename($langName)."\">$TL</td></tr>\n"); 
      }elseif(trim($EN) != ''){
      	 fputs($output,"<tr><td>$EN</td><td><a href=\"https://crowdin.com/project/sigma-lang/invite\" target=\"_blank\">Untranslated</a></td></tr>\n"); 
       	 fputs($outputED,"<tr><td>$EN</td><td>Untranslated</td></tr>\n"); 
      }
    }

    //Finish off the table
    fputs($output,"</tbody></table>\n");
    fputs($outputED,"</table>\n");

    //Close the files for this table
    fclose($inputEN);
    fclose($inputTL);
  }

  //Foot//
  fputs($output,"<div id=\"footer\" align=\"center\"><p>Created by <a href=\"http://www.bath.ac.uk/study/mash/\" target=\"_blank\">MASH</a>, <a href=\"http://www.bath.ac.uk/\" target=\"_blank\">University of Bath</a>. Work partially funded by the <a href=\"http://www.sigma-network.ac.uk/\" target=\"_blank\">sigma Network.</a> Translations are provided in the hope they are useful; we make no guarantees as to the accuracy or completeness of the translations. To get in touch use the project e-mail address: <a href=\"mailto:mash-lang@bath.ac.uk\">mash-lang@bath.ac.uk</a>.</p><p><a rel=\"license\" href=\"http://creativecommons.org/licenses/by-nc-sa/4.0/\"><img alt=\"Creative Commons Licence\" style=\"border-width:0\" src=\"http://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png\" /></a><br />This work is licensed under a <a rel=\"license\" href=\"http://creativecommons.org/licenses/by-nc-sa/4.0/\">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p></div>");

  //Close off the html 
  fputs($output,"</body>\n</html>\n");
  fputs($outputED,"</body>\n</html>\n");

  //close output file
  fclose($output);
  fclose($outputED);
  echo exec("pandoc -o ../lang/".basename($langName)."/".basename($langName)."-math-dic.odt ../lang/".basename($langName)."/".basename($langName)."-math-dic.html");
  echo exec("pandoc -o ../lang/".basename($langName)."/".basename($langName)."-math-dic.docx ../lang/".basename($langName)."/".basename($langName)."-math-dic.html");
  echo ("\n");
}

?>