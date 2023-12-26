<?php declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class ModelSaveException extends Exception
{
    public function __construct(public Model $model)
    {
        $modelClass = $model::class;

        parent::__construct("Failed to save model {$modelClass}");
    }
}