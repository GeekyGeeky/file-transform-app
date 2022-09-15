# File Transform App

This is a simple API to Transform a file from JSON to CSV and vice-versa
## Stack
- Laravel (https://laravel.com/)

## Installation
- Clone this repository
- cd in to the repo and run `php artisan key:generate`
- Copy `.env.example` to a new `.env` file.
- cd in the repo and run `docker build -t data-transform-app . && docker run -p 8000:8000 -d --name dt-app data-transform-app`
- Voila, the app should be running on port 8000!

## Running locally
- run `php artisan serve` to boot up a php server

## Testing out the endpoint
- POST request to `/api/transform` 
- FormData -> ['data_file'=> 'file', 'sort_by'=>'string'] 

## Feature
- Convert Json file to CSV
- Convert CSV file to Json

### Useful links

- **[staging server](https://data-transform-app.herokuapp.com/)**
