<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * 例外をHTTPレスポンスとしてレンダリング
     */
    public function render($request, Throwable $exception)
    {
        // APIリクエストの場合はJSONでエラーを返す
        if ($request->expectsJson() || $request->is('api/*')) {
            // 認証エラーは必ず401で返す
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'status' => 401
                ], 401);
            }
            // 404 Not Found
            if ($this->isHttpException($exception) && $exception->getStatusCode() === 404) {
                return response()->json([
                    'message' => 'Not Found',
                    'status' => 404
                ], 404);
            }
            // その他の例外
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
        }
        // 通常は親の処理
        return parent::render($request, $exception);
    }

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 未認証時のレスポンスをカスタマイズ
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'status' => 401
            ], 401);
        }
        return redirect('/login');
    }
}
