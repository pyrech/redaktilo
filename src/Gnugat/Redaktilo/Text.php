<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

/**
 * Redaktilo's base entity representing a collection of lines: each line is
 * stripped from its line break character.
 * This character is centralized in a property.
 *
 * When Text is created, the current line number is set to 0.
 *
 * @api
 */
class Text
{
    /**
     * @var array
     */
    protected $lines;

    /**
     * @var int
     */
    protected $totalLineNumber;

    /**
     * @var string
     */
    protected $lineBreak;

    /**
     * @var int
     */
    protected $currentLineNumber = 0;

    /**
     * @param array  $lines
     * @param string $lineBreak
     */
    public function __construct(array $lines, $lineBreak = PHP_EOL)
    {
        $this->setLines($lines);
        $this->lineBreak = $lineBreak;
    }

    /**
     * @return array
     *
     * @api
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param array $lines
     *
     * @api
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;
        $this->totalLineNumber = count($lines);
    }

    /**
     * @return string
     *
     * @api
     */
    public function getLineBreak()
    {
        return $this->lineBreak;
    }

    /**
     * @param string $lineBreak
     *
     * @api
     */
    public function setLineBreak($lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    /**
     * @return int
     *
     * @api
     */
    public function getCurrentLineNumber()
    {
        return $this->currentLineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @throws \InvalidArgumentException if $lineNumber is not an integer
     * @throws \InvalidArgumentException if $lineNumber is negative
     * @throws \InvalidArgumentException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function setCurrentLineNumber($lineNumber)
    {
        if (!is_int($lineNumber)) {
            throw new \InvalidArgumentException('The line number should be an integer');
        }
        if ($lineNumber < 0) {
            throw new \InvalidArgumentException('The line number should be positive');
        }
        if ($lineNumber >= $this->totalLineNumber) {
            throw new \InvalidArgumentException('The line number should be strictly lower than the number of lines');
        }
        $this->currentLineNumber = $lineNumber;
    }
}
