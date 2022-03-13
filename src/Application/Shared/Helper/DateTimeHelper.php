<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

use App\Application\Shared\Config\ParametersConfigInterface;

class DateTimeHelper implements DateTimeHelperInterface
{
    private string $formatDateTime;
    private string $searchDatesSeparator;

    public function __construct(
        ParametersConfigInterface $parametersConfig
    ) {
        $this->formatDateTime = $parametersConfig->get('app.date_format');
        $this->searchDatesSeparator = $parametersConfig->get('app.search_dates_separator');
    }

    /**
     * @return string
     */
    public function getFormatDateTime(): string
    {
        return $this->formatDateTime;
    }

    /**
     * @param string $dateTime
     * @return \DateTime
     */
    public function getDateTimeFromString(string $dateTime): \DateTime
    {
        return \DateTime::createFromFormat(
            $this->formatDateTime,
            $dateTime
        );
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public function getStringFromDateTime(\DateTime $dateTime): string
    {
        return $dateTime->format($this->formatDateTime);
    }

    /**
     * @return string
     */
    public function getSearchDatesSeparator(): string
    {
        return $this->searchDatesSeparator;
    }
}
