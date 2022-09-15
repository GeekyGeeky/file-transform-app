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
     * A single source of operation
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
                ]
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

        // Retrieve a portion of the validated input data...
        $validated = $validator->safe()->only(['data_file', 'sort_by']);
        $sortBy = $validated['sort_by'] ?? '';
        $uploadedFile = $validated['data_file'];

        $path = $uploadedFile->path();
        $extension = $uploadedFile->getClientOriginalExtension();

        $transformMethod = FileTransformFactory::getConversionMethod($extension);
        $typeAndExtension =  $transformMethod->getTypeAndExtension();
        $this->setHeaders($typeAndExtension['extension'], $typeAndExtension['type']);
        echo $transformMethod->convert($path, $sortBy);
        exit();
    }

    private function setHeaders($fileext, $filetype)
    {
        header('Content-disposition: attachment; filename=' . 'result.' .  $fileext);
        header('Content-type: ' . $filetype);
    }
}
