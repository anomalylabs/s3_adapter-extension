<?php namespace Anomaly\S3StorageAdapterExtension;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\FilesModule\Adapter\StorageAdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Anomaly\Streams\Platform\Application\Application;
use Aws\S3\S3Client;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

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

                return new StorageAdapterFilesystem(
                    $disk,
                    new AwsS3Adapter(
                        S3Client::factory(
                            [
                                'credentials' => [
                                    'key'    => $configuration->get(
                                        'anomaly.extension.s3_storage_adapter::access_key',
                                        $disk->getSlug()
                                    ),
                                    'secret' => $configuration->get(
                                        'anomaly.extension.s3_storage_adapter::secret_key',
                                        $disk->getSlug()
                                    ),
                                ],
                                'region'      => $configuration->get(
                                    'anomaly.extension.s3_storage_adapter::region',
                                    $disk->getSlug()
                                )
                            ]
                        ),
                        $configuration->get(
                            'anomaly.extension.s3_storage_adapter::bucket',
                            $disk->getSlug()
                        ),
                        $disk->getSlug()
                    )
                );
            }
        );
    }
}
