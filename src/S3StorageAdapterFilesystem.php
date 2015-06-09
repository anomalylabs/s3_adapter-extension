<?php namespace Anomaly\S3StorageAdapterExtension;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\FilesModule\Adapter\StorageAdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\Adapter\S3;

/**
 * Class S3StorageAdapterFilesystem
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3StorageAdapterExtension
 */
class S3StorageAdapterFilesystem
{

    /**
     * Handle loading the filesystem.
     *
     * @param DiskInterface                    $disk
     * @param FilesystemManager                $manager
     * @param Application                      $application
     * @param ConfigurationRepositoryInterface $configuration
     */
    public function load(
        DiskInterface $disk,
        FilesystemManager $manager,
        Application $application,
        ConfigurationRepositoryInterface $configuration
    ) {
        $manager->extend(
            $disk->getSlug(),
            function () use ($disk, $application, $configuration) {

                $mode = $configuration->get(
                    'anomaly.extension.s3_storage_adapter::privacy',
                    $disk->getSlug(),
                    'public'
                );

                if ($mode === 'private') {
                    $method = 'getStoragePath';
                } else {
                    $method = 'getAssetsPath';
                }

                return new StorageAdapterFilesystem(
                    $disk,
                    new S3(
                        $application->{$method}("streams/files/{$disk->getSlug()}")
                    )
                );
            }
        );
    }
}
