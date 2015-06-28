<?php namespace Anomaly\S3StorageAdapterExtension;

use Anomaly\FilesModule\Adapter\AdapterExtension;

/**
 * Class S3StorageAdapterExtension
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\S3StorageAdapterExtension
 */
class S3StorageAdapterExtension extends AdapterExtension
{

    /**
     * This module provides the s3
     * storage adapter for the files module.
     *
     * @var string
     */
    protected $provides = 'anomaly.module.files::adapter.s3';

}
