<?php

namespace PhpVdChecker\Command;

use PhpParser\ParserFactory;
use PhpVdChecker\Error;
use PhpVdChecker\NodeTraverser;
use PhpVdChecker\Reporter;
use PhpVdChecker\Scope;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

class CheckCommand extends Command
{
    /**
     * @var NodeTraverser
     */
    private $traverser;

    /**
     * @var Reporter
     */
    private $reporter;

    /**
     * @param NodeTraverser $traverser
     * @param Reporter $reporter
     */
    public function __construct(NodeTraverser $traverser, Reporter $reporter)
    {
        $this->traverser = $traverser;
        $this->reporter = $reporter;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('check')
            ->setDefinition([
                new InputArgument('path', InputArgument::REQUIRED),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf('Given path "%s" does not exist', $path));
        }

        $files = [];
        if (is_file($path)) {
            $files[] = $path;
        } else {
            $finder = new Finder();
            $finder->followLinks();

            foreach ($finder->files()->name('*.php')->in($path) as $file) {
                $files[] = $file;
            }
        }

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        foreach ($files as $file) {
            $nodes = $parser->parse(file_get_contents($file->getPathname()));

            $this->traverser->traverse($nodes, new Scope($file));
        }

        $nbErrors = $this->reporter->countErrors();
        if (!$nbErrors) {
            return 0;
        }

        foreach ($files as $file) {
            $io->table(['Line', $file], array_map(function (Error $error) {
                return [$error->getLine(), $error->getMessage()];
            }, $this->reporter->getErrorsForFile($file)));
        }

        $io->error(sprintf('Found %d errors', $this->reporter->countErrors()));

        return 1;
    }
}