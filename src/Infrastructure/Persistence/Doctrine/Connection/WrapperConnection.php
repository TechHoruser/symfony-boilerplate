<?php

namespace App\Infrastructure\Persistence\Doctrine\Connection;

use Doctrine\DBAL\Connection;

class WrapperConnection extends Connection
{
    public function switch(
        ?string $username = null,
        ?string $password = null,
        ?string $databaseName = null,
        ?string $host = null,
        ?string $port = null
    ): void {
        // close current connection if required
        $this->close();

        // replace database url with new database name
        $params = $this->getParams();

        $params['user'] = $username ?? $params['user'];
        $params['password'] = $password ?? $params['password'];
        $params['port'] = $port ?? $params['port'];
        $params['host'] = $host ?? $params['host'];
        $params['dbname'] = $databaseName ?? $params['dbname'];

        $url = sprintf(
            getenv('DATABASE_URL_TEMPLATE'),
            $params['user'],
            $params['password'],
            $params['host'],
            $params['port'],
            $params['dbname']
        );

        // replace connection's params
        $params['url'] = $url;

        // initialize again the connection with updated connection's parameters
        $this->__construct($params, $this->_driver, $this->_config, $this->_eventManager);
    }
}
