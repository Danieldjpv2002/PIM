<?php

namespace App\Templates;

use SoDe\Extend\JSON;

class ActivityIssue
{
    private string $string = '{"messaging_product":"whatsapp","to":"{phone}","type":"template","template":{"name":"activity_issue","language":{"code":"es"},"components":[{"type":"header","parameters":[{"type":"document","document":{"link":"{urlPDF}","filename":"{namePDF}"}}]},{"type":"body","parameters":[{"type":"text","text":"{destinatary}"},{"type":"text","text":"{startDate}"},{"type":"text","text":"{endDate}"},{"type":"text","text":"{issueDate}"}]}]}}';

    private string $phone;
    private string $urlPDF;
    private string $namePDF;
    private string $destinatary;
    private string $startDate;
    private string $endDate;
    private string $issueDate;

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setUrlPDF(string $urlPDF): void
    {
        $this->urlPDF = $urlPDF;
    }
    public function setNamePDF(string $namePDF): void
    {
        $this->namePDF = $namePDF;
    }
    public function setDestinatary(string $destinatary): void
    {
        $this->destinatary = $destinatary;
    }
    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }
    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }
    public function setIssueDate(string $issueDate): void
    {
        $this->issueDate = $issueDate;
    }

    public function get(): array
    {
        $json = str_replace(
            [
                '{phone}',
                '{urlPDF}', '{namePDF}',
                '{destinatary}',
                '{startDate}', '{endDate}',
                '{issueDate}'
            ],
            [
                $this->phone,
                $this->urlPDF, $this->namePDF,
                $this->destinatary,
                $this->startDate, $this->endDate,
                $this->issueDate,
            ],
            $this->string
        );
        return JSON::parse($json);
    }
}
