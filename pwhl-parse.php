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
        $line_array = explode( "\r", $line[4] );
        echo "<p><strong>Line array: </strong>" . print_r( $line_array, true );
        foreach( $line_array AS $key=>$val ) {
            echo "<p>" . $key . " :: " . $val . "</p>\n";
            if( substr_count( strtolower( $val ), "material", 0, ( strlen( "material" ) + 1) ) > 0 ) {
                echo "<p><strong>Found Material Details</strong></p>\n" . $val;
            }
            if( substr_count( strtolower( $val ), "care", 0, ( strlen( "care" ) + 1 ) ) > 0 ) {
                echo "<p><strong>Found Care Instructions</strong></p>\n" . $val;
            }
            if( substr_count( strtolower( $val ), "fit", 0, ( strlen( "fit" ) + 1 ) ) > 0 ) {
                echo "<p><strong>Found Fit Info</strong></p>\n" . $val;
            }
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
?>