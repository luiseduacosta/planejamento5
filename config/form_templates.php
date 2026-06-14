<?php
declare(strict_types=1);

return [
    'button' => '<button{{attrs}}>{{text}}</button>',
    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    'checkboxFormGroup' => '<div class="form-check">{{input}}{{label}}</div>',
    'checkboxWrapper' => '<div class="form-check">{{input}}{{label}}</div>',
    'error' => '<div class="invalid-feedback d-block" id="{{id}}">{{content}}</div>',
    'errorList' => '<div>{{content}}</div>',
    'errorItem' => '<div>{{text}}</div>',
    'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
    'formEnd' => '</form>',
    'formGroup' => '{{label}}{{input}}',
    'formStart' => '<form{{attrs}}>',
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}>',
    'inputContainer' => '<div class="mb-3 {{required}}">{{content}}</div>',
    'inputContainerError' => '<div class="mb-3 {{required}}">{{content}}{{error}}</div>',
    'label' => '<label{{attrs}}>{{text}}</label>',
    'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}} {{text}}</label>',
    'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
    'radioWrapper' => '<div class="form-check">{{input}}{{label}}</div>',
    'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
    'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
    'submitContainer' => '<div class="mt-3">{{content}}</div>',
    'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
    'errorClass' => 'is-invalid',
];
