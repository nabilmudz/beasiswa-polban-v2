<?php
if (!empty($_FILES['files'])) {
    $uploadDir = 'uploads/'; // Ensure this directory exists
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['files']['name'][$key]);
        $uploadFilePath = $uploadDir . $fileName;
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($tmp_name, $uploadFilePath)) {
            echo "File uploaded successfully: $fileName\n";
        } else {
            echo "Failed to upload: $fileName\n";
        }
    }
} else {
    echo "No files uploaded.";
}
?>
