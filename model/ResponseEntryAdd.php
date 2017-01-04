<?php

/**
 * Class ResponseEntryAdd
 */
class ResponseEntryAdd
{
    /** @var int $entryId Entry.entryId ** UID of the entry, which was added to DB */
    public $entryId;
    /** @var int $managementCode Entry.ManagementCode ** Needed for deleting and managing entry */
    public $managementCode;
    /** @var string $creationDate Entry ** Timestamp of creation */
    public $creationDate;
}
