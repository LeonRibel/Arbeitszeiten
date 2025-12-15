<?php

namespace App\Enum;

enum FehlzeitenStatus: string {

    case ABGELEHNT = 'eingereicht';
    case GENEHMIGT = 'nicht eingereicht';
}