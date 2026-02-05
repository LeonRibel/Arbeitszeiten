<?php

namespace App\Enums;

enum UrlaubsStatus: string
{
    case ANGEFRAGT = 'angefragt';
    case GENEHMIGT = 'genehmigt';
    case ABGELEHNT = 'abgelehnt';
}
