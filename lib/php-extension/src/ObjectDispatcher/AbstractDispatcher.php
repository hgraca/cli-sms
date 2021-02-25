<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used in a workshop to explain Architecture patterns.
 *
 * Most of it authored by Herberto Graca.
 */

namespace Acme\PhpExtension\ObjectDispatcher;

abstract class AbstractDispatcher
{
    /**
     * @var Destination[][]
     */
    private $objectDestinationMapper;

    public function addDestination(
        string $messageName,
        callable $destinationCallable,
        int $priority = 0
    ): void {
        $destinationList = $this->objectDestinationMapper[$messageName] ?? [];
        $order = $this->findIndexForInsertion($priority, ...$destinationList);
        $this->objectDestinationMapper[$messageName] = $this->insertDestinationInIndex(
            $destinationList,
            $destinationCallable,
            $priority,
            $order
        );
    }

    public function hasDestination(string $messageName): bool
    {
        return array_key_exists($messageName, $this->objectDestinationMapper)
            && !empty($this->objectDestinationMapper[$messageName]);
    }

    /**
     * @return callable[]
     */
    protected function getDestinationListForObject(string $messageName): array
    {
        $destinationList = $this->getDestinationDefinitionListForObject($messageName);

        foreach ($destinationList as $destination) {
            $receiverList[] = $destination->getReceiver();
        }

        return $receiverList ?? [];
    }

    /**
     * @return Destination[]
     */
    private function getDestinationDefinitionListForObject(string $messageName): array
    {
        return $this->objectDestinationMapper[$messageName] ?? [];
    }

    private function findIndexForInsertion(int $priority, Destination ...$destinationList): int
    {
        $i = 0;
        do {
            $destination = $destinationList[$i++] ?? null;
        } while ($destination !== null && $destination->getPriority() >= $priority);

        return --$i;
    }

    /**
     * @param Destination[] $destinationList
     */
    private function insertDestinationInIndex(array $destinationList, callable $destinationCallable, int $priority, $i): array
    {
        array_splice($destinationList, $i, 0, [new Destination($destinationCallable, $priority)]);

        return $destinationList;
    }
}
