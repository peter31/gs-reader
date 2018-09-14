<?php declare(strict_types=1);

namespace App;

use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;

class Caller
{
    /** @var StorageClient */
    private $storage;

    public function __construct()
    {
//        $server = 'staging';
        $server = 'staging-new';
//        $server = 'id-prod';
//        $server = 'br-prod';

        $this->storage = new StorageClient([
            'keyFilePath' => sprintf('%s/%s.json', dirname(dirname(__DIR__)), $server),
        ]);
    }

    public function showBuckets()
    {
        /** @var Bucket $bucket */
        echo('----- Buckets -----' . PHP_EOL);
        foreach ($this->storage->buckets() as $bucket) {
            echo($bucket->name() . PHP_EOL);
        }
        echo('-------------------' . PHP_EOL);
    }

    public function showBucket(string $bucketName)
    {
        $bucket = $this->storage->bucket($bucketName);

        echo('----- Bucket "' . $bucketName . '" objects -----' . PHP_EOL);

        /** @var StorageObject $object */
        foreach ($bucket->objects() as $object) {
            echo($object->name() . PHP_EOL);
        }
        echo('------------------------------------------------' . PHP_EOL);
    }

    public function showObject(string $bucketName, string $objectPath)
    {
        $bucket = $this->storage->bucket($bucketName);

        echo('----- Bucket "' . $bucketName . '" objects -----' . PHP_EOL);

        $objectContent = $bucket->object($objectPath)->downloadAsString();

        $jsonData = \json_decode($objectContent, true);
        if (\json_last_error() === JSON_ERROR_NONE) {
            print_r($jsonData);
//            print_r(array_column($jsonData, 'total_value', 'id'));
        } else {
            echo($objectContent . PHP_EOL);
        }

        echo('------------------------------------------------' . PHP_EOL);
    }

    public function showHelp()
    {
        echo(
            '### Available commands ###' . PHP_EOL . PHP_EOL .
            'list - show buckets list' . PHP_EOL .
            'bucket <bucket-name>: view bucket details' . PHP_EOL .
            'object <object-name>: view object content' . PHP_EOL . PHP_EOL
        );
    }
}
