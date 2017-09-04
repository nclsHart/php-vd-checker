<?php

use function DI\object;
use function DI\get;
use PhpVdChecker\Command\CheckCommand;
use PhpVdChecker\Rule\ClassShouldNotImplementsInterfaceRule;
use PhpVdChecker\Rule\InterfaceShouldNotExistRule;
use PhpVdChecker\Rule\NoDocumentationRule;
use Symfony\Component\Console\Application;

return [
    'rules' => [
        get(ClassShouldNotImplementsInterfaceRule::class),
        get(InterfaceShouldNotExistRule::class),
        get(NoDocumentationRule::class),
    ],

    PhpVdChecker\RuleExecutor::class => object()
        ->constructorParameter('rules', get('rules')),

    'application' => object(Application::class)
        ->method('add', get(CheckCommand::class)),
];