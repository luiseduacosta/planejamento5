<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;

class FormHelper extends CakeFormHelper
{
    public function control(string $fieldName, array $options = []): string
    {
        $type = $this->bootstrapType($options);
        $inputClass = $this->defaultInputClass($type);

        if ($inputClass !== '' && !isset($options['class'])) {
            $options['class'] = $inputClass;
        }

        if (($options['label'] ?? null) !== false) {
            $labelClass = \in_array($type, ['checkbox', 'radio'], true) ? 'form-check-label' : 'form-label';
            $options['label'] = $this->normalizeLabelOption($options['label'] ?? null, $labelClass);
        }

        return parent::control($fieldName, $options);
    }

    public function button(string $title, array $options = []): string
    {
        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-primary';
        } elseif (!str_contains((string)$options['class'], 'btn')) {
            $options['class'] = $this->mergeClasses((string)$options['class'], 'btn btn-primary');
        }

        return parent::button($title, $options);
    }

    private function bootstrapType(array $options): string
    {
        if (isset($options['type'])) {
            return strtolower((string)$options['type']);
        }

        if (isset($options['options'])) {
            return 'select';
        }

        if (isset($options['rows']) || isset($options['cols'])) {
            return 'textarea';
        }

        return 'text';
    }

    private function defaultInputClass(string $type): string
    {
        return match ($type) {
            'select' => 'form-select',
            'textarea' => 'form-control',
            'checkbox', 'radio' => 'form-check-input',
            default => 'form-control',
        };
    }

    private function normalizeLabelOption(mixed $label, string $labelClass): array|string
    {
        if (\is_array($label)) {
            $label['class'] = $this->mergeClasses((string)($label['class'] ?? ''), $labelClass);

            return $label;
        }

        if (\is_string($label) && $label !== '') {
            return [
                'text' => $label,
                'class' => $labelClass,
            ];
        }

        return ['class' => $labelClass];
    }

    private function mergeClasses(string $existing, string $classesToAdd): string
    {
        $classList = preg_split('/\s+/', trim("{$existing} {$classesToAdd}")) ?: [];
        $classList = array_filter($classList, static fn(string $class): bool => $class !== '');

        return implode(' ', array_values(array_unique($classList)));
    }
}
