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
           
            if(isset($request->filename)){

                $filePath = $path.'/'.$request->filename.'.'.$file->getClientOriginalExtension();

            }else{
                $filePath = $path.'/' . $file->getClientOriginalName();
            }
            

            $this->googleCloudStorageService->uploadFile($filePath, file_get_contents($file));

            return response()->json(['message' => 'file-uploaded-successfully','success'=> true,], 200);
        } catch (Exception $e) {
            Log::error('file-upload-failed :: ' . $e->getMessage());
            return response()->json(['error' => 'file-upload-failed','success'=> false], 500);
        }
    }


    public function download($file,$path)
    {
        $path = str_replace("-", "/", $path);

        try {
            $filePath = $path.'/'. $file;

            $fileStream = $this->googleCloudStorageService->downloadFile($filePath);

            if ($fileStream) {
                return Response::make($fileStream, 200, [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $file . '"',
                ]);
            }

            return response()->json(['message' => 'file-not-found','success'=> false], 404);
        } catch (Exception $e) {
            Log::error('file-download-failed :: ' . $e->getMessage());
            return response()->json(['error' => 'file-download-failed','success'=> false], 500);
        }
    }
    public function files($path)
    {
        $path = str_replace("-", "/", $path);


        try {
           
            $files = $this->googleCloudStorageService->listFiles($path.'/');

            return response()->json($files, 200);
        } catch (Exception $e) {
            Log::error('file-listing-failed: ' . $e->getMessage());
            return response()->json(['error' => 'file-listing-failed','success'=> false], 500);
        }
    }

    public function delete(Request $request,$path)
    {

        $path = str_replace("-", "/", $path);

        try {
          
            $deleted = $this->googleCloudStorageService->deleteFile($path);

            if ($deleted) {
                return response()->json(['message' => 'file-deleted-successfully','success'=> true,], 200);
            } else {
                return response()->json(['message' => 'file-not-found','success'=> false,], 404);
            }
        } catch (Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage());
            return response()->json(['error' => 'file-deletion-failed'.$request['path'],'success'=> false], 500);
        }
    }
}
