# bucket
Google Cloud ***Bucket Storage*** API for Legacy Apps, like that old PHP app without Composer.

### [notes]
Container is expecting google credential on production at `/gcs-1/bucket.json` and service account with ***Storage Admin*** or similar access to bucket. Dev files at `/bucket-dev` directory. Production files at `/cloud-run-files`. Dev expecting google credential at `/app/bucket.json` inside container.




