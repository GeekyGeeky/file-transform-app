<?php

namespace App\Http\Controllers;

use App\Factories\FileTransformFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class FileTransformController extends Controller
{

    /**
     * A single source of operation for transforming files
     * Recieves the request as a dependency, validates the input and triggers the file transform service
     *
     * @var    \Illuminate\Http\UploadedFile $uploadedFile
     * @param  \App\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function __invoke(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data_file' => [
                    'required',
                    Rule::prohibitedIf(fn () => !$request->hasFile('data_file') || !$request->file('data_file')->isValid())
                ],
                'sort_by' => ['nullable']
            ],
            [
                'data_file.prohibited' => 'File type must be JSON or CSV'
            ]
        );

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Retrieve a portion of the validated input data...
            $validated = $validator->safe()->only(['data_file', 'sort_by']);
            $sortBy = $validated['sort_by'] ?? '';
            $uploadedFile = $validated['data_file'];

            $path = $uploadedFile->path();
            $extension = $uploadedFile->getClientOriginalExtension();

            /**
             * Get the correct service to process the file
             */
            $transformMethod = FileTransformFactory::getConversionMethod($extension);
            $typeAndExtension =  $transformMethod->getTypeAndExtension();
            $this->setHeaders($typeAndExtension['extension'], $typeAndExtension['type']);
            echo $transformMethod->convert($path, $sortBy);
            exit();
        } catch (\Throwable $e) {
            // log error to external service if necessary
            // return error as simple json
            return Response::json([
                'status' => false,
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    private function setHeaders($fileext, $filetype)
    {
        header('Content-disposition: attachment; filename=' . 'result.' .  $fileext);
        header('Content-type: ' . $filetype);
    }
}
