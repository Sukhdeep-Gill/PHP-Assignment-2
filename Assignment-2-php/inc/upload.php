<?php

function uploadImage($field) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return ['error' => 'Invalid image format. Allowed: jpg, jpeg, png, gif.'];
        }

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        $target = 'uploads/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
            return ['path' => $target];
        } else {
            return ['error' => 'Failed to upload image.'];
        }
    }
    return ['path' => null];  // No file uploaded
}

