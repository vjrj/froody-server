<?php

/**
 * Class Geohash
 */
class Geohash
{
    /*
     * Constants and statics
     */
    const BASE32              = '0123456789bcdefghjkmnpqrstuvwxyz';
    const BASE32_NOT_INCLUDED = 'ailo';
    const ADJ_NEIGHBOUR       = [
        'n' => ['p0r21436x8zb9dcf5h7kjnmqesgutwvy', 'bc01fg45238967deuvhjyznpkmstqrwx'],
        's' => ['14365h7k9dcfesgujnmqp0r2twvyx8zb', '238967debc01fg45kmstqrwxuvhjyznp'],
        'e' => ['bc01fg45238967deuvhjyznpkmstqrwx', 'p0r21436x8zb9dcf5h7kjnmqesgutwvy'],
        'w' => ['238967debc01fg45kmstqrwxuvhjyznp', '14365h7k9dcfesgujnmqp0r2twvyx8zb'],
    ];
    const ADJ_BORDER          = [
        'n' => ['prxz', 'bcfguvyz'],
        's' => ['028b', '0145hjnp'],
        'e' => ['bcfguvyz', 'prxz'],
        'w' => ['0145hjnp', '028b'],
    ];

    /**
     * @var string
     */
    private $geohash;

    /**
     * Geohash constructor
     *
     * @param string $geohash
     */
    public function __construct($geohash)
    {
        $this->geohash = strtolower($geohash);
    }

    /**
     * Check if geohash contains the passed string
     *
     * @param string $str
     *
     * @return bool
     */
    private function contains($str)
    {
        return strpos($this->geohash, $str) !== false;
    }

    /**
     * Get the geohash's precision / length
     *
     * @return int
     */
    public function getPrecision()
    {
        return strlen($this->geohash);
    }

    /**
     * Check if this geohash is valid
     *
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->geohash)) {
            return false;
        }
        if (!ctype_alnum($this->geohash)) {
            return false;
        }

        return !$this->contains('a') && !$this->contains('i') && !$this->contains('l') && !$this->contains('o');
    }

    /**
     * Check if the geohash is valid and has a minimum precision
     *
     * @param int $min
     *
     * @return bool
     */
    public function isValidAndHasPrecision($min)
    {
        return $this->isValid() && $this->hasMinPrecision($min);
    }

    /**
     * Check if the geohash has a minimum precision
     *
     * @param int $min
     *
     * @return bool
     */
    public function hasMinPrecision($min)
    {
        return $min >= 0 && $this->getPrecision() >= (int) $min;
    }

    /**
     * Get geohash with the passed precision
     *
     * @param int $precision
     *
     * @return string
     */
    public function getWithMaxPrecision($precision)
    {
        if ($this->hasMinPrecision($precision)) {
            return substr($this->geohash, 0, $precision);
        }

        return $this->geohash;
    }

    /**
     * Get adjacent geohash
     *
     * @param string $geohash
     * @param string $direction
     *
     * @return string
     * @throws \Exception
     */
    public function getAdjacent($geohash, $direction)
    {
        // based on https://github.com/chrisveness/latlon-geohash/blob/master/latlon-geohash.js
        if (empty($geohash) || empty($direction)) {
            throw new Exception('Invalid geohash or dir');
        }

        $geohash = strtolower($geohash);
        $direction = strtolower($direction);

        $lastCh = substr($geohash, -1);    // last character of hash
        $parent = substr($geohash, 0, -1); // hash without last character
        $type = strlen($geohash) % 2;

        // check for edge-cases which don't share common prefix
        if (strpos(self::ADJ_BORDER[$direction][$type], $lastCh) !== false && strcmp($parent, '')) {
            $parent = $this->getAdjacent($parent, $direction);
        }

        // append letter for direction to parent
        $base32Index = strpos(self::ADJ_NEIGHBOUR[$direction][$type], $lastCh);

        return $parent . self::BASE32[$base32Index];
    }

    /**
     * Get this geohash's named neighbours
     *
     * @return array
     */
    public function getNeighboursNamed()
    {
        $adjN = $this->getAdjacent($this->geohash, 'n');
        $adjS = $this->getAdjacent($this->geohash, 's');

        return [
            'n'  => $adjN,
            'ne' => $this->getAdjacent($adjN, 'e'),
            'e'  => $this->getAdjacent($this->geohash, 'e'),
            'se' => $this->getAdjacent($adjS, 'e'),
            's'  => $adjS,
            'sw' => $this->getAdjacent($adjS, 'w'),
            'w'  => $this->getAdjacent($this->geohash, 'w'),
            'nw' => $this->getAdjacent($adjN, 'w'),
        ];
    }

    /**
     * Get this geohash's neighbours
     *
     * @return array
     */
    public function getNeighbours()
    {
        $adjN = $this->getAdjacent($this->geohash, 'n');
        $adjS = $this->getAdjacent($this->geohash, 's');

        return [
            $adjN,
            $this->getAdjacent($adjN, 'e'),
            $this->getAdjacent($this->geohash, 'e'),
            $this->getAdjacent($adjS, 'e'),
            $adjS,
            $this->getAdjacent($adjS, 'w'),
            $this->getAdjacent($this->geohash, 'w'),
            $this->getAdjacent($adjN, 'w'),
        ];
    }

    /**
     * Get the geohash
     *
     * @return string
     */
    public function getGeohash()
    {
        return $this->geohash;
    }

    /**
     * Set the geohash
     *
     * @param string $geohash
     *
     * @return Geohash
     */
    public function setGeohash($geohash)
    {
        $this->geohash = strtolower($geohash);

        return $this;
    }
}
