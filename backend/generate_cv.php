<?php
require_once __DIR__ . '/vendor/autoload.php'; // mPDF autoload

$mpdf = new \Mpdf\Mpdf();

// Read HTML file
$html = file_get_contents('cv_preview.html');

// Write HTML to PDF
$mpdf->WriteHTML($html);

// Output PDF for download
$mpdf->Output('Hashini_Herath_CV.pdf', 'D'); // 'D' = download
