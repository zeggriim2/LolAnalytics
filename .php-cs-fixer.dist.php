<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                        => true,
        '@Symfony'                      => true,
        '@Symfony:risky'                => true,
        '@PHP80Migration:risky'         => true,
        'array_syntax'                  => ['syntax' => 'short'],
        'concat_space'                  => ['spacing' => 'one' ],
        'linebreak_after_opening_tag'   => true,
        'mb_str_functions'              => true,
        'no_php4_constructor'           => true,
        'no_useless_else'               => true,
        'no_useless_return'             => true,
        'no_useless_sprintf'            => true,
        'ordered_imports'               => true,
        'phpdoc_order'                  => true,
        'semicolon_after_instruction'   => true,
        'strict_comparison'             => true,
        'strict_param'                  => true,
    ])
    ->setFinder($finder)
;
