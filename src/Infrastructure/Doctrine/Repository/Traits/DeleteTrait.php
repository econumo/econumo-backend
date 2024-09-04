<?php

declare(strict_types=1);


namespace App\Infrastructure\Doctrine\Repository\Traits;

trait DeleteTrait
{
    public function delete(array $items): void
    {
        foreach ($items as $item) {
            $this->getEntityManager()->remove($item);
        }
        if (count($items) > 0) {
            $this->getEntityManager()->flush();
        }
    }
}
