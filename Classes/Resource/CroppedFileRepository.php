<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Resource;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\ImageManipulation\Area;
use TYPO3\CMS\Core\Resource\AbstractRepository;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\Service\ConfigurationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CroppedFileRepository extends AbstractRepository implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $objectType = CroppedFile::class;
    protected $table = 'tx_imageopt_domain_model_croppedfile';
    protected $tableColumns = [];

    public function findOneByProcessedFileAndProvider(ProcessedFile $processedFile, string $providerName): CroppedFile
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->table);
        $configuration = [$processedFile->getProcessingConfiguration()['crop'] ?? ''];

        $databaseRow = $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->eq(
                    'original',
                    $queryBuilder->createNamedParameter($processedFile->getOriginalFile()->getUid(), Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'configuration_sha1',
                    $queryBuilder->createNamedParameter(
                        sha1((new ConfigurationService())->serialize($configuration)),
                        Connection::PARAM_STR
                    )
                ),
                $queryBuilder->expr()->eq(
                    'processing_provider',
                    $queryBuilder->createNamedParameter(
                        $providerName,
                        Connection::PARAM_STR
                    )
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if (is_array($databaseRow)) {
            $croppedFile = $this->createDomainObject($databaseRow);
        } else {
            $croppedFile = $this->createNewCroppedFileObject(
                $processedFile->getOriginalFile(),
                $configuration,
                $providerName
            );
        }

        return $croppedFile;
    }

    /**
     * @param CroppedFile $object
     */
    public function add($object): void
    {
        if ($object->isPersisted()) {
            $this->update($object);
        } else {
            $insertFields = $object->toArray();
            $insertFields['crdate'] = $insertFields['tstamp'] = time();
            $insertFields = $this->cleanUnavailableColumns($insertFields);

            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);

            $connection->insert(
                $this->table,
                $insertFields,
                ['configuration' => Connection::PARAM_LOB]
            );

            $uid = $connection->lastInsertId($this->table);
            $object->updateProperties(['uid' => $uid]);
        }
    }

    /**
     * @param CroppedFile $modifiedObject
     */
    public function update($modifiedObject): void
    {
        if ($modifiedObject->isPersisted()) {
            $uid = $modifiedObject->getUid();
            $updateFields = $this->cleanUnavailableColumns($modifiedObject->toArray());
            $updateFields['tstamp'] = time();

            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);
            $connection->update(
                $this->table,
                $updateFields,
                [
                    'uid' => $uid,
                ],
                ['configuration' => Connection::PARAM_LOB]
            );
        }
    }

    public function createNewCroppedFileObject(FileInterface $originalFile, array $configuration, string $providerName)
    {
        return GeneralUtility::makeInstance(
            $this->objectType,
            $originalFile,
            $configuration,
            $providerName
        );
    }

    protected function cleanUnavailableColumns(array $data): array
    {
        if (empty($this->tableColumns[$this->table])) {
            $this->tableColumns[$this->table] = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($this->table)
                ->createSchemaManager()
                ->listTableColumns($this->table);
        }

        return array_intersect_key($data, $this->tableColumns[$this->table]);
    }

    protected function createDomainObject(array $databaseRow)
    {
        $originalFile = $this->factory->getFileObject((int)$databaseRow['original']);

        // Allow deserialization of Area class, since Area objects get serialized in configuration
        $configuration = unserialize(
            $databaseRow['configuration'],
            [
                'allowed_classes' => [
                    Area::class,
                ],
            ]
        );

        return GeneralUtility::makeInstance(
            $this->objectType,
            $originalFile,
            $configuration,
            $databaseRow['processing_provider'],
            $databaseRow
        );
    }
}
