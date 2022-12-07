<?php

/**
 * Hook on render block to filter markup and add breakpoints query
 * @param string $block_content
 * @param array $block
 * @return string html
 */
function render_block_style( $block_content, $block ) {

	if ( ! w_has_column_breakpoints( $block ) ) {
		return $block_content;
	}

	$selector = w_column_generate_selector();

	$block_style = w_column_generate_breakpoint_query( $block, '.' . $selector );
	if ( empty( $block_style ) ) {
		return $block_content;
	}

	$content = w_add_class_to_block( $block_content, $selector );

	return $content . '<style>' . $block_style . '</style>';
}
add_filter( 'render_block', 'render_block_style', 10, 2 );

/**
 * Check if current block need a rewrite
 * @param array $block
 * @return bool
 */
function w_has_column_breakpoints( $block ) {
	if ( isset( $block['attrs']['breakUnder'] ) ) {
		return true;
	}
	return false;
}

/**
 * Invent a css className
 * @return string
 */
function w_column_generate_selector() {
	return 'column-breakpoint-' . uniqid();
}

/**
 * Return a CSS breakpoint query
 * @param array $block
 * @param string $selector
 * @return string css query
 */
function w_column_generate_breakpoint_query( $block, $selector ) {
	return vsprintf('@media (max-width:%2$s){.wp-block-columns%1$s{flex-wrap:wrap!important}.wp-block-columns%1$s>.wp-block-column{flex-basis:100%%!important}}', array(
		/* 1 */ 'selector'   => $selector,
		/* 2 */ 'breakpoint' => $block['attrs']['breakUnder'],
	));
}

/**
 * Raplace columns class by ours
 * @param string $block_content
 * @param string $classes
 * @return string
 */
function w_add_class_to_block( $block_content, $classes ) {
	if ( ! w_block_has_attribute( 'class', $block_content ) ) {
		$content = preg_replace(
			'/' . preg_quote( '>', '/' ) . '/',
			'class="' . $classes . '">',
			$block_content,
			1
		);
	} else {
		$content = preg_replace(
			'/' . preg_quote( 'class="', '/' ) . '/',
			'class = "' . $classes . ' ',
			$block_content,
			1
		);
	}

	$content = str_replace('is-not-stacked-on-mobile', '', $content);

	return $content;
}

/**
 * Utils to fin block properties
 * @param mixed $attribute
 * @param mixed $block_content
 * @return bool|int
 */
function w_block_has_attribute( $attribute, $block_content ) {
	$greater_position = strpos( $block_content, '>' );
	if ( $greater_position !== false ) {
		$block_tag = substr( $block_content, 0, $greater_position + 1 );
		return strpos( $block_tag, $attribute . '=' );
	}

	return false;
}