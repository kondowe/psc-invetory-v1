<?php
/**
 * Validator Class
 *
 * Input validation with rules
 */

class Validator
{
    private $errors = [];
    private $data = [];

    /**
     * Validate data against rules
     *
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @return bool
     */
    public function validate($data, $rules)
    {
        $this->data = $data;
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            $fieldRules = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);

            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Apply validation rule
     *
     * @param string $field Field name
     * @param mixed $value Field value
     * @param string $rule Rule
     */
    private function applyRule($field, $value, $rule)
    {
        // Parse rule and parameter (e.g., "min:5" => rule="min", param="5")
        $parts = explode(':', $rule, 2);
        $ruleName = $parts[0];
        $ruleParam = $parts[1] ?? null;

        $fieldLabel = ucfirst(str_replace('_', ' ', $field));

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "{$fieldLabel} is required");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "{$fieldLabel} must be a valid email address");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "{$fieldLabel} must be numeric");
                }
                break;

            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "{$fieldLabel} must be an integer");
                }
                break;

            case 'min':
                if (!empty($value)) {
                    if (is_numeric($value) && $value < $ruleParam) {
                        $this->addError($field, "{$fieldLabel} must be at least {$ruleParam}");
                    } elseif (is_string($value) && strlen($value) < $ruleParam) {
                        $this->addError($field, "{$fieldLabel} must be at least {$ruleParam} characters");
                    }
                }
                break;

            case 'max':
                if (!empty($value)) {
                    if (is_numeric($value) && $value > $ruleParam) {
                        $this->addError($field, "{$fieldLabel} must not exceed {$ruleParam}");
                    } elseif (is_string($value) && strlen($value) > $ruleParam) {
                        $this->addError($field, "{$fieldLabel} must not exceed {$ruleParam} characters");
                    }
                }
                break;

            case 'between':
                if (!empty($value) && is_numeric($value)) {
                    list($min, $max) = explode(',', $ruleParam);
                    if ($value < $min || $value > $max) {
                        $this->addError($field, "{$fieldLabel} must be between {$min} and {$max}");
                    }
                }
                break;

            case 'in':
                if (!empty($value)) {
                    $allowedValues = explode(',', $ruleParam);
                    if (!in_array($value, $allowedValues)) {
                        $this->addError($field, "{$fieldLabel} must be one of: " . implode(', ', $allowedValues));
                    }
                }
                break;

            case 'unique':
                if (!empty($value)) {
                    // Format: unique:table,column,except_id
                    $params = explode(',', $ruleParam);
                    $table = $params[0];
                    $column = $params[1] ?? $field;
                    $exceptId = $params[2] ?? null;

                    $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
                    $sqlParams = [$value];

                    if ($exceptId !== null) {
                        $sql .= " AND id != ?";
                        $sqlParams[] = $exceptId;
                    }

                    $result = Database::fetchOne($sql, $sqlParams);
                    if ($result['count'] > 0) {
                        $this->addError($field, "{$fieldLabel} is already taken");
                    }
                }
                break;

            case 'match':
                $matchField = $ruleParam;
                if ($value !== ($this->data[$matchField] ?? null)) {
                    $matchLabel = ucfirst(str_replace('_', ' ', $matchField));
                    $this->addError($field, "{$fieldLabel} must match {$matchLabel}");
                }
                break;

            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "{$fieldLabel} must be a valid URL");
                }
                break;

            case 'date':
                if (!empty($value)) {
                    $date = date_create($value);
                    if (!$date) {
                        $this->addError($field, "{$fieldLabel} must be a valid date");
                    }
                }
                break;

            case 'alpha':
                if (!empty($value) && !preg_match('/^[a-zA-Z]+$/', $value)) {
                    $this->addError($field, "{$fieldLabel} must contain only letters");
                }
                break;

            case 'alphanumeric':
                if (!empty($value) && !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                    $this->addError($field, "{$fieldLabel} must contain only letters and numbers");
                }
                break;

            case 'regex':
                if (!empty($value) && !preg_match($ruleParam, $value)) {
                    $this->addError($field, "{$fieldLabel} format is invalid");
                }
                break;

            case 'greater_than':
                if (!empty($value) && is_numeric($value)) {
                    $compareValue = $this->data[$ruleParam] ?? 0;
                    if ($value <= $compareValue) {
                        $compareLabel = ucfirst(str_replace('_', ' ', $ruleParam));
                        $this->addError($field, "{$fieldLabel} must be greater than {$compareLabel}");
                    }
                }
                break;

            case 'less_than':
                if (!empty($value) && is_numeric($value)) {
                    $compareValue = $this->data[$ruleParam] ?? 0;
                    if ($value >= $compareValue) {
                        $compareLabel = ucfirst(str_replace('_', ' ', $ruleParam));
                        $this->addError($field, "{$fieldLabel} must be less than {$compareLabel}");
                    }
                }
                break;
        }
    }

    /**
     * Add validation error
     *
     * @param string $field Field name
     * @param string $message Error message
     */
    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Get all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get errors for a specific field
     *
     * @param string $field Field name
     * @return array
     */
    public function getFieldErrors($field)
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Get first error for a field
     *
     * @param string $field Field name
     * @return string|null
     */
    public function getFirstError($field)
    {
        $errors = $this->getFieldErrors($field);
        return $errors[0] ?? null;
    }

    /**
     * Check if validation has errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Get all error messages as flat array
     *
     * @return array
     */
    public function getAllErrorMessages()
    {
        $messages = [];
        foreach ($this->errors as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $messages[] = $error;
            }
        }
        return $messages;
    }
}
