<?php

namespace App\Exceptions;

use App\Notifications\ErrorNotification;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param  Throwable $exception
     * @return bool
     */
    public function shouldReport(Throwable $exception)
    {
        if(config('app.env') == "production" &&  parent::shouldReport($exception)){
            return true;
        }

        return false;
    }

    /**
     * Report or log an exception.
     *
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        if ($this->shouldReport($exception)) {
            $this->sendErrorNotificationToSlack($exception);
        }
    }

    /**
     * Send error notification to Slack.
     *
     * @param  Throwable $exception
     * @return void
     */
    protected function sendErrorNotificationToSlack(Throwable $exception)
    {
        $errorMessage = $exception->getMessage();
        $report = [
            "environment" => app()->environment(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "code" => $exception->getCode(),
            "trace" => $exception->getTraceAsString(),
        ];

        try {
            Notification::route('slack', config('services.slack.webhook_url.error'))
                ->notify(new ErrorNotification($errorMessage, $report));
        } catch (\Exception $e) {
            Log::error('Failed to send error notification to Slack: ' . $e->getMessage());
        }
    }
}
