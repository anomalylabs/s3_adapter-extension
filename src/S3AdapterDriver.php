<?php namespace Anomaly\S3AdapterExtension;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\FilesModule\Adapter\AdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * Class S3AdapterDriver
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3AdapterExtension
 */
class S3AdapterDriver
{

    /**
     * The configuration repository.
     *
     * @var ConfigurationRepositoryInterface
     */
    protected $configuration;

    /**
     * Create a new S3AdapterDriver instance.
     *
     * @param ConfigurationRepositoryInterface $configuration
     */
    function __construct(ConfigurationRepositoryInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Return the configured filesystem driver.
     *
     * @param DiskInterface $disk
     * @return AdapterFilesystem
     */
    public function make(DiskInterface $disk)
    {
        $prefix = $this->configuration->get(
            'anomaly.extension.s3_adapter::prefix_path',
            $disk->getSlug()
        );

        return new AdapterFilesystem(
            $disk,
            new AwsS3Adapter(
                S3Client::factory(
                    [
                        'credentials' => [
                            'key'    => $this->configuration->get(
                                'anomaly.extension.s3_adapter::access_key',
                                $disk->getSlug()
                            ),
                            'secret' => $this->configuration->get(
                                'anomaly.extension.s3_adapter::secret_key',
                                $disk->getSlug()
                            ),
                        ],
                        'region'      => $this->configuration->get(
                            'anomaly.extension.s3_adapter::region',
                            $disk->getSlug()
                        ),
                        'version'     => '2006-03-01'
                    ]
                ),
                $this->configuration->get(
                    'anomaly.extension.s3_adapter::bucket',
                    $disk->getSlug()
                ),
                $prefix ? $disk->getSlug() : null
            )
        );
    }
}
