<?php

/**
 * Class BlockInfo
 */
class BlockInfo
{
    /** @var string $geohash BlockInfo ** GeoHash of location(lat,lng) */
    public $geohash;
    /** @var string $modificationDate BlockInfo ** Timestamp of last modification of entries in this block */
    public $modificationDate;
    /** @var bool $hasBlockBeenModified BlockInfo ** True if the block has changed since requested min modification time */
    public $hasBlockBeenModified;

    /**
     * Create BlockInfo object with passed geohash
     *
     * @param string $geohash
     *
     * @return \BlockInfo
     */
    public static function create($geohash)
    {
        $v = new BlockInfo();
        $v->geohash = $geohash;

        return $v;
    }
}
