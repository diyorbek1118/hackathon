<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    /**
     * Success response method
     *
     * @param mixed $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, string $message, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }

    /**
     * Error response method
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function sendValidationError(array $errors): JsonResponse
    {
        return $this->sendError('Validation Error', $errors, 422);
    }

    /**
     * Unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->sendError($message, [], 401);
    }

    /**
     * Forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->sendError($message, [], 403);
    }

    /**
     * Not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendNotFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->sendError($message, [], 404);
    }

    /**
     * Server error response
     *
     * @param string $message
     * @param array $errors
     * @return JsonResponse
     */
    public function sendServerError(string $message = 'Server Error', array $errors = []): JsonResponse
    {
        return $this->sendError($message, $errors, 500);
    }

    /**
     * Success response without data
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendSuccessMessage(string $message, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $code);
    }

    /**
     * Paginated response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public function sendPaginatedResponse($data, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
        ]);
    }

    /**
     * Created response (201)
     *
     * @param mixed $result
     * @param string $message
     * @return JsonResponse
     */
    public function sendCreated($result, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->sendResponse($result, $message, 201);
    }

    /**
     * Updated response
     *
     * @param mixed $result
     * @param string $message
     * @return JsonResponse
     */
    public function sendUpdated($result, string $message = 'Resource updated successfully'): JsonResponse
    {
        return $this->sendResponse($result, $message, 200);
    }

    /**
     * Deleted response
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendDeleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return $this->sendSuccessMessage($message, 200);
    }

    /**
     * No content response (204)
     *
     * @return JsonResponse
     */
    public function sendNoContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}