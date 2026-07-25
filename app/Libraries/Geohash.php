<?php

namespace App\Libraries;

class Geohash
{
    private const BASE32    = '0123456789bcdefghjkmnpqrstuvwxyz';
    private const PRECISION = 5; // ~4.9km × 4.9km cells

    private const NEIGHBORS = [
        'right'  => ['even' => 'bc01fg45238967deuvhjyznpkmstqrwx', 'odd' => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy'],
        'left'   => ['even' => '238967debc01fg45uvhjyznpkmstqrwx', 'odd' => '14365h7k9dcfesgujnmqp0r2twvyx8zb'],
        'top'    => ['even' => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy', 'odd' => 'bc01fg45238967deuvhjyznpkmstqrwx'],
        'bottom' => ['even' => '14365h7k9dcfesgujnmqp0r2twvyx8zb', 'odd' => '238967debc01fg45uvhjyznpkmstqrwx'],
    ];

    private const BORDERS = [
        'right'  => ['even' => 'bcfguvyz', 'odd' => 'prxz'],
        'left'   => ['even' => '0145hjnp', 'odd' => '028b'],
        'top'    => ['even' => 'prxz',     'odd' => 'bcfguvyz'],
        'bottom' => ['even' => '028b',     'odd' => '0145hjnp'],
    ];

    /**
     * Encode a lat/lng pair into a geohash string.
     * Precision 5 → ~4.9km × 4.9km cells.
     */
    public static function encode(float $lat, float $lng, int $precision = self::PRECISION): string
    {
        $idx    = 0;
        $bit    = 0;
        $even   = true;
        $hash   = '';
        $minLat = -90.0;  $maxLat = 90.0;
        $minLng = -180.0; $maxLng = 180.0;

        while (strlen($hash) < $precision) {
            if ($even) {
                $mid = ($minLng + $maxLng) / 2;
                if ($lng >= $mid) { $idx = ($idx << 1) | 1; $minLng = $mid; }
                else              { $idx = ($idx << 1);      $maxLng = $mid; }
            } else {
                $mid = ($minLat + $maxLat) / 2;
                if ($lat >= $mid) { $idx = ($idx << 1) | 1; $minLat = $mid; }
                else              { $idx = ($idx << 1);      $maxLat = $mid; }
            }

            $even = !$even;

            if (++$bit === 5) {
                $hash .= self::BASE32[$idx];
                $bit = $idx = 0;
            }
        }

        return $hash;
    }

    /**
     * Return the 3×3 grid of geohash cells surrounding a given hash.
     * At precision 5, this covers a ~14.7km bounding box — wider than
     * our 30km target, so the Haversine fine-filter trims the edges.
     *
     * @return string[] 9 geohash strings
     */
    public static function getNeighborhood(string $hash): array
    {
        $top    = self::adjacent($hash, 'top');
        $bottom = self::adjacent($hash, 'bottom');
        $right  = self::adjacent($hash, 'right');
        $left   = self::adjacent($hash, 'left');

        return [
            $hash,                              // center
            $top,                               // N
            $bottom,                            // S
            $right,                             // E
            $left,                              // W
            self::adjacent($top,    'right'),   // NE
            self::adjacent($top,    'left'),    // NW
            self::adjacent($bottom, 'right'),   // SE
            self::adjacent($bottom, 'left'),    // SW
        ];
    }

    private static function adjacent(string $hash, string $direction): string
    {
        $hash = strtolower($hash);
        $last = substr($hash, -1);
        $type = (strlen($hash) % 2) ? 'odd' : 'even';
        $base = substr($hash, 0, -1);

        if ($base !== '' && strpos(self::BORDERS[$direction][$type], $last) !== false) {
            $base = self::adjacent($base, $direction);
        }

        $neighborStr = self::NEIGHBORS[$direction][$type];
        $pos         = strpos($neighborStr, $last);

        return $base . self::BASE32[$pos];
    }
}
