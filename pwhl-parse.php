<?php

$total_lines = count( file( "us-products-export.csv" ) );
$fh = fopen( "us-products-export.csv", "r" ) or die( "Couldn't find the CSV file" );
unlink( "us-products-export-updated.csv" );
$fh1 = fopen( "us-products-export-updated.csv", "a" ) or die( "Couldn't open the new file for writing" );

echo "<h2>$total_lines total lines in the file</h2>\n";

$i = 0;
$total_changes = 0;

while( ( $line = fgetcsv( $fh ) ) !== FALSE ) {
     if( ($line[4] != "") && ($i > 0) ) {
        $line_array = explode( "\n", $line[4] );
        $total_elements_in_details = count( $line_array );
        echo "Found $total_elements_in_details in details variable.\n";

        for( $i = 0; $i < $total_elements_in_details; $i++ ) {
            echo "<p>We are on key $i<br />\n";
            if( substr_count( strtolower( $line_array[$i] ), "material" ) > 0 ) {
                echo "<strong>Found Material Details</strong>: " . $line_array[$i];
            }
            if( substr_count( strtolower( $line_array[$i] ), "care" ) > 0 ) {
                echo "<strong>Found Care Instructions</strong>: " . $line_array[$i];
            }
            if( substr_count( strtolower( $line_array[$i] ), "fit" ) > 0 ) {
                echo "<strong>Found Fit Info</strong>: " . $line_array[$i];
            }
            echo "</p>\n";
        }
        $line[2] = "THIS FIELD GOT CHANGED";
        fputcsv( $fh1, $line ) or die( "Something went wrong with writing the entry $total_changes" );
        $total_changes++;
    }
    echo "<hr />\n";
    $i++;
}
fclose( $fh );
fclose( $fh1 );

echo "<p>Total required changes = $total_changes</p>";
