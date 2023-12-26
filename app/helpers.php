<?php

use App\Exceptions\ModelSaveException;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('save_model')) {
    /**
     * Save a model safely, raising an exception if it fails due to an event callback
     * returning false such as updating, creating, saving, etc.
     *
     * @throws ModelSaveException
     */
    function save_model(Model $model, array $fillWith = []): void
    {
        if (count(func_get_args()) > 1) {
            $model->fill($fillWith);
        }

        if ($model->save() === false) {
            throw new ModelSaveException($model);
        }
    }
}

if (! function_exists('delete_model')) {
    /**
     * Delete a model safely, raising an exception if it fails due to an event callback
     * returning false such as deleting.
     *
     * @throws ModelSaveException
     */
    function delete_model(Model $model): void
    {
        if ($model->delete() === false) {
            throw new ModelSaveException($model);
        }
    }
}