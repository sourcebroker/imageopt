<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Resource;

use TYPO3\CMS\Core\Imaging\ImageManipulation\Area;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Resource\Driver\DriverInterface;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Service\ConfigurationService;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class CroppedFile extends AbstractFile
{
    /**
     * Processing configuration
     */
    protected array $processingConfiguration;

    /**
     * Reference to the original file this cropped file has been created from.
     */
    protected File $originalFile;

    /**
     * The SHA1 hash of the original file this cropped version has been created for.
     * Is used for detecting changes if the original file has been changed and thus
     * we have to recreate this cropped file.
     */
    protected string $originalFileSha1;

    /**
     * A flag that shows if this object has been updated during its lifetime, i.e. the file has been
     * replaced with a new one.
     */
    protected bool $updated = false;

    protected DriverInterface $driver;

    protected string $processingProvider;

    /**
     * Constructor for a cropped file object. Should normally not be used
     * directly, use the corresponding factory methods instead.
     *
     * @param File $originalFile
     * @param array $processingConfiguration
     * @param string $providerName
     * @param array|null $databaseRow
     */
    public function __construct(File $originalFile, array $processingConfiguration, string $providerName, array $databaseRow = null)
    {
        $this->originalFile = $originalFile;
        $this->originalFileSha1 = $this->originalFile->getSha1();
        $this->storage = $originalFile->getStorage()->getProcessingFolder()->getStorage();
        $this->processingConfiguration = $processingConfiguration;
        $this->processingProvider = $providerName;
        if (is_array($databaseRow)) {
            $this->reconstituteFromDatabaseRecord($databaseRow);
        } else {
            $this->setName(
                'cropped'
                . '_' . $originalFile->getNameWithoutExtension()
                . '_' . $this->calculateChecksum()
                . '.' . $originalFile->getExtension()
            );
        }

        $this->driver = GeneralUtility::makeInstance(LocalDriver::class, $this->originalFile->getStorage()->getConfiguration());
        $this->driver->setStorageUid($this->originalFile->getStorage()->getStorageRecord()['uid'] ?? null);
        $this->driver->mergeConfigurationCapabilities($this->originalFile->getStorage()->getCapabilities());
        $this->driver->processConfiguration();
        $this->driver->initialize();
    }

    /**
     * Creates a CroppedFile object from a database record.
     *
     * @param array $databaseRow
     */
    protected function reconstituteFromDatabaseRecord(array $databaseRow): void
    {
        $this->processingConfiguration = $this->processingConfiguration ?? unserialize($databaseRow['configuration'] ?? '', ['allowed_classes' => [Area::class]]);
        $this->originalFileSha1 = $databaseRow['original_file_sha1'];
        $this->identifier = $databaseRow['identifier'];
        $this->name = $databaseRow['name'];
        $this->properties = $databaseRow;

        if (!empty($databaseRow['storage']) && $this->storage->getUid() !== (int)$databaseRow['storage']) {
            $this->storage = GeneralUtility::makeInstance(StorageRepository::class)->findByUid($databaseRow['storage']);
        }
    }

    /**
     * Injects a local file, which is a processing result into the object.
     *
     * @param string $filePath
     * @throws \RuntimeException
     */
    public function updateWithLocalFile(string $filePath): void
    {
        if (empty($this->identifier)) {
            throw new \RuntimeException('Cannot update original file!', 2330582154);
        }
        $processingFolder = $this->originalFile->getStorage()->getProcessingFolder($this->originalFile);

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('File "' . $filePath . '" does not exist.', 2329555746);
        }
        if ($processingFolder === null) {
            throw new \InvalidArgumentException('Processing folder does not exist.', 2219532741);
        }

        $fileIdentifier = $this->driver->addFile($filePath, $processingFolder->getIdentifier(), $this->getName(), false);

        // Update some related properties
        $this->identifier = $fileIdentifier;
        $this->originalFileSha1 = $this->originalFile->getSha1();
        $this->updateProperties($this->getProperties());
        $this->deleted = false;
        $this->updated = true;
    }

    /**
     * Returns TRUE if this file is indexed
     *
     * @return bool
     */
    public function isIndexed(): bool
    {
        return false;
    }

    /**
     * Checks whether the CroppedFile already has an entry in sys_file_croppedfile table
     *
     * @return bool
     */
    public function isPersisted(): bool
    {
        return is_array($this->properties) && array_key_exists('uid', $this->properties) && $this->properties['uid'] > 0;
    }

    /**
     * Checks whether the CroppedFile Object is newly created
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return !$this->isPersisted();
    }

    /**
     * Checks whether the object since last reconstitution, and therefore
     * needs persistence again
     *
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->updated;
    }

    /**
     * Sets a new file name
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        // Remove the existing file, but only we actually have a name or the name has changed
        if (!empty($this->name) && $this->name !== $name && $this->exists()) {
            $this->delete();
        }

        $this->name = $name;
        $this->identifier = $this->storage->getProcessingFolder($this->originalFile)->getIdentifier() . $this->name;

        $this->updated = true;
    }

    /**
     * Checks if this file exists.
     * Since the original file may reside in a different storage
     * we ask the original file if it exists in case the processed is representing it
     *
     * @return bool TRUE if this file physically exists
     */
    public function exists(): bool
    {
        if ($this->usesOriginalFile()) {
            return $this->originalFile->exists();
        }

        return parent::exists();
    }

    /**
     * Returns TRUE if this file is already processed.
     *
     * @return bool
     */
    public function isCropped(): bool
    {
        return $this->updated || ($this->isPersisted() && !$this->needsReprocessing());
    }

    /**
     * Getter for the Original, unprocessed File
     *
     * @return File
     */
    public function getOriginalFile(): File
    {
        return $this->originalFile;
    }

    /**
     * Get the identifier of the file
     *
     * If there is no cropped file in the file system  (as the original file did not have to be modified e.g.
     * when the original image is in the boundaries of the maxW/maxH stuff), then just return the identifier of
     * the original file
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return (!$this->usesOriginalFile()) ? $this->identifier : $this->getOriginalFile()->getIdentifier();
    }

    /**
     * Get the name of the file
     *
     * If there is no cropped file in the file system (as the original file did not have to be modified e.g.
     * when the original image is in the boundaries of the maxW/maxH stuff)
     * then just return the name of the original file
     *
     * @return string
     */
    public function getName(): string
    {
        if ($this->usesOriginalFile()) {
            return $this->originalFile->getName();
        }
        return $this->name;
    }

    /**
     * Updates properties of this object. Do not use this to reconstitute an object from the database; use
     * reconstituteFromDatabaseRecord() instead!
     *
     * @param array $properties
     */
    public function updateProperties(array $properties): void
    {
        if (!is_array($this->properties)) {
            $this->properties = [];
        }

        if (array_key_exists('uid', $properties) && MathUtility::canBeInterpretedAsInteger($properties['uid'])) {
            $this->properties['uid'] = $properties['uid'];
        }

        $this->properties = array_merge($this->properties, $properties);

        if (!$this->isUnchanged() && $this->exists()) {
            $storage = $this->storage;
            if ($this->usesOriginalFile()) {
                $storage = $this->originalFile->getStorage();
            }
            $this->properties = array_merge($this->properties, $storage->getFileInfo($this));
        }
    }

    /**
     * Basic array function for the DB update
     *
     * @return array
     */
    public function toArray(): array
    {
        if ($this->usesOriginalFile()) {
            $properties = $this->originalFile->getProperties();
            unset($properties['uid']);
            $properties['identifier'] = '';
            $properties['name'] = null;

        } else {
            $properties = $this->properties;
            $properties['identifier'] = $this->getIdentifier();
            $properties['name'] = $this->getName();
        }

        $properties['configuration'] = (new ConfigurationService())->serialize($this->processingConfiguration);

        return array_merge($properties, [
            'storage' => $this->getStorage()->getUid(),
            'configuration_identifier' => $this->calculateChecksum(),
            'original' => $this->originalFile->getUid(),
            'original_file_sha1' => $this->originalFileSha1,
            'configuration_sha1' => sha1($properties['configuration']),
            'processing_provider' => $this->processingProvider,
        ]);
    }

    /**
     * Returns TRUE if this file has not been changed during processing (i.e., we just deliver the original file)
     *
     * @return bool
     */
    protected function isUnchanged(): bool
    {
        return !($this->properties['width'] ?? false) && $this->usesOriginalFile();
    }

    /**
     * Defines that the original file should be used.
     */
    public function setUsesOriginalFile(): void
    {
        // @todo check if some of these properties can/should be set in a generic update method
        $this->identifier = $this->originalFile->getIdentifier();
        $this->originalFileSha1 = $this->originalFile->getSha1();
        $this->updated = true;
    }

    /**
     * @return bool
     */
    public function usesOriginalFile(): bool
    {
        return empty($this->identifier) || $this->identifier === $this->originalFile->getIdentifier();
    }

    /**
     * Returns TRUE if the original file of this file changed and the file should be processed again.
     *
     * @return bool
     */
    public function isOutdated(): bool
    {
        return $this->needsReprocessing();
    }

    /**
     * Delete cropped file
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        if (!$force && $this->isUnchanged()) {
            return false;
        }
        // Only delete file when original isn't used
        if (!$this->usesOriginalFile()) {
            return parent::delete();
        }
        return true;
    }

    /**
     * Getter for file-properties
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getProperty($key)
    {
        // The uid always (!) has to come from this file and never the original file (see getOriginalFile() to get this)
        if ($key !== 'uid' && $this->isUnchanged()) {
            return $this->originalFile->getProperty($key);
        }
        return $this->properties[$key] ?? null;
    }

    /**
     * Returns the uid of this file
     *
     * @return int
     */
    public function getUid(): int
    {
        return $this->properties['uid'] ?? 0;
    }

    /**
     * Checks if the CroppedFile needs reprocessing
     *
     * @return bool
     */
    public function needsReprocessing(): bool
    {
        $fileMustBeRecreated = false;

        // if original is missing we can not reprocess the file
        if ($this->originalFile->isMissing()) {
            return false;
        }

        // croppedFile does not exist
        if (!$this->usesOriginalFile() && !$this->exists()) {
            $fileMustBeRecreated = true;
        }

        // hash does not match
        if (
            array_key_exists('configuration_sha1', $this->properties)
            && $this->getConfigurationSha1() !== $this->properties['configuration_sha1']
        ) {
            $fileMustBeRecreated = true;
        }

        // original file changed
        if ($this->originalFile->getSha1() !== $this->originalFileSha1) {
            $fileMustBeRecreated = true;
        }

        if (!array_key_exists('uid', $this->properties)) {
            $fileMustBeRecreated = true;
        }

        // remove outdated file
        if ($fileMustBeRecreated && $this->exists()) {
            $this->delete();
        }
        return $fileMustBeRecreated;
    }

    /**
     * Returns the processing information
     *
     * @return array
     */
    public function getProcessingConfiguration(): array
    {
        return $this->processingConfiguration;
    }

    protected function calculateChecksum(): string
    {
        return substr(md5(implode('|', $this->getChecksumData())), 0, 10);
    }

    protected function getChecksumData(): array
    {
        return [
            $this->getOriginalFile()->getUid(),
            $this->getType() . '.' . $this->processingProvider . $this->getOriginalFile()->getModificationTime(),
            (new ConfigurationService())->serialize($this->processingConfiguration),
        ];
    }

    protected function getConfigurationSha1(): string
    {
        return sha1((new ConfigurationService())->serialize($this->processingConfiguration));
    }

    /**
     * Returns a publicly accessible URL for this file
     *
     * @param bool $relativeToCurrentScript Determines whether the URL returned should be relative to the current script, in case it is relative at all. Deprecated since TYPO3 v11, will be removed in TYPO3 v12.0
     * @return string|null NULL if file is deleted, the generated URL otherwise
     */
    public function getPublicUrl($relativeToCurrentScript = false): ?string
    {
        if ($this->deleted) {
            return null;
        }
        // @deprecated $relativeToCurrentScript since v11, will be removed in TYPO3 v12.0
        if ($this->usesOriginalFile()) {
            return $this->getOriginalFile()->getPublicUrl($relativeToCurrentScript);
        }
        return $this->getStorage()->getPublicUrl($this, $relativeToCurrentScript);
    }

}
