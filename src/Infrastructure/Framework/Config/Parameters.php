<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\Config;

use App\Application\Shared\Config\ParametersConfigInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Parameters implements ParametersConfigInterface
{
    private ContainerBagInterface $containerBag;

    /**
     * Parameters constructor.
     * @param ContainerBagInterface $containerBag
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->containerBag = $containerBag;
    }


    public function get(string $id)
    {
        return $this->containerBag->get($id);
    }
}
