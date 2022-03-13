<?php

declare(strict_types=1);

namespace App\Tests\Integration\Resources\Config;

class FixtureValues implements FixtureValuesInterface
{
    private string $commonUserPassword;

    public function __construct()
    {
        $ini = file_exists(dirname(__FILE__) . '/IniFiles/values.ini') ?
            array_merge(
                parse_ini_file('IniFiles/default_values.ini'),
                parse_ini_file('IniFiles/values.ini')
            ) :
            parse_ini_file('IniFiles/default_values.ini');

        $this->commonUserPassword = strval($ini['COMMON_USER_PASSWORD']);
    }

    public function getCommonUserPassword(): string
    {
        return $this->commonUserPassword;
    }
}
