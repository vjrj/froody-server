<?php

namespace \Geo;

class GeohashParamBased {
	const BASE32 = '0123456789bcdefghjkmnpqrstuvwxyz';
	const ADJ_NEIGHBOUR = [
			'n' => [ 'p0r21436x8zb9dcf5h7kjnmqesgutwvy', 'bc01fg45238967deuvhjyznpkmstqrwx' ],
			's' => [ '14365h7k9dcfesgujnmqp0r2twvyx8zb', '238967debc01fg45kmstqrwxuvhjyznp' ],
			'e' => [ 'bc01fg45238967deuvhjyznpkmstqrwx', 'p0r21436x8zb9dcf5h7kjnmqesgutwvy' ],
			'w' => [ '238967debc01fg45kmstqrwxuvhjyznp', '14365h7k9dcfesgujnmqp0r2twvyx8zb' ]
	];
	const ADJ_BORDER = [
			'n'=> [ 'prxz',     'bcfguvyz' ],
			's'=> [ '028b',     '0145hjnp' ],
			'e'=> [ 'bcfguvyz', 'prxz'     ],
			'w'=> [ '0145hjnp', '028b'     ]
	];
	
	

	public function getAdjacent($geohash, $direction) {
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

	public function getNeighbours($geohash) {
		// based on https://github.com/chrisveness/latlon-geohash/blob/master/latlon-geohash.js
		$adjN = $this->getAdjacent($geohash, 'n');
		$adjS = $this->getAdjacent($geohash, 's');
		return [
			'n' => $adjN,
			'ne'=> $this->getAdjacent($adjN, 'e'),
			'e' => $this->getAdjacent($geohash, 'e'),
			'se'=> $this->getAdjacent($adjS, 'e'),
			's' => $adjS,
			'sw'=> $this->getAdjacent($adjS, 'w'),
			'w' => $this->getAdjacent($geohash, 'w'),
			'nw'=> $this->getAdjacent($adjN, 'w'),
		];
	}
	
	public function getNeighbours2($geohash) {
		$adjN = $this->getAdjacent($geohash, 'n');
		$adjS = $this->getAdjacent($geohash, 's');
		return [
			$adjN,
			$this->getAdjacent($adjN, 'e'),
			$this->getAdjacent($geohash, 'e'),
			$this->getAdjacent($adjS, 'e'),
			$adjS,
			$this->getAdjacent($adjS, 'w'),
			$this->getAdjacent($geohash, 'w'),
			$this->getAdjacent($adjN, 'w'),
		];
	}
}

?>
