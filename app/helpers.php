<?php

use App\Exceptions\ModelSaveException;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('save_model')) {
    /**
     * Save a model safely, raising an exception if it fails due to an event callback
     * returning false such as updating, creating, saving, etc.
     *
     * @template T of Model
     *
     * @param  T  $model
     * @param  array<string, mixed>  $fillWith
     * @return T
     *
     * @throws ModelSaveException
     */
    function save_model(Model $model, array $fillWith = []): Model
    {
        if (count(func_get_args()) > 1) {
            $model->fill($fillWith);
        }

        if ($model->save() === false) {
            throw new ModelSaveException($model);
        }

        return $model;
    }
}

if (! function_exists('delete_model')) {
    /**
     * Delete a model safely, raising an exception if it fails due to an event callback
     * returning false such as deleting.
     *
     * @template T of Model
     *
     * @param  T  $model
     * @return T
     *
     * @throws ModelSaveException
     */
    function delete_model(Model $model): Model
    {
        if ($model->delete() === false) {
            throw new ModelSaveException($model);
        }

        return $model;
    }
}
