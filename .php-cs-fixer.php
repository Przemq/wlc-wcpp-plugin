<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__)
  ->name('*.php')
  ->exclude(['vendor', 'cache']);

$config = new PhpCsFixer\Config();
return $config
  ->setRiskyAllowed(true)
  ->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'align_multiline_comment' => true,
    'binary_operator_spaces' => [
      'default' => 'single_space',
      'operators' => ['=>' => 'align_single_space_minimal'],
    ],
    'blank_line_before_statement' => [
      'statements' => ['return'],
    ],
    'braces' => [
      'allow_single_line_closure' => true,
    ],
    'concat_space' => ['spacing' => 'one'],
    'function_typehint_space' => true,
    'single_quote' => true,
    'no_unused_imports' => true,
    'no_whitespace_before_comma_in_array' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'whitespace_after_comma_in_array' => true,
    'phpdoc_align' => ['align' => 'left'],
    'phpdoc_indent' => true,
    'phpdoc_no_empty_return' => true,
    'phpdoc_scalar' => true,
    'phpdoc_separation' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_var_without_name' => true,
    'return_type_declaration' => [
      'space_before' => 'none',
    ],
    'single_blank_line_before_namespace' => true,
    'single_trait_insert_per_statement' => true,
  ])
  ->setFinder($finder);
