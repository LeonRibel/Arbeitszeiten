<?php
namespace App\Validator;

class Filevalidator {
    private array $errors = [];

    public function fileRequired(string $name, array $file): void {
        if (empty($file['tmp_name'])) {
            $this->errors[$name][] = 'Datei ist erforderlich';
        }
    }

   public function fileType(string $name, array $file, array $allowed): void {
        if (!empty($file['tmp_name'])) {
            $mime = mime_content_type($file['tmp_name']);
            if (!in_array($mime, $allowed, true)) {
                $this->errors[$name][] = "Ungültiger Dateityp ({$mime})";
            }
        }
   }

    public function isValid(): bool {
        return empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function validate(string $name,array $allowed): bool{ $file=$_FILES[$name];
        $this->fileRequired($name, $file);
        $this->fileType($name, $file, $allowed);
        return $this->isValid();
    }
}

