<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCloudStorageService;
use Illuminate\Support\Facades\Response;
use Exception;
use Illuminate\Support\Facades\Log;

class BucketController extends Controller
{
    protected $googleCloudStorageService;

    public function __construct(GoogleCloudStorageService $googleCloudStorageService)
    {
        $this->googleCloudStorageService = $googleCloudStorageService;
    }


    public function upload(Request $request)
    {
        try {
          
            $request->validate([
                'file' => 'required|file',
            ]);

            $file = $request->file('file');
            $filePath = 'uploads/' . $file->getClientOriginalName();

            $this->googleCloudStorageService->uploadFile($filePath, file_get_contents($file));

            return response()->json(['message' => 'file-uploaded-successfully'], 200);
        } catch (Exception $e) {
            Log::error('file-upload-failed :: ' . $e->getMessage());
            return response()->json(['error' => 'file-upload-failed'], 500);
        }
    }


    public function download($file)
    {
        try {
            $filePath = 'uploads/' . $file;

            $fileStream = $this->googleCloudStorageService->downloadFile($filePath);

            if ($fileStream) {
                return Response::make($fileStream, 200, [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $file . '"',
                ]);
            }

            return response()->json(['message' => 'file-not-found'], 404);
        } catch (Exception $e) {
            Log::error('file-download-failed :: ' . $e->getMessage());
            return response()->json(['error' => 'file-download-failed'], 500);
        }
    }
    public function files()
    {

        try {
           
            $files = $this->googleCloudStorageService->listFiles('uploads/');

            return response()->json($files, 200);
        } catch (Exception $e) {
            Log::error('file-listing-failed: ' . $e->getMessage());
            return response()->json(['error' => 'file-listing-failed'], 500);
        }
    }

    public function delete($file)
    {
        try {
            $filePath = 'uploads/' . $file;
            $deleted = $this->googleCloudStorageService->deleteFile($filePath);

            if ($deleted) {
                return response()->json(['message' => 'file-deleted-successfully'], 200);
            } else {
                return response()->json(['message' => 'file-not-found'], 404);
            }
        } catch (Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage());
            return response()->json(['error' => 'file-deletion-failed'], 500);
        }
    }
}
