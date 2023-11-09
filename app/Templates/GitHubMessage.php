<?php

namespace App\Templates;

use SoDe\Extend\Text;

class GitHubMessage
{
    private ?string $string = null;
    public string $contact = '';
    public string $phone = '';
    public string $commiter = '';
    public string $repository = '';
    public string $branch = '';
    public string $owner = '';
    public string $commit = '';
    public string $added = '';
    public string $modified = '';
    public string $removed = '';
    public string $username = '';
    public string $commit_id = '';

    public function __construct()
    {
        $this->string = 'Hola *{contact}*, he realizado un cambio en la rama *{branch}* del repositorio *{repository}*.' . Text::lineBreak(2) .
            'Commit: *{commit}*' . Text::lineBreak(2) .
            'Agregué:' . Text::lineBreak() .
            '{added}' . Text::lineBreak(2) .
            'Modifiqué:' . Text::lineBreak() .
            '{modified}' . Text::lineBreak(2) .
            'Eliminé:' . Text::lineBreak() .
            '{removed}';
    }

    public function get(): string
    {
        $string = str_replace(
            [
                '{contact}',
                '{phone}',
                '{commiter}',
                '{repository}',
                '{branch}',
                '{owner}',
                '{commit}',
                '{added}',
                '{modified}',
                '{removed}',
                '{username}',
                '{commit_id}',
            ],
            [
                $this->contact,
                $this->phone,
                $this->commiter,
                $this->repository,
                $this->branch,
                $this->owner,
                $this->commit,
                $this->added,
                $this->modified,
                $this->removed,
                $this->username,
                $this->commit_id,
            ],
            $this->string
        );
        return $string;
    }
}
