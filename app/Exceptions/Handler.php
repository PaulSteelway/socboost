<?php

namespace App\Exceptions;

use App\Jobs\SendLogsJob;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Throwable $exception
     * @return mixed|void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($request->is('api/*')) {
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json(['data' => null, 'error' => 'Not Found'], 404);
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['data' => null, 'error' => 'Unauthorized'], 401);
            }
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return redirect()->route('customer.main')->with('errors', __('Unauthorized access'));
        }

        $message = $exception->getMessage();

        $stopWords = [
            'Unauthenticated',
            'invalid_grant',
        ];

        foreach ($stopWords as $stopWord) {
            if (preg_match('/'.$stopWord.'/', $message)) {
                return parent::render($request, $exception);
            }
        }

        if (!empty($exception->getMessage()) && !preg_match('/invalid/', $exception->getMessage())) {
            if ($exception->getMessage() == 'CSRF token mismatch.') {
                \Log::info('Началась какая-то ерунда - SendLogsJob ' . $exception->getMessage());
                \Log::critical(request()->ip());
            } else {
              if (config('app.env') !== 'development') {
                SendLogsJob::dispatch($message)->onQueue(getSupervisorName().'-low')->delay(0);
              }
            }
        }
        return parent::render($request, $exception);
    }
}
