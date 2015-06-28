<?php namespace Anomaly\S3StorageAdapterExtension;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\FilesModule\Adapter\AdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Class S3StorageAdapterDriver
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3StorageAdapterExtension
 */
class S3StorageAdapterDriver
{

    /**
     * Return the configured filesystem driver.
     *
     * @param ConfigurationRepositoryInterface $configuration
     * @param DiskInterface                    $disk
     * @return AdapterFilesystem
     */
    public function make(ConfigurationRepositoryInterface $configuration, DiskInterface $disk)
    {
        return new AdapterFilesystem(
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
                        ),
                        'version'     => '2006-03-01'
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
}
