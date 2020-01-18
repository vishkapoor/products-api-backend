<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $resource)
    {

        $transformedInput = [];

        if($request->file('picture')) {
            $transformedInput[$resource::originalAttribute('picture')] = $request->file('picture');
        }

        foreach($request->request->all()  as $input => $value) {
            $transformedInput[$resource::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);
        $response = $next($request);

       if(isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();
            $transformedErrors = [];
            foreach($data->error as $field => $error) {
                $transformedField = $resource::transformedAttribute($field);
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }
            $data->error = $transformedErrors;
            $response->setData($data);
       }

       return $response;
    }
}
