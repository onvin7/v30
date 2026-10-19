<?php

namespace App\Controllers;

class GalleryController
{
    private const IMG_BASE = 'https://coresg-normal.trae.ai/api/ide/v1/text_to_image';

    /**
     * Sestaví URL obrázku z textového promptu a požadovaného rozměru.
     */
    private function img(string $prompt, string $size): string
    {
        return self::IMG_BASE . '?prompt=' . rawurlencode($prompt) . '&image_size=' . $size;
    }

    public function index()
    {
        // Mock data – alba podle ročníků s náhledovými fotografiemi.
        $alba = [
            [
                'rok' => 2026,
                'nazev' => 'Vodácká 30 – ročník 2026',
                'popis' => 'Fotografie z dětských závodů 26. ročníku u chaty Bedřichovka.',
                'fotograf' => 'Jakub Šilar',
                'odkaz' => 'https://eu.zonerama.com/JakubSilar/Album/14557825',
                'nahled' => $this->img(
                    'winter cross-country skiing race mass start on a snowy forest trail, Nordic skiers in colorful race bibs, Orlicke hory Czech mountains, cold morning light, professional sports photography',
                    'landscape_4_3'
                ),
            ],
            [
                'rok' => 2019,
                'nazev' => 'Vodácká 30 – ročník 2019',
                'popis' => 'Zimní běžkařský závod v Orlických horách.',
                'fotograf' => '—',
                'odkaz' => 'https://vodacka30.cz/index.php?menu=4',
                'nahled' => $this->img(
                    'cross-country skier gliding through a snowy spruce forest on a winter trail, Czech highlands, soft daylight, sports photography',
                    'landscape_4_3'
                ),
            ],
            [
                'rok' => 2018,
                'nazev' => 'Vodácká 30 – ročník 2018',
                'popis' => 'Zimní běžkařský závod v Orlických horách.',
                'fotograf' => '—',
                'odkaz' => 'https://vodacka30.cz/index.php?menu=4',
                'nahled' => $this->img(
                    'classic cross-country skier climbing a steep snowy hill, winter forest, effort and determination, cold blue light, sports photography',
                    'landscape_4_3'
                ),
            ],
            [
                'rok' => 2017,
                'nazev' => 'Vodácká 30 – ročník 2017',
                'popis' => 'Zimní běžkařský závod v Orlických horách.',
                'fotograf' => '—',
                'odkaz' => 'https://vodacka30.cz/index.php?menu=4',
                'nahled' => $this->img(
                    'panoramic snowy mountain ridge in Orlicke hory, frozen trees, deep winter, clear blue sky, landscape photography',
                    'landscape_4_3'
                ),
            ],
        ];

        // Fotografie zachycující průběh ročníku 2026.
        $fotky = [
            [
                'src' => $this->img(
                    'children cross-country skiing on a snowy trail, kids winter sports race, colorful hats and bibs, forest background, cheerful atmosphere, sports photography',
                    'square'
                ),
                'alt' => 'Děti na trati dětí běžeckého závodu',
            ],
            [
                'src' => $this->img(
                    'cross-country skier in classic technique on a groomed winter track, snow spray, spruce forest, dynamic motion, sports photography',
                    'square'
                ),
                'alt' => 'Závodník klasickou technikou na upravené trati',
            ],
            [
                'src' => $this->img(
                    'wooden mountain chalet covered in snow in a winter forest, cozy alpine hut with warm lit windows, Czech countryside, evening',
                    'square'
                ),
                'alt' => 'Chata Bedřichovka v zimě',
            ],
            [
                'src' => $this->img(
                    'skiers crossing the finish line at a winter cross-country race, cheering crowd, snow, celebration, sports photography',
                    'square'
                ),
                'alt' => 'Závodníci v cíli závodu',
            ],
            [
                'src' => $this->img(
                    'aid station with hot drinks in a snowy forest during a winter race, checkpoint banner, volunteers, winter sports event',
                    'square'
                ),
                'alt' => 'Občerstvení a kontrolní bod na trati',
            ],
            [
                'src' => $this->img(
                    'evening award ceremony after a winter cross-country race, people gathered by a mountain chalet, warm lights, snow, festive mood',
                    'square'
                ),
                'alt' => 'Vyhlášení výsledků u chaty po závodě',
            ],
        ];

        $title = 'Galerie | Vodácká 30';
        $view  = APP_ROOT . '/app/Views/gallery.php';

        require APP_ROOT . '/app/Views/layout.php';
    }
}
