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

        /*
        if( $total_elements_in_details > 4 ) {
            echo "<p>Got this weirdness - $total_elements_in_details elements.</p>\n";
            echo "<p>" . print_r( $line_array, true ) . "</p>\n";
            die();
        } else {
            echo "<p>Got this normalcy - $total_elements_in_details elements.</p>\n";
            echo "<p>" . print_r( $line_array, true ) . "</p>\n";
        }
        */

        for( $i = 0; $i < $total_elements_in_details; $i++ ) {
            $this_detail = strtolower( str_replace("<p>", "", str_replace("</p>", "", $line_array[$i])));
            if( strlen( $this_detail ) >= 10 ) {
                if (substr_count($this_detail, "material", 0, 15) > 0) {
                    echo "<strong>Found Material Details</strong>: " . $this_detail;
                }
                if (substr_count($this_detail, "care", 0, 10) > 0) {
                    echo "<strong>Found Care Instructions</strong>: " . $this_detail;
                }
                if (substr_count($this_detail, "fit", 0, 10) > 0) {
                    echo "<strong>Found Fit Info</strong>: " . $this_detail;
                }
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
