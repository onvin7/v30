<?php

namespace App\Enums;

enum RaceEditionStatus: string
{
    case Draft = 'draft';
    case Planned = 'planned';
    case Published = 'published';
    case Archived = 'archived';
}
