<?php

namespace App\Forms;

use App\Exceptions\FormException;
use App\Libraries\MY;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

abstract class CommonForm
{
    protected $user_id = null;
    protected $user;
    protected $input_data;
    protected $rules = [];
    protected $names = [];
    protected array $fieldNames = [];
    protected $create_and_edit = true;
    protected $policy_class = null;

    protected static $testing = false;
    protected static $test_user = null;
    protected static $rollback = false;

    const TYPE_STR = 'str';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_DATE = 'date';
    const TYPE_DATE_MYSQL = 'date_mysql';
    const TYPE_ARR = 'array';
    const TYPE_ID_ARR = 'array_ids';
    const TYPE_FILE = 'file';

    const DOCUMENT_FILE = 'pdf,jpg,jpeg,png,bmp,doc,docx,xls,xlsx';

    public function __construct($input_data)
    {
        $this->input_data = $input_data;

        if (self::$test_user) {
            $this->user_id = self::$test_user->user_id;
            $this->user = self::$test_user;
        } else {
            $this->user_id = auth()->check() ? auth()->user()->user_id : null;
            $this->user = auth()->check() ? auth()->user() : null;
        }
    }
    abstract public function getSuccessMessage($record);
    abstract public function setValidationRules();
    abstract public function processInput();

    public static function setTesting($user = null)
    {
        self::$testing = true;

        if ($user) {
            self::$test_user = $user;
        }
    }
    public static function setRollback($value = true)
    {
        self::$rollback = $value;
    }
    public function process()
    {
        $output = [
            'success' => false,
            'errors' => null,
            'validator' => null,
            'message' => null,
            'record' => null,
        ];

        if (Arr::get($this->input_data, 'testing_rollback')) {
            self::setRollback();
        }

        $this->setValidationRules();

        $validator = Validator::make($this->input_data, $this->rules, $this->names);
        $validator->setAttributeNames($this->fieldNames);

        if ($validator->fails()) {
            $output['validator'] = $validator;
            $output['errors'] = $validator->errors()->all();

            return $output;
        } else {
            DB::beginTransaction();

            try {
                $record = $this->processInput();

                if (self::$rollback) {
                    DB::rollBack();
                } else {
                    DB::commit();
                }

                $output['success'] = true;
                $output['message'] = $this->getSuccessMessage($record);
                $output['record'] = $record;
            } catch (FormException $e) {
                $output['errors'] = $e->getMessage();
            }
        }

        return $output;
    }
    public function read($field, $type = '', $allow_null = false)
    {
        $value = Arr::get($this->input_data, $field, '');

        if ($type == self::TYPE_STR || !$type) {
            $value = trim('' . $value);

            if ($allow_null) {
                $value = $value ? $value : null;
            }
        } elseif ($type == self::TYPE_INT) {
            if ($allow_null) {
                $value = $value ? (int) $value : null;
            } else {
                $value = (int) $value;
            }
        } elseif ($type == self::TYPE_FLOAT) {
            if ($allow_null) {
                $value = $value ? (float) $value : null;
            } else {
                $value = (float) $value;
            }
        } elseif ($type == self::TYPE_DATE) {
            $value = $value ? MY::parseDate($value) : null;
        } elseif ($type == self::TYPE_DATE_MYSQL) {
            $value = $value ? Carbon::parse($value) : null;
        } elseif ($type == self::TYPE_ARR) {
            $value = $value && is_array($value) ? $value : [];
        } elseif ($type == self::TYPE_ID_ARR) {
            $new_value = [];

            if (is_array($value)) {
                foreach ($value as $item) {
                    if ((int) $item) {
                        $new_value[] = (int) $item;
                    }
                }
            }

            $value = $new_value;
        } elseif ($type == self::TYPE_FILE) {
            $value = $value ? $value : null;
        } elseif ($type == self::TYPE_BOOLEAN) {
            $value = (bool) $value;
        }

        return $value;
    }
}
