<?php declare(strict_types=1);
namespace Smeghead\PhpClassDiagram\DiagramElement;

class PackageArrow {
    private string $from;
    private string $to;
    private bool $bothSideArrow = false;

    public function __construct(string $from, string $to) {
        $this->from = $from;
        $this->to = $to;
    }

    public function bothSideArrow() {
        $this->bothSideArrow = true;
    }

    public function isOpposite(self $other) {
        if ($this->from !== $other->to) {
            return false;
        }
        if ($this->to !== $other->from) {
            return false;
        }
        return true;
    }

    public function toString(): string {
        $format = $this->bothSideArrow
            ? '  %s <-[#red,plain,thickness=4]-> %s'
            : '  %s --> %s';
        return sprintf($format, $this->from, $this->to);
    }
}
