<?php

declare(strict_types=1);

return (new PhpCsFixer\Config())
  ->setRiskyAllowed(true)
  ->setLineEnding("\n")
  ->setUsingCache(false)
  ->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    '@PHP80Migration' => true,
    'not_operator_with_successor_space' => false,
    'concat_space' => ['spacing' => 'one'],
    'declare_strict_types' => true,
    'void_return' => true,
    'native_function_invocation' => ['include' => ['@all']],
    'phpdoc_to_comment' => false,
    'single_line_throw' => false,
    'echo_tag_syntax' => ['format' => 'short'],
    'simplified_if_return' => true,
    'simplified_null_return' => true,
    'method_argument_space' => true,
    'phpdoc_annotation_without_dot' => false,
    'increment_style' => ['style' => 'post'],
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
  ]);
