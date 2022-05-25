<?php

namespace App\Model;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class ChristmasTree
{
    /** @var AACanvas */
    private $canvas;

    /** @var ChristmasTreeDrawer */
    private $drawer;

    /** @var (int|null|false)[] */
    private $chains;
    
    /** @var (int|null|false)[] */
    private $glassBalls;

    /** @var (int|null|false)[] */
    private $sweets;

    /** @var (int|null|false)[] */
    private $lamps;

    /** @var int|null|false */
    private $starColor;
    
    /** @var array[] */
    private $gifts;

    /** @var int */
    private $numberOfGifts;

    public function __construct(array $state = [])
    {
        $this->canvas = new AACanvas(60, 37);
        $this->drawer = new ChristmasTreeDrawer($this->canvas);

        $this->chains = $this->initializeObjectList(
            $this->drawer->getNumberOfChains(),
            $state['chains'] ?? []
        );
        $this->glassBalls = $this->initializeObjectList(
            $this->drawer->getNumberOfGlassBalls(),
            $state['glassBalls'] ?? []
        );
        $this->sweets = $this->initializeObjectList(
            $this->drawer->getNumberOfSweets(),
            $state['sweets'] ?? []
        );
        $this->lamps = $this->initializeObjectList(
            $this->drawer->getNumberOfLamps(),
            $state['lamps'] ?? []
        );
        $this->starColor = $this->getSafeColor(
            array_key_exists('starColor', $state) ? $state['starColor'] : false,
            true
        );
        $this->numberOfGifts = $this->drawer->getNumberOfGifts();
        
        $this->gifts = $this->initializeGifts($state['gifts'] ?? []);
        
        $this->redraw();
    }
    /**
     *@param array $gifts
     */
    private function initializeGifts($gifts): array
    {
        if (!is_array($gifts)) {
            return [];
        }
        
        $finalGifts = [];
        foreach ($gifts as $gift) {
            if (!is_array($gift) || !isset($gift['label']) || !is_string($gift['label'])) {
                continue;
            }
            $finalGifts[] = [
                'label' => $gift['label'],
                'packageColor' => $this->getSafeColor($gift['packageColor'] ?? null),
                'labelColor' => $this->getSafeColor($gift['labelColor'] ?? null),
            ];
            if (count($finalGifts) >= $this->numberOfGifts) {
                break;
            }
        }
        return $finalGifts;
    }
    
    public function getState(): array
    {
        return [
            "chains" => $this->chains,
            "glassBalls" => $this->glassBalls,
            "sweets" => $this->sweets,
            "lamps" => $this->lamps,
            "starColor" => $this->starColor,
            "gifts" => $this->gifts,
        ];
    }

    public function putChain(?int $color = null): self
    {
        $this->setObjectList($this->chains, null, $color);
        $this->drawChain(null);
        return $this;
    }

    public function putChainPart(int $partNumber, ?int $color = null): self
    {
        $this->setObjectList($this->chains, $partNumber, $color);
        $this->drawChain($partNumber);
        return $this;
    }

    public function removeChain(): self
    {
        $this->setObjectList($this->chains, null, false);
        $this->redraw();
        return $this;
    }

    public function removeChainPart(int $partNumber): self
    {
        $this->setObjectList($this->chains, $partNumber, false);
        $this->redraw();
        return $this;
    }

    public function putGlassBalls(?int $color = null): self
    {
        $this->setObjectList($this->glassBalls, null, $color);
        $this->drawGlassBalls(null);
        return $this;
    }

    public function putGlassBall(int $ballNumber, ?int $color = null): self
    {
        $this->setObjectList($this->glassBalls, $ballNumber, $color);
        $this->drawGlassBalls($ballNumber);
        return $this;
    }

    public function removeGlassBalls(): self
    {
        $this->setObjectList($this->glassBalls, null, false);
        $this->redraw();
        return $this;
    }

    public function removeGlassBall(int $ballNumber): self
    {
        $this->setObjectList($this->glassBalls, $ballNumber, false);
        $this->redraw();
        return $this;
    }

    public function putSweets(?int $color = null): self
    {
        $this->setObjectList($this->sweets, null, $color);
        $this->drawSweets(null);
        return $this;
    }

    public function putSweet(int $sweetNumber, ?int $color = null): self
    {
        $this->setObjectList($this->sweets, $sweetNumber, $color);
        $this->drawSweets($sweetNumber);
        return $this;
    }

    public function removeSweets(): self
    {
        $this->setObjectList($this->sweets, null, false);
        $this->redraw();
        return $this;
    }

    public function removeSweet(int $sweetNumber): self
    {
        $this->setObjectList($this->sweets, $sweetNumber, false);
        $this->redraw();
        return $this;
    }

    public function putLamps(?int $color = null): self
    {
        $this->setObjectList($this->lamps, null, $color);
        $this->drawLamps(null);
        return $this;
    }

    public function putLamp(int $lampNumber, ?int $color = null): self
    {
        $this->setObjectList($this->lamps, $lampNumber, $color);
        $this->drawLamps($lampNumber);
        return $this;
    }

    public function removeLamps(): self
    {
        $this->setObjectList($this->lamps, null, false);
        $this->redraw();
        return $this;
    }

    public function removeLamp(int $lampNumber): self
    {
        $this->setObjectList($this->lamps, $lampNumber, false);
        $this->redraw();
        return $this;
    }

