<?php


namespace App\Mock;


class DeveloperMock
{
    /**
     * Mock dev list
     *
     * @param int $devCount
     *
     * @return iterable
     */
    public static function list($devCount = 5): array
    {
        $devList = [];
        foreach (range(1, $devCount) as $index) {
            $devList[] = [
                'name' => 'dev' . $index,
                'workUnit' => $index,
                'workLevel' => $index,
                'dailyWorkHours' => 9
            ];
        }
        return $devList;
    }
}