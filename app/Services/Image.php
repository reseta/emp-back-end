<?php

namespace App\Services;

/**
 * Class handling image manipulation
 * Implement all you needs here
 */
class Image
{
    private array $file = [];

    /**
     * The file name
     * @var
     */
    private string $fileName = '';

    /**
     * File max upload size
     * @var int
     */
    private int $fileMaxSize = 1048576;

    /**
     * File store path
     * @var string
     */
    private string $imagePath = '';

    /**
     * File relative path
     * @var string
     */
    private string $imageRelativePath = '';

    /**
     * Store validation errors
     * @var array
     */
    private array $errors = [];

    /**
     * Class constructor
     *
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->file = $file;
        $this->fileMaxSize = $_ENV['APP_IMAGE_MAX_SIZE'] ?? 1048576;
        $this->imagePath = './../public' . $_ENV['APP_IMAGES_PATH'];
        $this->imageRelativePath = $_ENV['APP_IMAGES_PATH'];
    }

    /**
     * Get image new name
     *
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Set image new name
     *
     * @param mixed $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * Get image storage path
     *
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * Set image storage path
     *
     * @param string $imagePath
     */
    public function setImagePath(string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Get image relative path
     * @return string
     */
    public function getImageRelativePath(): mixed
    {
        return $this->imageRelativePath;
    }

    /**
     * Set image relative path
     *
     * @param string $imageRelativePath
     */
    public function setImageRelativePath(mixed $imageRelativePath): void
    {
        $this->imageRelativePath = $imageRelativePath;
    }

    /**
     * Get file max upload size
     *
     * @return int
     */
    public function getFileMaxSize(): int
    {
        return $this->fileMaxSize;
    }

    /**
     * Set file max upload size
     *
     * @param int $fileMaxSize
     */
    public function setFileMaxSize(int $fileMaxSize): void
    {
        $this->fileMaxSize = $fileMaxSize;
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        $pathParts = pathinfo($this->file['name']);

        return $pathParts['extension'];
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Image validations
     *
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->file['size'] > $this->fileMaxSize) {
            $this->errors[] = 'File is too big';
        }

        return !empty($this->errors);
    }

    /**
     * Save image
     *
     * @return void
     */
    public function save(): void
    {
        $this->fileName = md5_file($this->file['tmp_name']) . '.' . $this->getFileExtension();
        move_uploaded_file($this->file["tmp_name"], $this->imagePath . '/' . $this->fileName);
    }
}
