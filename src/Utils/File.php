<?php
namespace App\Utils;

class File implements IFile {
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    public function uploadFile(array $fileData): ?string {
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($this->validateFile($fileData)) {

            $newFileName = $this->generateUniqueFilename($fileData['name']);

            if (move_uploaded_file(
                $fileData['tmp_name'],
                dirname(__DIR__) . self::UPLOAD_DIRECTORY . $newFileName
            )) {
                return $newFileName;
            }
        }
        return null;
    }

    private function validateFile(array $file): bool {
        return $file['size'] <= self::MAX_FILE_SIZE && in_array($file['type'], self::SUPPORTED_TYPES);
    }

    private function generateUniqueFilename(string $filename): string {
        $fileInfo = pathinfo($filename);
        $baseName = $fileInfo['filename'];
        $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';

        $counter = 1;
        while (file_exists(dirname(__DIR__) . self::UPLOAD_DIRECTORY . $baseName . "_$counter" . $extension)) {
            $counter++;
        }

        return $baseName . "_$counter" . $extension;
    }
}

