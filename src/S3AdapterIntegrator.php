<?php namespace Anomaly\S3AdapterExtension;

use Anomaly\FilesModule\Adapter\AdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Class S3AdapterIntegrator
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3AdapterExtension
 */
class S3AdapterIntegrator
{

    /**
     * The filesystem manager.
     *
     * @var FilesystemManager
     */
    protected $manager;

    /**
     * Create a new S3AdapterIntegrator instance.
     *
     * @param FilesystemManager $manager
     */
    function __construct(FilesystemManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Integrate the disk with Laravel.
     *
     * @param DiskInterface     $disk
     * @param AdapterFilesystem $driver
     */
    public function integrate(DiskInterface $disk, AdapterFilesystem $driver)
    {

    }
}
