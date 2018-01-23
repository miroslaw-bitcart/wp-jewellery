<?php 

$importer = new AJC_Importer( AJC_LEGACY_DBNAME, AJC_LEGACY_USERNAME, AJC_LEGACY_PASS );
$importer->import_x_products( 10 );
