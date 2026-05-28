<?php
/**
 * Register self-contained blocks from /blocks/.
 *
 * Each block directory contains a block.json with "editorScript": "file:./index.js".
 * WordPress reads that file and enqueues the compiled script automatically.
 *
 * @package Globeiron
 */

declare(strict_types=1);

add_action('init', function (): void {
    $blocks_dir = GLOBEIRON_DIR . '/blocks';

    $blocks = [
        'hero-home',
        'hero-interior',
        'blueprint-columns',
    ];

    foreach ($blocks as $block) {
        register_block_type( $blocks_dir . '/' . $block );
    }
});
