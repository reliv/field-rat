<?php

namespace Reliv\FieldRat\Api;

use Reliv\ArrayProperties\Property;
use Reliv\ValidationRat\Model\ValidationResult;
use Reliv\ValidationRat\Model\ValidationResultBasic;
use Reliv\ValidationRat\Model\ValidationResultFields;
use Reliv\ValidationRat\Model\ValidationResultFieldsBasic;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildFieldRatValidationResult
{
    const OPTION_FIELD_TYPE = 'field-type';
    const OPTION_FIELD_CONFIG = 'field-config';

    const DETAIL_FIELD_TYPE = 'field-rat-field-type';
    const DETAIL_FIELD_CONFIG = 'field-rat-field-config';

    /**
     * @param ValidationResult $validationResult
     * @param array            $options
     *
     * @return ValidationResult
     */
    public static function invoke(
        ValidationResult $validationResult,
        array $options = []
    ): ValidationResult {
        $fieldConfig = Property::getArray(
            $options,
            static::OPTION_FIELD_CONFIG
        );

        $fieldType = Property::getArray(
            $options,
            static::OPTION_FIELD_TYPE
        );

        if (empty($fieldConfig)) {
            return $validationResult;
        }

        $details = $validationResult->getDetails();
        $details[static::DETAIL_FIELD_TYPE] = $fieldType;
        $details[static::DETAIL_FIELD_CONFIG] = $fieldConfig;

        if ($validationResult instanceof ValidationResultFields) {
            new ValidationResultFieldsBasic(
                $validationResult->isValid(),
                $validationResult->getCode(),
                $validationResult->getFieldResults(),
                $details
            );
        }

        new ValidationResultBasic(
            $validationResult->isValid(),
            $validationResult->getCode(),
            $details
        );
    }
}
