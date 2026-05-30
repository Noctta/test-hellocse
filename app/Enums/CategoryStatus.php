<?php

namespace App\Enums;

enum CategoryStatus: string
{
    case ONLINE   = 'online';
    case DISABLED = 'disabled';
    case ARCHIVED = 'archived';
}