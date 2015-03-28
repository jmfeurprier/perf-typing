<?php

namespace perf\Typing;

/**
 *
 *
 */
class TypeSpecificationParser
{

    /**
     *
     *
     * @param string $typeSpecification
     * @return string[]
     * @throws InvalidTypeSpecificationException
     */
    public function parse($typeSpecification)
    {
        if (!is_string($typeSpecification)) {
            throw new InvalidTypeSpecificationException('Invalid type specification provided (expected string).');
        }

        $splitPoints = $this->getSplitPoints($typeSpecification);

        return $this->split($typeSpecification, $splitPoints);
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @return int[]
     * @throws InvalidTypeSpecificationException
     */
    private function getSplitPoints($typeSpecification)
    {
        $length = strlen($typeSpecification);

        $depth       = 0;
        $splitPoints = array();

        for ($offset = 0; $offset < $length; ++$offset) {
            $character = $typeSpecification{$offset};

            if ('{' === $character) {
                ++$depth;
            }

            if ('}' === $character) {
                --$depth;

                if ($depth < 0) {
                    throw new InvalidTypeSpecificationException();
                }
            }

            if ('|' === $character) {
                if (0 === $depth) {
                    $splitPoints[] = $offset;
                }
            }
        }

        if (0 !== $depth) {
            throw new InvalidTypeSpecificationException();
        }

        $splitPoints[] = $length;

        return $splitPoints;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param int[] $splitPoints
     * @return string[]
     * @throws InvalidTypeSpecificationException
     */
    private function split($typeSpecification, array $splitPoints)
    {
        $offsetStart = 0;
        $chunks      = array();

        foreach ($splitPoints as $splitPoint) {
            $offsetEnd   = ($splitPoint - 1);
            $chunk       = substr($typeSpecification, $offsetStart, ($offsetEnd - $offsetStart + 1));
            $chunks[]    = $chunk;
            $offsetStart = ($splitPoint + 1);
        }

        return $chunks;
    }
}
