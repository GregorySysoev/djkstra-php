<?php

declare(strict_types=1);

namespace App\Dijkstra\Entities;

use App\Tests\Dijkstra\Entities\GraphTest;

/**
 * @see GraphTest
 */
class Graph
{
    private array $points;

    public function __construct()
    {
        $this->points = [];
    }

    public function addToGraph(string $pointName): void
    {
        if (isset($this->points[$pointName])) {
            throw new \InvalidArgumentException('Вершина с данным названием уже присутствует в графе');
        }
        $this->points[$pointName] = [];
    }

    public function deleteFromGraph(string $pointName): void
    {
        if (!isset($this->points[$pointName])) {
            throw new \InvalidArgumentException('Вершина с данным названием отсутсвует в графе');
        }

        foreach ($this->points as $key => $roads) {
            if (isset($roads[$pointName])) {
                unset($this->points[$key][$pointName]);
            }
        }
        unset($this->points[$pointName]);
    }

    public function printGraph(): string
    {
        $countOfPoints = count($this->points);
        $result = "Всего в графе {$countOfPoints} вершин" . PHP_EOL;
        foreach ($this->points as $key => $roads) {
            $result .= "'{$key}': ";
            foreach ($roads as $way => $distance) {
                $result .= "'{$way}' => {$distance} ";
            }
            $result .= PHP_EOL;
        }
        return $result;
    }

    public function bindPoints(string $pointFrom, string $pointTo, int $distance): void
    {
        if ($distance <= 1) {
            throw new \InvalidArgumentException('Расстояния между вершинами должно быть не менее 1');
        }

        $this->validatePoints($pointFrom, $pointTo);

        $this->points[$pointFrom][$pointFrom] = $distance;
    }

    public function unbindPoints(string $pointFrom, string $pointTo): void
    {
        $this->validatePoints($pointFrom, $pointTo);

        if (!isset($this->points[$pointFrom][$pointTo])) {
            throw new \InvalidArgumentException("Не существует маршрута из {$pointFrom} в {$pointTo}");
        }
        unset($this->points[$pointFrom][$pointTo]);
    }

    private function validatePoints(string $pointFrom, string $pointTo)
    {
        if ($pointFrom === $pointTo) {
            throw new \InvalidArgumentException('Нельзя указывать одну и туже вершину для начала пути и конца');
        }

        if (!isset($this->points[$pointFrom])) {
            throw new \InvalidArgumentException("В графе нет вершины с названием {$pointFrom}");
        }

        if (!isset($this->points[$pointTo])) {
            throw new \InvalidArgumentException("В графе нет вершины с названием {$pointTo}");
        }
    }
}