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


    public function upload(Request $request, $path)
    {

        $path = str_replace("-", "/", $path);

        $path = str_replace(' ', '_', $path);

        try {

            $request->validate([
                'file' => 'required|file',
            ]);


            $file = $request->file('file');

            if (isset($request->filename)) {

                $filename = $request->filename;
                $filename  = str_replace("-", "_", $filename);


                $filePath = $path . '/' . $filename . '.' . $file->getClientOriginalExtension();
            } else {

                $filename = $file->getClientOriginalName();
                $filename  = str_replace("-", "_", $filename);


                $filePath = $path . '/' . $filename;
            }


            $this->googleCloudStorageService->uploadFile($filePath, file_get_contents($file));

            return response()->json(['message' => 'file-uploaded-successfully', 'success' => true,], 200);
        } catch (Exception $e) {
            Log::error('file-upload-failed :: ' . $e->getMessage());
            return response()->json(['error' => 'file-upload-failed', 'success' => false], 500);
        }
    }


    public function download($path, $file)
    {
        $path = str_replace("-", "/", $path);

        try {
            $fileData = $this->googleCloudStorageService->downloadFile($path . '/' . $file);
    
            if ($fileData) {
                return Response::make($fileData['stream'], 200, [
                    'Content-Type' => $fileData['mimeType'], // Use the MIME type from the service
                    'Content-Disposition' => 'attachment; filename="' . $file . '"',
                ]);
            }
    
            return response()->json(['message' => 'file-not-found', 'success'=> false], 404);
        } catch (Exception $e) {
            Log::error('file-download-failed :: ' . $e->getMessage());
            return response()->json(['error' => 'file-download-failed', 'success'=> false], 500);
        }
    }

    public function files($path)
    {
        $path = str_replace("-", "/", $path);


        try {

            $files = $this->googleCloudStorageService->listFiles($path . '/');

            return response()->json($files, 200);
        } catch (Exception $e) {
            Log::error('file-listing-failed: ' . $e->getMessage());
            return response()->json(['error' => 'file-listing-failed', 'success' => false], 500);
        }
    }

    public function delete(Request $request, $path)
    {

        $path = str_replace("-", "/", $path);

        try {

            $deleted = $this->googleCloudStorageService->deleteFile($path);

            if ($deleted) {
                return response()->json(['message' => 'file-deleted-successfully', 'success' => true,], 200);
            } else {
                return response()->json(['message' => 'file-not-found', 'success' => false,], 404);
            }
        } catch (Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage());
            return response()->json(['error' => 'file-deletion-failed' . $request['path'], 'success' => false], 500);
        }
    }
}
