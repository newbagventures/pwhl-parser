<?php

$total_lines = count( file( "us-products-export.csv" ) );
$fh = fopen( "us-products-export.csv", "r" ) or die( "Couldn't find the CSV file" );
unlink( "us-products-export-updated.csv" );
$fh1 = fopen( "us-products-export-updated.csv", "a" ) or die( "Couldn't open the new file for writing" );

echo "<h2>$total_lines total lines in the file</h2>\n";
echo "<h2>Source File: <a href=\"us-products-export.csv\">US Products Export.csv</a></h2>";
echo "<h2>Result File for Import to Shopify: <a href=\"us-products-export-updated.csv.zip\">US Products Export Updated.csv</a></h2>";
echo "<p>&nbsp;</p><p>&nbsp;</p><hr /><p>&nbsp;</p><p>&nbsp;</p>\n";

$i = 0;
$total_changes = 0;

while( ( $line = fgetcsv( $fh ) ) !== FALSE ) {
   if( $i == 0 ) {
        array_push( $line, "Materials Debugging", "Care Debugging", "Fit Debugging", "Specs Debuggin", "Features Debugging" );
        fputcsv( $fh1, $line ) or die( "Something went wrong with writing the entry $total_changes" );
    }
     if( ($line[4] != "") && ($i > 0) ) {
        $line_array = explode( "\n", $line[4] );
        $total_elements_in_details = count( $line_array );
        echo ">Record " . ($i) . ". Found $total_elements_in_details in details variable for ".$line[0] ." <a href=\"https://shop.thepwhl.com/products/". $line[1] ."\" target=\"_blank\">" . $line[1] . "</a>.</p>\n";
        if( in_array( $line[0], array( "9070440481089", "9070443135297", "9070445429057", "9070448050497", "9070450245953", "9070452080961", "9070453948737", "9070455914817", "9070457585985", "9070459519297", "9070461092161", "9070462730561", "9070464991553", "9070466629953", "9070469415233", "9070470922561" ) ) === FALSE ) {
            for ($n = 0; $n < $total_elements_in_details; $n++) {
                $this_detail = strtolower(str_replace("<p>", "", str_replace("</p>", "", $line_array[$n])));
                if (strlen($this_detail) > 15) {
                    if (substr_count($this_detail, "material", 0, 15) > 0) {
                        echo "<strong>Found Materials and Care Instructions Part 1</strong>: " . $this_detail;
                        $line[30] = $this_detail;
                    } elseif (substr_count($this_detail, "care", 0, 15) > 0) {
                        echo "<strong>Found Materials and Care Instructions Part 2</strong>: " . $this_detail;
                        $line[30] .= $this_detail;
                    } elseif (substr_count($this_detail, "fit", 0, 15) > 0) {
                        echo "<strong>Found Fit Information</strong>: " . $this_detail;
                        $line[29] = $this_detail;
                    } elseif (substr_count($this_detail, "spec", 0, 10) > 0) {
                        echo "<strong>Found Specs and Features</strong>: " . $this_detail;
                        $line[31] = $this_detail;
                    } else {
                        echo "$n [" . $this_detail . "] found nothing<br />\n";
                    }
                }
                $found = strpos($line_array[$n], "material");
                if ($found !== FALSE) {
                    $line[36] = "Found the string [material] at position $found.\n";
                } else {
                    $line[36] = "";
                }
                $found = strpos($line_array[$n], "care");
                if ($found !== FALSE) {
                    $line[37] = "Found the string [care] at position $found.\n";
                } else {
                    $line[37] = "";
                }
                $found = strpos($line_array[$n], "fit");
                if ($found !== FALSE) {
                    $line[38] = "Found the string [fit] at position $found.\n";
                } else {
                    $line[38] = "";
                }
                $found = strpos($line_array[$n], "spec");
                if ($found !== FALSE) {
                    $line[39] = "Found the string [spec] at position $found.\n";
                } else {
                    $line[39] = "";
                }
                $found = strpos($line_array[$n], "feature");
                if ($found !== FALSE) {
                    $line[40] = "Found the string [feature] at position $found.\n";
                } else {
                    $line[40] = "";
                }
                echo "</p>\n";
            }
            fputcsv($fh1, $line) or die("Something went wrong with writing the entry $total_changes");
            $total_changes++;
        }
    }
    echo "<hr />\n";
    $i++;
}
fclose( $fh );
fclose( $fh1 );

$zip = new ZipArchive();
$filename = "us-products-export-updated.csv" . ".zip";
if ($zip->open($filename) === TRUE) {
    $zip->addFile("us-products-export-updated.csv" );
    $zip->close();
}

echo "<p>Total required changes = $total_changes</p>";
