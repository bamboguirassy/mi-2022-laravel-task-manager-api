<?php

namespace App\Helper;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\Validator;
use Throwable;
use Illuminate\Support\Str;

class CustomResponse implements Arrayable
{
    private $hasError;
    private $content;
    private $validationFails;
    private $isException;

    private function __construct($content, $hasError = false, $validationFails = false, $isException = false)
    {
        $this->hasError = $hasError;
        $this->validationFails = $validationFails;
        $this->content = $content;
        $this->isException = $isException;
    }

    public function toArray()
    {
        return [
            'hasError' => $this->hasError,
            'validationFails' => $this->validationFails,
            'isException' => $this->isException,
             'content' => $this->content
        ];
    }

    // si hasError = false, $validationFails = false,
    // content = toute reponse correcte
    static function buildCorrectResponse($content)
    {
        return new CustomResponse($content);
    }

    // si hasError=true & validationFails=true
    // content = tableau de validation
    static function buildValidationResponse(Validator $validators)
    {
        return new CustomResponse($validators->errors(), true, true);
    }

    // si hasError=true & validationFails=false
    // content = chaine de caractère (message d'erreur)
    static function buildCustomErrorResponse(String $message)
    {
        return new CustomResponse($message, true, false);
    }

    // exception capturée entre ici...
    static function buildExceptionResponse(Throwable $throwable)
    {
        if(Str::contains($throwable->getMessage(), 'SQLSTATE')) {
            return new CustomResponse("Erreur SQL, contactez l'administrateur...", true, false, true);
        }
        return new CustomResponse($throwable->getMessage(), true, false, true);
    }
}
