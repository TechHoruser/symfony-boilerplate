<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

interface DateTimeHelperInterface
{
    public function getFormatDateTime(): string;
    public function getDateTimeFromString(string $dateTime): \DateTime;
    public function getStringFromDateTime(\DateTime $dateTime): string;
    public function getSearchDatesSeparator(): string;
}
