# http_code_worker

## Prerequisites
1. MySQL
2. Jobs table

```
  [id] - Stores an incrementing identifier for the job
  [url] - Stores a common URL
  [status] - Contains one of the values NEW, PROCESSING, DONE or ERROR
  [http_code] - Stores the resulting HTTP­code from the request.
```

## Installation
1. composer install
2. run from command line `php index.php`
