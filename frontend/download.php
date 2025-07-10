<?php
// Path to the original HTML file
$htmlFile = __DIR__ . '/form.html';

// Path to temporary PDF file
$pdfFile = sys_get_temp_dir() . '/form_download.pdf';

// Command to convert HTML to PDF
$cmd = "wkhtmltopdf $htmlFile $pdfFile";

// Execute the command
exec($cmd, $output, $return_var);

if ($return_var === 0 && file_exists($pdfFile)) {
    // Serve the PDF to the browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="form.pdf"');
    readfile($pdfFile);
    // Optionally, delete the temp file
    unlink($pdfFile);
} else {
    echo "Failed to generate PDF.";
}
?>
