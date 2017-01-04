<?php

/**
 * Class ResponseOk
 */
class ResponseOk
{
    /** @var bool $success true if successful, false if not */
    public $success;

    /**
     * Create ResponseOk object
     *
     * @param bool $success
     *
     * @return \ResponseOk
     */
    public static function create($success)
    {
        $v = new ResponseOk();
        $v->success = $success;

        return $v;
    }
}
