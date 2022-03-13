<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Shared\Entity\PaginationProperties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractRepository extends ServiceEntityRepository
{
    abstract protected function getAliasTable(): string;
    abstract protected function getEntityRepositoryClass(): string;
    private array $appliedJoins;
    protected QueryBuilder $queryBuilder;

    public function __construct(ManagerRegistry $registry, SerializerInterface $serializer)
    {
        parent::__construct($registry, $this->getEntityRepositoryClass());
        $this->resetParams();
    }

    public function getByUuid(string $uuid, array $embeds = [])
    {
        $this->resetParams();

        foreach ($embeds as $embed) {
            $this->addEmbed($embed);
        }

        return $this->queryBuilder
            ->where($this->getAliasTable().'.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()->getOneOrNullResult();
    }

    public function deleteByUuid(string $uuid): void
    {
        $entity = $this->_em->getPartialReference($this->getClassName(), array('uuid' => $uuid));
        $this->_em->remove($entity);
    }

    public function complexFind(
        PaginationProperties $paginationProperties = new PaginationProperties(),
        array $filters = [],
        array $embeds = [],
    ): iterable {
        $this->resetParams();
        if ($paginationProperties->page > 0 && $paginationProperties->resultsPerPage > 0) {
            $this->queryBuilder->setFirstResult(
                $paginationProperties->resultsPerPage * ($paginationProperties->page - 1)
            )
                ->setMaxResults($paginationProperties->resultsPerPage);
        }

        if (!is_null($paginationProperties->sortBy)) {
            $this->addOrder($paginationProperties->sortBy, $paginationProperties->sortOrder);
        }

        foreach ($filters as $fieldName => $fieldValue) {
            $this->addWhere($fieldName, $fieldValue);
        }

        foreach ($embeds as $embed) {
            $this->addEmbed($embed);
        }

        return $this->queryBuilder->getQuery()->getResult();
    }

    public function countAll($filters = []): int
    {
        $this->resetParams();
        // REVIEW: %s.uuid by %s.* but error in DTO library
        $this->queryBuilder->select(sprintf('count(%s.uuid)', $this->getAliasTable()));

        foreach ($filters as $fieldName => $fieldValue) {
            $this->addWhere($fieldName, $fieldValue);
        }

        return $this->queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function saveEntityInterface($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
        return $entity;
    }

    protected function addOrder(string $fieldName, mixed $value)
    {
        $this->addRecursiveJoin([$this, "_callbackOrder"], $fieldName, $value);
    }

    protected function addWhere(string $fieldName, mixed $value)
    {
        $this->addRecursiveJoin([$this, "_callbackWhere"], $fieldName, $value);
    }

    protected function addEmbed(string $fieldName)
    {
        $this->addRecursiveJoin([$this, "_callbackVoid"], $fieldName);
    }

    private function addRecursiveJoin(
        callable $callbackMethod,
        string $fieldName,
        mixed $value = null,
        ?string $alias = null,
        ?ClassMetadata $classMetadata = null
    ): void {
        $alias = $alias ?? $this->getAliasTable();

        $classMetadata = $classMetadata ?? $this->_em->getClassMetadata($this->getEntityRepositoryClass());

        $separatedFieldNames = explode('.', $fieldName, 2);
        $parentField = $separatedFieldNames[0];

        if (isset($classMetadata->associationMappings[$parentField])) {
            $join = $alias . '.' . $parentField;
            if (!isset($this->appliedJoins[$join])) {
                $this->appliedJoins[$join] = true;
                $this->queryBuilder->leftJoin($join, $parentField);
            }
        }

        if (!isset($classMetadata->associationMappings[$parentField]) || count($separatedFieldNames) === 1) {
            $callbackMethod(
                $separatedFieldNames[0],
                $value,
                $alias,
                $classMetadata
            );

            return;
        }

        $classMetadata = $this->_em->getClassMetadata(
            $classMetadata->associationMappings[$parentField]['targetEntity']
        );

        $this->addRecursiveJoin(
            $callbackMethod,
            $separatedFieldNames[1],
            $value,
            $parentField,
            $classMetadata
        );
    }

    private function _callbackWhere(
        string $fieldName,
        $fieldValue,
        string $alias,
        ClassMetadata $classMetadata
    ) {
        $fieldMapping = $classMetadata->fieldMappings[$fieldName];
        $fieldName = $alias . '.' . $fieldName;

        if ($fieldMapping['type'] === 'datetime' || $fieldMapping['type'] === 'date') {
            $this->addWhereDateTime($fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] === 'string') {
            $this->addWhereString($fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] === 'guid') {
            $this->addWhereUuid($fieldName, $fieldValue);
        }
        if ($fieldMapping['type'] == 'integer') {
            $this->addWhereInteger($fieldName, $fieldValue);
        }
    }

    private function _callbackOrder(
        string $fieldName,
        $value,
        string $alias,
        ClassMetadata $classMetadata
    ) {
        // TODO: Check $value if it's different to ASC or DESC, then throw certain exception

        $this->queryBuilder->orderBy($alias . '.' . $fieldName, $value);
    }

    private function _callbackVoid(
        string $fieldName,
        $value,
        string $alias,
        ClassMetadata $classMetadata
    ) {}

    /**
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereDateTime(string $fieldName, $fieldValue): void
    {
        $dateTimes = explode('/', $fieldValue);

        $fromDate = $dateTimes[0];
        if ($fromDate) {
            $fromOperator = count($dateTimes) > 1 ? '>=' : '=';
            $this->queryBuilder->andWhere(
                sprintf(
                    "%s %s '%s'",
                    $fieldName,
                    $fromOperator,
                    $fromDate
                )
            );
        }
        $toDate = count($dateTimes) > 1 ? $dateTimes[1] : null;
        if ($toDate) {
            $this->queryBuilder->andWhere(sprintf("%s <= '%s'", $fieldName, $toDate));
        }
    }

    /**
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereString(string $fieldName, $fieldValue): void
    {
        $this->queryBuilder->andWhere(
            sprintf('LOWER(%s) LIKE \'%%%s%%\'', $fieldName, mb_strtolower($fieldValue))
        );
    }

    /**
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereUuid(string $fieldName, $fieldValue): void
    {
        $this->queryBuilder->andWhere(sprintf('%s = \'%s\'', $fieldName, mb_strtolower($fieldValue)));
    }

    /**
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function addWhereInteger(string $fieldName, $fieldValue): void
    {
        $this->queryBuilder->andWhere(sprintf('%s = %s', $fieldName, intval($fieldValue)));
    }

    private function resetParams(): void
    {
        $this->queryBuilder = $this->createQueryBuilder($this->getAliasTable());
        $this->appliedJoins = [];
    }

}
