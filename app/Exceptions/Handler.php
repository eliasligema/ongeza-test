<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * Report or log an exception.
     *
     * @param Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request $request
     * @param Exception                 $exception
     *
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if($this->ajax($request)) {
            Log::channel('error')->error($exception);

            $code = (method_exists($exception, 'getStatusCode'))? $exception->getStatusCode(): 500;
            $message = (!empty($exception->getMessage()))? $exception->getMessage(): "Not found";

            return response()->json([
                "code" => $code, 
                "message" => $message
            ], $code); 
        } elseif(config('app.env') == 'production') {
            $layout = 'website';
            if($request->is('admin/*')) {
                $layout = 'admin';
            }
            
            switch ($exception->getStatusCode()) {
                // not authorized
                case '403':
                    return new Response(view('error.403', ['layout'=>$layout]));
                    break;
    
                // not found
                case '404':
                    return new Response(view('error.404', ['layout'=>$layout]));
                    break;
    
                // internal error
                case '500':
                    return new Response(view('error.500', ['layout'=>$layout]));
                    break;

                default:
                    return new Response(view('error.400', [
                        'layout'=>$layout,
                        "code"=>$exception->getStatusCode(),
                        "message"=>""
                    ]));
            }
        }


        return parent::render($request, $exception);
    }

    private function ajax($request){
        if($request->expectsJson() || $request->is('api') || $request->is('api/*')) {
            return true;
        } elseif($request->isJson()) {
            return true;
        }

        return false;
    }
}
