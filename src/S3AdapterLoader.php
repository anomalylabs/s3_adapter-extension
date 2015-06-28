<?php namespace Anomaly\S3AdapterExtension;

use Anomaly\FilesModule\Disk\Contract\DiskInterface;

/**
 * Class S3AdapterLoader
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3AdapterExtension
 */
class S3AdapterLoader
{

    /**
     * The driver utility.
     *
     * @var S3AdapterDriver
     */
    protected $driver;

    /**
     * The disk mounter.
     *
     * @var S3AdapterMounter
     */
    protected $mounter;

    /**
     * The Laravel integrator.
     *
     * @var S3AdapterIntegrator
     */
    protected $integrator;

    /**
     * Create a new S3AdapterLoader instance.
     *
     * @param S3AdapterDriver     $driver
     * @param S3AdapterMounter    $mounter
     * @param S3AdapterIntegrator $integrator
     */
    function __construct(
        S3AdapterDriver $driver,
        S3AdapterMounter $mounter,
        S3AdapterIntegrator $integrator
    ) {
        $this->driver     = $driver;
        $this->mounter    = $mounter;
        $this->integrator = $integrator;
    }

    /**
     * Load the disk.
     *
     * @param DiskInterface $disk
     */
    public function load(DiskInterface $disk)
    {
        $driver = $this->driver->make($disk);

        $this->mounter->mount($disk, $driver);
        $this->integrator->integrate($disk, $driver);
    }
}
