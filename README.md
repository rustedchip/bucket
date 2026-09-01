# bucket
Google Cloud ***Bucket Storage*** API for Legacy Apps, like that old PHP app without Composer.

### [notes]
Container is expecting google credential on production at `/gcs-1/bucket.json` and service account with ***Storage Admin*** or similar access to bucket. Dev files at `/bucket-dev` directory. Production files at `/cloud-run-files`. Dev expecting google credential at `/app/bucket.json` inside container.

### [usage]
It is a simple endpoint, you have the following self-explanatory routes:
```
Route::get('/', function () {
    return response()->json(['message' => 'ok'], 200);
});

Route::post('bucket/upload/{path}', [BucketController::class, 'upload'])->middleware(BucketAuth::class);
Route::get('bucket/download/{path}', [BucketController::class, 'download'])->middleware(BucketAuth::class);
Route::get('bucket/files/{path}', [BucketController::class, 'files'])->middleware(BucketAuth::class);
Route::delete('bucket/delete/{path}', [BucketController::class, 'delete'])->middleware(BucketAuth::class);
Route::get('bucket/verify/{path}', [BucketController::class, 'verify'])->middleware(BucketAuth::class);
```
Fill also self-explanatory variables to connect your Google Cloud Bucket and your allowed endpoints or sites.

```
GOOGLE_CLOUD_PROJECT_ID=
GOOGLE_CLOUD_STORAGE_BUCKET=
GOOGLE_APPLICATION_CREDENTIALS=/app/bucket.json
GOOGLE_CLOUD_STORAGE_API_URI=

API_TOKEN=
ALLOWED_DOMAIN=

ENDPOINT_A=http://localhost:6060
ENDPOINT_B=
ENDPOINT_C=
ENDPOINT_D=
```



