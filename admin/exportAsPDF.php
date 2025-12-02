<?php

require_once '../vendor/autoload.php';

$mpdf = new mPDF();
$mpdf->WriteHTML('<h1>Hello world!</h1>');
$mpdf->Output();
