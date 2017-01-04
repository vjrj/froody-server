<?php

/**
 * Class FroodyEntry
 */
class FroodyEntry
{
    /** @var int $entryId Entry ** Unique ID representing the entry ID in the database */
    public $entryId;
    /** @var int $userId User.userId ** UserId that this entry belongs to. -1 if not belongs to client */
    public $userId;
    /** @var string $geohash Entry ** GeoHash of location(lat,lng) with precision &gt;&#x3D; 9 */
    public $geohash;
    /** @var string $creationDate Entry ** Timestamp of creation */
    public $creationDate;
    /** @var string $modificationDate Entry ** Timestamp of modification */
    public $modificationDate;
    /** @var int $entryType Entry ** Type of entry (e.g. pear, apple) */
    public $entryType;
    /** @var int $certificationType Entry ** Type of certification (None&#x3D;0/bio&#x3D;1/demeter&#x3D;2) */
    public $certificationType;
    /** @var int $distributionType Entry ** Type of distribution (Free&#x3D;0/Selling&#x3D;1/..) */
    public $distributionType;
    /** @var string $description Entry ** Description what is offered */
    public $description;
    /** @var string $contact Entry ** Contact informations */
    public $contact;
    /** @var string $address Entry ** Resolved address from latitude and longitude */
    public $address;
    /** @var bool $wasDeleted Entry ** True if the entry was requested for deletion */
    public $wasDeleted;
    /** @var int $managementCode Entry ** Management code, or -1 if not belongs to client */
    public $managementCode;

    /**
     * Clean froody entry extras
     */
    public function setExtrasEmpty()
    {
        $this->managementCode = -1;
        $this->userId = -1;
        $this->address = '';
        $this->contact = '';
        $this->description = '';
    }

    /**
     * Factory method for FroodyEntries. Copy factory.
     *
     * @param FroodyEntry $entry Entry to copy
     *
     * @return \FroodyEntry A copy of the passed entry
     */
    public static function create(&$entry)
    {
        $cpy = new FroodyEntry();
        $cpy->entryId = $entry->entryId;
        $cpy->userId = $entry->userId;
        $cpy->geohash = $entry->geohash;
        $cpy->creationDate = $entry->creationDate;
        $cpy->modificationDate = $entry->modificationDate;
        $cpy->entryType = $entry->entryType;
        $cpy->certificationType = $entry->certificationType;
        $cpy->distributionType = $entry->distributionType;
        $cpy->description = $entry->description;
        $cpy->contact = $entry->contact;
        $cpy->address = $entry->address;
        $cpy->wasDeleted = $entry->wasDeleted;
        $cpy->managementCode = $entry->managementCode;

        return $cpy;
    }
}
