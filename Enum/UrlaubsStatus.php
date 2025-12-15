<?php

namespace App\Enum;

enum UrlaubsStatus: string {
    case ANGEFRAGT = 'angefragt';
    case ABGELEHNT = 'abgelehnt';
    case GENEHMIGT = 'genehmigt';
}