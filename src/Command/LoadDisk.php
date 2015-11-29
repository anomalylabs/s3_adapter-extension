<?php namespace Anomaly\S3AdapterExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\EncryptedFieldType\EncryptedFieldTypePresenter;
use Anomaly\FilesModule\Disk\Adapter\AdapterFilesystem;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Aws\S3\S3Client;
use Illuminate\Contracts\Bus\SelfHandling;
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
class LoadDisk implements SelfHandling
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
        MountManager $manager
    ) {
        $prefix = $configuration->value('anomaly.extension.s3_adapter::prefix', true);

        /* @var EncryptedFieldTypePresenter $key */
        $key = $configuration->presenter('anomaly.extension.s3_adapter::access_key', $this->disk->getSlug());

        /* @var EncryptedFieldTypePresenter $secret */
        $secret = $configuration->presenter('anomaly.extension.s3_adapter::secret_key', $this->disk->getSlug());

        $driver = new AdapterFilesystem(
            $this->disk,
            new AwsS3Adapter(
                new S3Client(
                    [
                        'credentials' => [
                            'key'    => $key->decrypted(),
                            'secret' => $secret->decrypted(),
                        ],
                        'region'      => $configuration->get(
                            'anomaly.extension.s3_adapter::region',
                            $this->disk->getSlug()
                        )->getValue(),
                        'version'     => '2006-03-01'
                    ]
                ),
                $configuration->get(
                    'anomaly.extension.s3_adapter::bucket',
                    $this->disk->getSlug()
                )->getValue(),
                $prefix ? $this->disk->getSlug() : null
            )
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
