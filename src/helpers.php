<?php

use Psr\Http\Message\UploadedFileInterface;

function initEnv(): void
{
    $envPath = root_dir('.env');

    if (! file_exists($envPath)) {
        throw new RuntimeException("File not found: {$envPath}");
    }

    if (! is_readable($envPath)) {
        throw new RuntimeException("File is not readable: {$envPath}");
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        $isCommentedLine = str_starts_with($line, '#');

        if ($isCommentedLine) {
            continue;
        }

        $lineKeyValue = explode('=', str_replace(' ', '', $line), 2);

        $key = $lineKeyValue[0];

        $value = $lineKeyValue[1];

        if (! array_key_exists($key, $_ENV)) {
            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
        }
    }
}

if (! function_exists('env')) {
    function env(string $key): mixed
    {
        return getenv($key);
    }
}

if (! function_exists('cfg')) {
    function cfg(string $cfg): string
    {
        $fileAndKey = explode('.', $cfg, 2);

        $cfgFile = require root_dir("config/{$fileAndKey[0]}.php");

        return $cfgFile[$fileAndKey[1]];
    }
}

if (! function_exists('root_dir')) {
    function root_dir(string $path = ''): string
    {
        $dirPath = dirname(__DIR__) . "/{$path}";

        return str_replace('\\', '/', $dirPath);
    }
}

if (! function_exists('public_dir')) {
    function public_dir(string $path = ''): string
    {
        $dirPath = root_dir("public/{$path}");

        return str_replace('\\', '/', $dirPath);
    }
}

if (! function_exists('storage_dir')) {
    function storage_dir(string $path = ''): string
    {
        $dirPath = root_dir("public/storage/{$path}");

        return str_replace('\\', '/', $dirPath);
    }
}

if (! function_exists('app_url')) {
    function app_url(string $path = ''): string
    {
        $dirPath = env('APP_URL') . "/{$path}";

        return str_replace('\\', '/', $dirPath);
    }
}

if (! function_exists('hash_uploaded_file')) {
    function hash_uploaded_file(string $directory, UploadedFileInterface $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}

if (! function_exists('ensure_dir_exists')) {
    function ensure_dir_exists(string $directory): void
    {
        if (! file_exists($directory)) {
            mkdir($directory);
        }
    }
}
