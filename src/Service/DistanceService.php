<?php

namespace App\Service;

class DistanceService
{
    public static function degreesToRadians($degrees)
    {
        return $degrees * pi() / 180;
    }

    public static function distanceInKmBetweenEarthCoordinates($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadiusKm = 6371;

        $dLat = self::degreesToRadians($lat2 - $lat1);
        $dLon = self::degreesToRadians($lon2 - $lon1);

        $lat1 = self::degreesToRadians($lat1);
        $lat2 = self::degreesToRadians($lat2);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadiusKm * $c;
    }

    //	Radius of earth.  3959 miles or 6371 kilometers.  Must set radius to units you are using, in my case, miles.

    //  Pass in coordinates in Decimal form.  Example: -41.5786214
    // A bearing of 0 is due north, 90 is due east, 180 due south, 270 due west, and anything in between.

    public static function newCoordonatesAddAngleAndDistance($latitude, $longitude, $bearing, $distance, ?string $unit = 'km'): array
    {
        if ($unit == 'm') {
            $radius = 3959;
        } elseif ($unit == 'km') {
            $radius = 6371;
        }

        //	New latitude in degrees.
        $new_latitude = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));

        //	New longitude in degrees.
        $new_longitude = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($new_latitude))));

        //  Assign new latitude and longitude to an array to be returned to the caller.
        $coord['latitude'] = round($new_latitude, 8);
        $coord['longitude'] = round($new_longitude, 8);

        // return $coord['latitude'] . ', ' . $coord['longitude'];
        return $coord;
    }

    // Points cardinaux égloignés de $distance 
    public static function cardinalCoordonatesDistanceFromPoint($latitude, $longitude, $distance): array
    {
        $directions = ['N' => 0, 'E' => 90, 'S' => 180, 'W' => 270];
        $losange = [];
        foreach ($directions as $key => $directions) {
            $losange[$key] = self::newCoordonatesAddAngleAndDistance($latitude, $longitude, $directions, $distance, 'km');
        }

        return $losange;
    }

    // Delete trips in corners of square search, where distance > $distance
    public static function checkDistance(array $results, float $lat, float $lng, int $distance): array
    {
        $trips = [];
        foreach ($results as $trip) {
            $dist = DistanceService::distanceInKmBetweenEarthCoordinates(
                $trip->getLocation()->getLatitude(),
                $trip->getLocation()->getLongitude(),
                $lat,
                $lng
            );

            if ($dist < $distance) {
                $trips[] = $trip;
            }
        }
        return $trips;
    }
}
