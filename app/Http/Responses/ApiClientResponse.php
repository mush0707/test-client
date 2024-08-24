<?php

namespace App\Http\Responses;

use App\Http\Responses\Messages\ErrorsMessage;
use App\Http\Responses\Messages\SuccessMessage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as Response;

trait ApiClientResponse
{

    public function success($data = [], ?string $message = null): JsonResponse
    {
        return $this->responseJson(new SuccessMessage($data, $message), Response::HTTP_OK);
    }

    public function created(?string $message = null): JsonResponse
    {
        return $this->responseJson(new SuccessMessage([], $message), Response::HTTP_CREATED);
    }

    public function errors(array $errors, string $message = '', $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->responseJson([
            'message' => $message,
            'errors' => new ErrorsMessage($errors)
        ], $code);
    }

    /**
     * @param array $errors
     * @return JsonResponse
     */
    public function permissionErrors(array $errors): JsonResponse
    {
        return $this->responseJson(new ErrorsMessage($errors), Response::HTTP_FORBIDDEN);
    }

    /**
     * @param array $errors
     * @return JsonResponse
     */
    public function validationErrors(array $errors)
    {
        return $this->responseJson(new ErrorsMessage($errors), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Response
     */
    public function destroyResponse()
    {
        return response()->noContent();
    }

    public function responseJson($data, $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    public function responseFile($file, $contentType = 'application/pdf')
    {
        return  response()->download($file);
    }
}
