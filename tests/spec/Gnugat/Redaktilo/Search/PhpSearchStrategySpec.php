<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Converter\PhpContentConverter;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Search\Php\Token;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use PhpSpec\ObjectBehavior;

class PhpSearchStrategySpec extends ObjectBehavior
{
    const CLASS_NAME = 'AppKernel';
    const METHOD_NAME = 'registerBundles';
    const FILENAME = '%s/tests/fixtures/sources/AppKernel.php';

    private $tokenBuilder;

    function let(File $file)
    {
        $rootPath = __DIR__.'/../../../../..';
        $filename = sprintf(self::FILENAME, $rootPath);
        $content = file_get_contents($filename);
        $file->getFilename()->willReturn($filename);
        $file->read()->willReturn($content);

        $this->tokenBuilder = new TokenBuilder();
        $converter = new PhpContentConverter($this->tokenBuilder);

        $this->beConstructedWith($converter);
    }

    function it_is_a_search_strategy()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Search\SearchStrategy');
    }

    function it_supports_php_criterion()
    {
        $rawTokens = token_get_all('<?php echo 42;');
        $tokens = $this->tokenBuilder->buildFromRaw($rawTokens);
        $emptyArray = array();
        $rawLine = "Sir Bedevere: Good. Now, why do witches burn?\n";
        $lineNumber = 42;
        $rubishArray = array(23, 1337);

        $this->supports($tokens)->shouldBe(true);
        $this->supports($emptyArray)->shouldBe(true);
        $this->supports($rawLine)->shouldBe(false);
        $this->supports($rubishArray)->shouldBe(false);
        $this->supports($lineNumber)->shouldBe(false);
    }

    function it_finds_previous_occurences(File $file)
    {
        $previousLineNumber = 0;
        $previousToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $nextToken = $this->tokenBuilder->buildMethod('registerBundles');

        $this->findPrevious($file, $nextToken, $currentLineNumber)->shouldBe(false);
        $this->findPrevious($file, $currentToken, $currentLineNumber)->shouldBe(false);
        $this->findPrevious($file, $previousToken, $currentLineNumber)->shouldBe($previousLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findPrevious($file, $nextToken)->shouldBe(false);
        $this->findPrevious($file, $currentToken)->shouldBe(false);
        $this->findPrevious($file, $previousToken)->shouldBe($previousLineNumber);
    }

    function it_finds_next_occurences(File $file)
    {
        $previousToken = array(new Token(T_OPEN_TAG, "<?php\n"));
        $currentLineNumber = 10;
        $currentToken = $this->tokenBuilder->buildClass('AppKernel');
        $nextLineNumber = 15;
        $nextToken = $this->tokenBuilder->buildMethod('registerBundles');

        $this->findNext($file, $previousToken, $currentLineNumber)->shouldBe(false);
        $this->findNext($file, $currentToken, $currentLineNumber)->shouldBe(false);
        $this->findNext($file, $nextToken, $currentLineNumber)->shouldBe($nextLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findNext($file, $previousToken)->shouldBe(false);
        $this->findNext($file, $currentToken)->shouldBe(false);
        $this->findNext($file, $nextToken)->shouldBe($nextLineNumber);
    }
}
