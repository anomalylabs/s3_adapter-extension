<?php namespace Anomaly\S3AdapterExtension;

use Anomaly\FilesModule\Disk\Adapter\AdapterExtension;
use Anomaly\FilesModule\Disk\Adapter\Contract\AdapterInterface;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Anomaly\S3AdapterExtension\Command\LoadDisk;

/**
 * Class S3AdapterExtension
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3AdapterExtension
 */
class S3AdapterExtension extends AdapterExtension implements AdapterInterface
{

    /**
     * This module provides the s3
     * storage adapter for the files module.
     *
     * @var string
     */
    protected $provides = 'anomaly.module.files::adapter.s3';

    /**
     * Load the disk.
     *
     * @param DiskInterface $disk
     */
    public function load(DiskInterface $disk)
    {
        $this->dispatch(new LoadDisk($disk));
    }

    /**
     * Validate adapter configuration.
     *
     * @param array $configuration
     * @return bool
     */
    public function validate(array $configuration)
    {
        return true;
    }
}
