<?php

namespace App\Controllers;

use App\Helpers\Client;
use App\Helpers\Log;
use App\Helpers\RateLimit;
use App\Models\AthleteRepository;

class SearchController
{
    public function index()
    {
        $query = trim((string) ($_GET['q'] ?? ''));
        $year  = trim((string) ($_GET['rok'] ?? ''));
        $error = '';
        $rows  = [];
        $repository = new AthleteRepository();
        $years = $repository->verifiedYears();

        if ($query !== '' || $year !== '') {
            $key = 'athlete-search:' . Client::anonymizedIp();
            if (! RateLimit::check($key, 30, 3600)) {
                Log::security('hledání závodníků: překročen limit pokusů');
                $error = 'Limit hledání byl dočasně překročen. Zkuste to později.';
            } elseif ($query !== '' && mb_strlen($query) < 2) {
                $error = 'Zadejte alespoň dva znaky jména nebo oddílu.';
            } elseif ($year !== '' && (! ctype_digit($year) || ! in_array((int) $year, $years, true))) {
                $error = 'Vyberte platný ročník.';
            } else {
                $rows = $repository->search($query, $year === '' ? null : (int) $year);
                Log::security('hledání závodníků: rok=' . $year . ' počet=' . count($rows));
            }
        }

        $title       = 'Hledat závodníka | Vodácká 30';
        $description = 'Veřejné hledání závodníků v ověřených výsledcích Vodácké 30.';
        $view        = APP_ROOT . '/app/Views/search.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
