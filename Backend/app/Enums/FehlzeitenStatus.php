<?php

namespace App\Enums;

enum FehlzeitenStatus: string
{
    case OFFEN = 'nicht eingereicht';
    case GENEHMIGT = 'eingereicht';
}
