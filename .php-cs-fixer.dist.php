<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/database')
    ->name('*.php')
    ->notName('*.blade.php')
    ->exclude('storage')
    ->exclude('vendor')
    ->append([__FILE__]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12'                      => true,
        'elseif'                      => true,
        'array_syntax'                => ['syntax' => 'short'],
        'binary_operator_spaces'      => ['default' => 'align_single_space_minimal'],
        'no_unused_imports'           => true,
        'no_trailing_whitespace'      => true,
        'linebreak_after_opening_tag' => true,
        'no_extra_blank_lines'        => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'single_quote'                => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments']],
        'ordered_imports'             => [
            'sort_algorithm' => 'alpha',
            'imports_order'  => ['class', 'function', 'const'],
        ],
        'blank_line_between_import_groups'    => true,
        'not_operator_with_space'             => false,
        'not_operator_with_successor_space'   => false,
        'method_argument_space'               => ['on_multiline' => 'ensure_fully_multiline'],
        'array_indentation'                   => true,
        'no_whitespace_before_comma_in_array' => true,
        'trim_array_spaces'                   => true,
        'indentation_type'                    => true,
        'blank_line_after_namespace'          => true,
        'visibility_required'                 => [
            'elements' => [
                'method',
                'property',
                'const',
            ],
        ],
        'switch_case_space'                       => true,
        'no_blank_lines_after_class_opening'      => true,
        'line_ending'                             => true,
        'control_structure_braces'                => true,
        'control_structure_continuation_position' => [
            'position' => 'same_line',
        ],
        'constant_case' => [
            'case' => 'lower',
        ],
        'declare_equal_normalize' => [
            'space' => 'single',
        ],
        'ternary_operator_spaces'           => true,
        'ternary_to_null_coalescing'        => true,
        'compact_nullable_type_declaration' => true,
        'full_opening_tag'                  => true,
        'lowercase_cast'                    => true,
        'lowercase_keywords'                => true,
        'lowercase_static_reference'        => true,
        'no_closing_tag'                    => true,
        'return_type_declaration'           => [
            'space_before' => 'none',
        ],
    ])
    ->setFinder($finder);