    public function putStar(?int $color = null): self
    {
        $this->starColor = $color;
        $this->drawStar();
        return $this;
    }

    public function removeStar(): self
    {
        $this->starColor = false;
        $this->redraw();
        return $this;
    }

    public function putGift(string $label, ?int $packageColor = null, ?int $labelColor = null): self
    {
        $this->gifts[] = [
            'label' => $label,
            'packageColor' => $packageColor,
            'labelColor' => $labelColor,
        ];
        while (count($this->gifts) > $this->numberOfGifts) {
            array_shift($this->gifts);
        }
        $this->drawGifts();
        return $this;
    }

    public function removeGifts(): self
    {
        $this->gifts = [];
        $this->redraw();
        return $this;
    }

    public function removeGift(int $giftNumber): self
    {
        if (isset($this->gifts[$giftNumber])) {
            array_splice($this->gifts, $giftNumber, 1);
        }
        $this->redraw();
        return $this;
    }
    /**
     *@param mixed $stateValues
     */
    private function initializeObjectList(int $number, $stateValues): array
    {
        if (!is_array($stateValues)) {
            $stateValues = [];
        }
        $list = [];
        for ($i = 0; $i < $number; $i++) {
            $list[] = array_key_exists($i, $stateValues) ? $this->getSafeColor($stateValues[$i], true) : false;
        }
        return $list;
    }

    private function iterateObjectList(array &$list, ?int $number): iterable
    {
        if ($number === null) {
            foreach ($list as $i => $v) {
                yield $i => $v;
            }
        } else {
            $number = $this->modulo($number, count($list));
            yield $number => $list[$number];
        }
    }

    /**
     * @param array $list
     * @param int|null $number
     * @param mixed $value
     * @return (int|null|false)[]
     */
    private function setObjectList(array &$list, ?int $number, $value): array
    {
        if ($number === null) {
            foreach (array_keys($list) as $i) {
                $list[$i] = $value;
            }
        } else {
            $number = $this->modulo($number, count($list));
            $list[$number] = $value;
        }
        return $list;
    }

    private function modulo(int $divident, int $divisor): int
    {
        if ($divident < 0) {
            return $divisor - ((-$divident) % $divisor);
        } else {
            return $divident % $divisor;
        }
    }

    /**
     * @param mixed $color
     * @param bool $allowFalse
     * @return int|null|false
     */
    private function getSafeColor($color, bool $allowFalse = false)
    {
        if ($allowFalse && $color === false) {
            return false;
        }
        if ($color === null || !is_int($color)) {
            return null;
        }
        return $this->modulo($color, 16);
    }


    private function redraw(): void
    {
        $this->canvas->clear();
        $this->drawTree();
        $this->drawChain();
        $this->drawGlassBalls();
        $this->drawSweets();
        $this->drawLamps();
        $this->drawStar();
        $this->drawGifts();
    }

    private function drawGifts(): void
    {
        foreach ($this->gifts as $i => $giftDescriptor) {
            $this->canvas->setColor($this->getSafeColor($giftDescriptor['packageColor']));
            $this->drawer->drawGift($i);
            $this->canvas->setColor($this->getSafeColor($giftDescriptor['labelColor']));
            $this->drawer->drawGiftLabel($giftDescriptor['label'], $i);
        }
    }

    private function drawTree(): void
    {
        $this->canvas->setColor(2);
        $this->drawer->drawTree();
        $this->canvas->setColor(3);
        $this->drawer->drawRoot();
        $this->canvas->setColor(null);
    }

    private function drawChain(?int $number = null): void
    {
        foreach ($this->iterateObjectList($this->chains, $number) as $i => $color) {
            if ($color !== false) {
                $this->canvas->setColor($this->getSafeColor($color));
                $this->drawer->drawChain($i);
            }
        }
        $this->canvas->setColor(null);
    }

    private function drawGlassBalls(?int $number = null): void
    {
        foreach ($this->iterateObjectList($this->glassBalls, $number) as $i => $color) {
            if ($color !== false) {
                $this->canvas->setColor($this->getSafeColor($color));
                $this->drawer->drawGlassBalls($i);
            }
        }
        $this->canvas->setColor(null);
    }

    private function drawSweets(?int $number = null): void
    {
        foreach ($this->iterateObjectList($this->sweets, $number) as $i => $color) {
            if ($color !== false) {
                $this->canvas->setColor($this->getSafeColor($color));
                $this->drawer->drawSweets($i);
            }
        }
        $this->canvas->setColor(null);
    }

    private function drawLamps(?int $number = null): void
    {
        foreach ($this->iterateObjectList($this->lamps, $number) as $i => $color) {
            if ($color !== false) {
                $this->canvas->setColor($this->getSafeColor($color));
                $this->drawer->drawLamps($i);
            }
        }
        $this->canvas->setColor(null);
    }

    private function drawStar(): void
    {
        if ($this->starColor !== false) {
            $this->canvas->setColor($this->getSafeColor($this->starColor));
            $this->drawer->drawStar();
            $this->canvas->setColor(null);
        }
    }

    public function getTerminalOutput(int $rows, int $cols, bool $redraw): string
    {
        return $this->canvas->getTerminalOutput($rows, $cols, $redraw);
    }

    public function getTerminalClear(): self
    {
        $this->canvas->getTerminalClear();
        return $this;
    }
}
