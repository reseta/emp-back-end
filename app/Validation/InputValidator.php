<?php

namespace App\Validation;

use Valitron\Validator;

class InputValidator
{
    /**
    * List of returned errors in case of a failing
    *
    * @var array
    */
    public static array $errors = [];

    /**
     * Set the user subscription constraints
     *
     * @param $model
     * @param array $rules
     * @param array $inputData
     * @param array $fields
     * @return bool
     */
    public static function validate($model, array $rules = [], array $inputData = [], array $fields = []): bool
    {
        $model = $model::query();
        $validator = new Validator($inputData);

        // add rules to validator
        foreach ($rules as $key => $value) {
            if (is_array($value)) {
                $validator->rules([$key => $value]);
            } else {
                $validator->rule($key, $value);
            }
        }

        if($validator->validate()) {
            // check for unique field into db
            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $model->where($field, $inputData[$field]);
                }

                $result = $model->get();
                if ($result->count() > 0) {
                    self::$errors = ['fields' => "should be unique"];

                    return false;
                }
            }
            return true;
        } else {
            self::$errors = $validator->errors();

            return false;
        }
    }
}
