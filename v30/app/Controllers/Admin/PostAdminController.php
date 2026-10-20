<?php

namespace App\Controllers\Admin;

use App\Helpers\Log;
use App\Models\PostRepository;

/**
 * Správa příspěvků (novinek) v administraci.
 */
class PostAdminController
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    /**
     * Seznam všech příspěvků.
     */
    public function index()
    {
        $prispevky    = $this->repo->all();
        $adminTitle   = 'Příspěvky';
        $adminSection = 'prispevky';
        $adminView    = APP_ROOT . '/app/Views/admin/posts/index.php';

        require APP_ROOT . '/app/Views/admin/layout.php';
    }

    /**
     * Formulář pro nový příspěvek.
     */
    public function create()
    {
        $prispevek    = ['id' => null, 'nadpis' => '', 'datum' => date('Y-m-d'), 'perex' => '', 'text' => '', 'zverejneno' => 1];
        $errors       = flash_get('errors', []);
        $adminTitle   = 'Nový příspěvek';
        $adminSection = 'prispevky';
        $adminView    = APP_ROOT . '/app/Views/admin/posts/form.php';

        require APP_ROOT . '/app/Views/admin/layout.php';
    }

    /**
     * Uložení nového příspěvku.
     */
    public function store()
    {
        // CSRF – bez platného tokenu se nic neukládá.
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Příspěvek se nepodařilo uložit. Zkuste to prosím znovu.');
            redirect('/admin/prispevky/novy');
        }

        [$vstup, $errors] = $this->validuj($_POST);

        if ($errors !== []) {
            flash_set('errors', $errors);
            flash_set('old', $vstup);
            redirect('/admin/prispevky/novy');
        }

        $id = $this->repo->create($vstup);
        Log::admin('Vytvořen příspěvek #' . $id . ' ("' . $vstup['nadpis'] . '")');
        flash_set('old', []);
        flash_set('success', 'Příspěvek byl vytvořen.');

        redirect('/admin');
    }

    /**
     * Formulář pro úpravu příspěvku.
     */
    public function edit(string $id)
    {
        $prispevek = $this->repo->find((int) $id);
        if ($prispevek === null) {
            flash_set('error', 'Příspěvek nebyl nalezen.');
            redirect('/admin');
        }

        $errors       = flash_get('errors', []);
        $adminTitle   = 'Upravit příspěvek';
        $adminSection = 'prispevky';
        $adminView    = APP_ROOT . '/app/Views/admin/posts/form.php';

        require APP_ROOT . '/app/Views/admin/layout.php';
    }

    /**
     * Uložení úprav příspěvku.
     */
    public function update(string $id)
    {
        $id = (int) $id;

        // CSRF – bez platného tokenu se nic neukládá.
        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Příspěvek se nepodařilo uložit. Zkuste to prosím znovu.');
            redirect('/admin/prispevky/' . $id . '/upravit');
        }

        if ($this->repo->find($id) === null) {
            flash_set('error', 'Příspěvek nebyl nalezen.');
            redirect('/admin');
        }

        [$vstup, $errors] = $this->validuj($_POST);

        if ($errors !== []) {
            flash_set('errors', $errors);
            flash_set('old', $vstup);
            redirect('/admin/prispevky/' . $id . '/upravit');
        }

        $this->repo->update($id, $vstup);
        Log::admin('Upraven příspěvek #' . $id . ' ("' . $vstup['nadpis'] . '")');
        flash_set('old', []);
        flash_set('success', 'Příspěvek byl uložen.');

        redirect('/admin');
    }

    /**
     * Smazání příspěvku.
     */
    public function delete(string $id)
    {
        $id = (int) $id;

        if (! csrf_verify($_POST['_csrf'] ?? null)) {
            flash_set('error', 'Příspěvek se nepodařilo smazat.');
            redirect('/admin');
        }

        if ($this->repo->delete($id)) {
            Log::admin('Smazán příspěvek #' . $id);
            flash_set('success', 'Příspěvek byl smazán.');
        } else {
            flash_set('error', 'Příspěvek nebyl nalezen.');
        }

        redirect('/admin');
    }

    /**
     * Validace formuláře. Vrací dvojici [očištěný vstup, chyby].
     *
     * @param array<string, mixed> $vstup
     * @return array{0: array<string, mixed>, 1: array<string, string>}
     */
    private function validuj(array $vstup): array
    {
        $data = [
            'nadpis'     => trim((string) ($vstup['nadpis'] ?? '')),
            'datum'      => trim((string) ($vstup['datum'] ?? '')),
            'perex'      => trim((string) ($vstup['perex'] ?? '')),
            'text'       => trim((string) ($vstup['text'] ?? '')),
            'zverejneno' => (string) ($vstup['zverejneno'] ?? '0') === '1' ? 1 : 0,
        ];

        $errors = [];

        if ($data['nadpis'] === '') {
            $errors['nadpis'] = 'Zadejte nadpis příspěvku.';
        } elseif (mb_strlen($data['nadpis']) > 120) {
            $errors['nadpis'] = 'Nadpis může mít nejvýše 120 znaků.';
        }

        $d = \DateTimeImmutable::createFromFormat('Y-m-d', $data['datum']);
        if ($data['datum'] === '' || $d === false || $d->format('Y-m-d') !== $data['datum']) {
            $errors['datum'] = 'Zadejte platné datum ve formátu RRRR-MM-DD.';
        }

        if (mb_strlen($data['perex']) > 300) {
            $errors['perex'] = 'Perex může mít nejvýše 300 znaků.';
        }

        return [$data, $errors];
    }
}
