<?php
$htmlFile = __DIR__ . '/form.html';
$pdfFile  = sys_get_temp_dir() . '/form_download.pdf';
$cmd      = "/usr/bin/wkhtmltopdf '$htmlFile' '$pdfFile' 2>&1";

exec($cmd, $output, $return_var);

if ($return_var === 0 && file_exists($pdfFile)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="form.pdf"');
    readfile($pdfFile);
    unlink($pdfFile);
    exit;
} else {
    echo "Failed to generate PDF.<br>";
    echo "Command: $cmd<br>";
    echo "Output:<pre>" . implode("\n", $output) . "</pre>";
}
?>
