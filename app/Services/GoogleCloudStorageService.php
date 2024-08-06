<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;

class GoogleCloudStorageService
{
    protected $storageClient;
    protected $bucket;

    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => env('GOOGLE_APPLICATION_CREDENTIALS'),
        ]);

        $this->bucket = $this->storageClient->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));
    }

    public function uploadFile($filePath, $fileContent)
    {
        $file = $this->bucket->upload($fileContent, [
            'name' => $filePath,
        ]);

        return $file;
    }

    public function downloadFile($filePath)
    {
        $object = $this->bucket->object($filePath);

        if ($object->exists()) {
            $stream = $object->downloadAsStream();
            $mimeType = $object->info()['contentType']; 
    
            return ['stream' => $stream, 'mimeType' => $mimeType];
        }
    
        return null;
    }
    public function listFiles($prefix)
    {
        $objects = $this->bucket->objects([
            'prefix' => $prefix,
        ]);

        $files = [];
        foreach ($objects as $object) {
            $files[] = $object->name();
        }

        return $files;
    }

    public function deleteFile($path)
    {
        $object = $this->bucket->object($path);

        if ($object->exists()) {
            $object->delete();
            return true;
        }
    
        return false;
        
    }
}
