<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum ProductFieldType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Number = 'number';
    case Select = 'select';
    case Boolean = 'boolean';
    case File = 'file';
    case Date = 'date';
}
