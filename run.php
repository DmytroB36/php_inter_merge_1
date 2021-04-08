<?php

include('data.php');

/** @var $data array */

$startTime = microtime(true);

/** @var $merged
 *
 * Should contain arrays of IDs, merged if each of the groups has intersections in another record.
 *    [
 *      [1, 2, 3],
 *      [4, 5, 6, 7],
 *      ...
 *     ]
 */
$merged = [];

while (count($data) > 0) {

    $mainItem = array_shift($data); // Take item from beginning of Array

    $tmpRes = $mainItem['id_list']; // Add ID-s to the future result

    foreach ($data as &$secondaryItem) { // Loop through all other elements to add an ID-s to the tmpRes

        // A sign that each group of the main element intersects with at least one group of the secondary element
        $allGroupsIntersect = true;

        /*
         *  $mainItem['groups'] -  [[1, 2], [1,3], [4], ...]
         *  $mainGroup - [1, 2]
         */
        foreach ($mainItem['groups'] as &$mainGroup) {

            $groupsIntersect = false;

            /*
             *  $secondaryItem['groups'] - [[1, 2], [1,3], [4], ...]
             *  $secondaryGroup - [1, 2]
             */
            foreach ($secondaryItem['groups'] as &$secondaryGroup) {

                if (array_intersect($mainGroup, $secondaryGroup)) {
                    $groupsIntersect = true;
                    break;
                }

            }

            if (!$groupsIntersect) {
                $allGroupsIntersect = false;
            }
        }

        if ($allGroupsIntersect) {
            $tmpRes = array_merge($tmpRes, $secondaryItem['id_list']);
        }

    }

    $merged[] = array_unique($tmpRes);
}

$finishTime = microtime(true) - $startTime;

file_put_contents('result.json', json_encode($merged, JSON_PRETTY_PRINT));
echo "\nFinished at " . $finishTime . " s.";