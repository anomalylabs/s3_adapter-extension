<?php namespace Anomaly\S3AdapterExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\EncryptedFieldType\EncryptedFieldTypePresenter;
use Anomaly\FilesModule\Disk\Adapter\AdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Aws\S3\S3Client;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\MountManager;

/**
 * Class LoadDisk
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3AdapterExtension\Command
 */
class LoadDisk
{

    /**
     * The disk interface.
     *
     * @var DiskInterface
     */
    protected $disk;

    /**
     * Create a new LoadDisk instance.
     *
     * @param DiskInterface $disk
     */
    public function __construct(DiskInterface $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @param ConfigurationRepositoryInterface $configuration
     */
    public function handle(
        ConfigurationRepositoryInterface $configuration,
        FilesystemManager $filesystem,
        MountManager $manager,
        Repository $config
    ) {

        /* @var EncryptedFieldTypePresenter $key */
        $key = $configuration->presenter(
            'anomaly.extension.s3_adapter::access_key',
            $this->disk->getSlug()
        );

        $key = $key->decrypt();

        /* @var EncryptedFieldTypePresenter $secret */
        $secret = $configuration->presenter(
            'anomaly.extension.s3_adapter::secret_key',
            $this->disk->getSlug()
        );

        $secret = $secret->decrypt();

        $prefix = $configuration->value(
            'anomaly.extension.s3_adapter::prefix',
            $this->disk->getSlug()
        );

        $cloudFront = $configuration->value(
            'anomaly.extension.s3_adapter::cloud_front',
            $this->disk->getSlug()
        );

        $bucket = $configuration->value(
            'anomaly.extension.s3_adapter::bucket',
            $this->disk->getSlug()
        );

        $region = $configuration->get(
            'anomaly.extension.s3_adapter::region',
            $this->disk->getSlug()
        )->getValue();

        $visibility = $configuration->get(
            'anomaly.extension.s3_adapter::visibility',
            $this->disk->getSlug()
        )->getValue();

        $config->set(
            'filesystems.disks.' . $this->disk->getSlug(),
            [
                'driver' => 's3',
                'key'    => $key,
                'secret' => $secret,
                'region' => $region,
                'bucket' => $bucket,
                'prefix' => $prefix,
            ]
        );

        $baseUrl = 'https://s3.'
            . $region
            . '.amazonaws.com/'
            . implode(
                '/',
                array_filter([$bucket, $prefix])
            );

        if ($cloudFront) {
            $baseUrl = trim($cloudFront, '/') . '/' . implode('/', array_filter([$prefix]));
        }

        $driver = new AdapterFilesystem(
            $this->disk,
            new AwsS3Adapter(
                $client = new S3Client(
                    [
                        'credentials' => [
                            'key'    => $key,
                            'secret' => $secret,
                        ],
                        'region'      => $region,
                        'version'     => '2006-03-01',
                    ]
                ),
                $bucket,
                $prefix
            ),
            [
                'base_url' => $baseUrl,
                'visibility' => $visibility
            ]
        );

        $manager->mountFilesystem($this->disk->getSlug(), $driver);

        $filesystem->extend(
            $this->disk->getSlug(),
            function () use ($driver) {
                return $driver;
            }
        );
    }
}
