# S3 Adapter

An Amazon S3 adapter for the Files module.

## Installation
Download using composer: `composer require anomaly/s3_adapter-extension`.

Next, install using `php artisan addon:install anomaly.extension.s3_adapter`

## Getting Started

You should be able to setup a new S3 disk from the CMS via Files > Disks > New Disk. From here you can set up new Folders and assign them to the S3 disk.

#### Configuration

The configuration is pulled from the standard [laravel filesystem configuration](https://laravel.com/docs/5.7/filesystem#configuration) and published to the `default_configuration_configuration` table. Make sure you've populated the `s3` key in `config/filesystem.php` with your AWS S3 bucket API credentials before running your migration. Optionally you may also set these values from the disk extension settings in Settings > Extensions > S3 Adapter.

## Usage
After your S3 disk is set-up, usage is the same as any other disk adapter. Refer to the [pyro documentation](https://pyrocms.com/documentation/files-module/2.4/integration/laravel-filesystem)
