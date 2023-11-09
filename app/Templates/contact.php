<?php

namespace App\Templates;

use SoDe\Extend\JSON;

class Contact
{
    private string $string = '{"messaging_product":"whatsapp","to":"{destinataryPhone}","type":"template","template":{"name":"dedicated_form_contact","language":{"code":"es"},"components":[{"type":"header","parameters":[{"type":"text","text":"{page}"}]},{"type":"body","parameters":[{"type":"text","text":"{destinataryName}"},{"type":"text","text":"{namesUser}"},{"type":"text","text":"{fullPage}"},{"type":"text","text":"{phone}"},{"type":"text","text":"{email}"},{"type":"text","text":"{message}"}]}]}}';

    private string $destinataryPhone;
    private string $page;
    private string $destinataryName;
    private string $namesUser;
    private string $fullPage;
    private string $phone;
    private string $email;
    private string $message;

    public function setDestinataryPhone(string $destinataryPhone): void
    {
        $this->destinataryPhone = $destinataryPhone;
    }
    public function setPage(string $page): void
    {
        $this->page = $page;
    }
    public function setDestinataryName(string $destinataryName): void
    {
        $this->destinataryName = $destinataryName;
    }
    public function setNamesUser(string $namesUser): void
    {
        $this->namesUser = $namesUser;
    }
    public function setFullPage(string $fullPage): void
    {
        $this->fullPage = $fullPage;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function get(): array
    {
        $json = str_replace(
            [
                '{destinataryPhone}', 
                '{page}', 
                '{destinataryName}',
                '{namesUser}',
                '{fullPage}', 
                '{phone}',
                '{email}',
                '{message}',
            ],
            [
                $this->destinataryPhone, 
                $this->page, 
                $this->destinataryName,
                $this->namesUser,
                $this->fullPage, 
                $this->phone,
                $this->email,
                $this->message,
            ],
            $this->string
        );
        return JSON::parse($json);
    }
}
