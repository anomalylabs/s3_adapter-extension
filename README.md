# S3 Adapter

An Amazon S3 adapter for the Files module.

## Installation
Download using composer: `composer require anomaly/s3_adapter-extension`.

Next, install using `php artisan addon:install anomaly.extension.s3_adapter`

## Getting Started

You should be able to setup a new S3 disk from the CMS via Files > Disks > New Disk. From here you can set up new Folders and assign them to the S3 disk.

If you'd prefer to swap out the default local driver seamlessly and keep all of your existing folders you can do so via a migration. Your migration file may look something like this:

```php
use Anomaly\ConfigurationModule\Configuration\ConfigurationRepository;
use Anomaly\FilesModule\Disk\DiskRepository;
use Anomaly\Streams\Platform\Database\Migration\Migration;

class SetupS3DiskConfiguration extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $disks = app(DiskRepository::class);
        $configuration = app(ConfigurationRepository::class);

        $config = config('filesystems.disks.s3');
        $disk = $disks->findBySlug('local');
        $disk->adapter = 'anomaly.extension.s3_adapter';
        $disk->save();

        $configuration->create([
            'scope' => $disk->getSlug(),
            'key'   => 'anomaly.extension.s3_adapter::access_key',
            'value' => $config['key'],
        ]);
        $configuration->create([
            'scope' => $disk->getSlug(),
            'key'   => 'anomaly.extension.s3_adapter::secret_key',
            'value' => $config['secret'],
        ]);
        $configuration->create([
            'scope' => $disk->getSlug(),
            'key'   => 'anomaly.extension.s3_adapter::region',
            'value' => $config['region'],
        ]);
        $configuration->create([
            'scope' => $disk->getSlug(),
            'key'   => 'anomaly.extension.s3_adapter::bucket',
            'value' => $config['bucket'],
        ]);
        $configuration->create([
            'scope' => $disk->getSlug(),
            'key'   => 'anomaly.extension.s3_adapter::prefix',
            'value' => $config['prefix'],
        ]);
        $configuration->create([
            'scope' => $disk->getSlug(),
            'key'   => 'anomaly.extension.s3_adapter::visibility',
            'value' => $config['visibility'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $disks = app(DiskRepositoryInterface::class);
        $configuration = app(ConfigurationRepositoryInterface::class);

        $disk = $disks->findBySlug('local');
        $disk->adapter = 'anomaly.extension.local_storage_adapter';
        $disk->save();

        $configuration->purge('anomaly.extension.s3_adapter');
    }
}

```

The configuration is pulled from the standard [laravel filesystem configuration](https://laravel.com/docs/5.7/filesystem#configuration) and published to the `default_configuration_configuration` table. Make sure you've populated the `s3` key in `config/filesystem.php` with your AWS S3 bucket API credentials before running your migration.

## Usage
After your S3 disk is set-up, usage is the same as any other disk adapter. Refer to the [pyro documentation](https://pyrocms.com/documentation/files-module/2.4/integration/laravel-filesystem)