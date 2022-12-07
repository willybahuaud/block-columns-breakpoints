const { createHigherOrderComponent } = wp.compose;
const { Fragment, useState } = wp.element;
const { BlockControls, InspectorControls } = wp.blockEditor;
const { __ } = wp.i18n;
import { __experimentalUnitControl as UnitControl } from '@wordpress/components';

const {
    ToolbarGroup,
    ToolbarButton,
	PanelBody
} = wp.components;

const enableOnBLocks = [
    'core/columns'
];

/**
 * Permet d'ajouter un nouvel attribut
 * 
 * @param {*} settings 
 * @param {*} name 
 * @returns {*} attributes
 */
const setToolbarButtonAttribute = ( settings, name ) => {
    // Do nothing if it's another block than our defined ones.
    if ( ! enableOnBLocks.includes( name ) ) {
        return settings;
    }

    return Object.assign( {}, settings, {
        attributes: Object.assign( {}, settings.attributes, {
            breakUnder: { type: 'string' }
        } ),
    } );
};
wp.hooks.addFilter(
    'blocks.registerBlockType',
    'wab/set-columns-breakpoints',
    setToolbarButtonAttribute
);


/**
 * Ajout du control
 */
const withBreakUnder = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {

    	if ( ! enableOnBLocks.includes( props.name ) ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const { attributes, setAttributes } = props;
        const { breakUnder } = attributes;

		const units = [
			{ value: 'px', label: 'px', default: 0 },
			{ value: 'vw', label: 'vw', default: 0 },
			{ value: 'vh', label: 'vh', default: 0 },
			{ value: 'em', label: 'em', default: 0 },
			{ value: 'rem', label: 'rem', default: 0 },
		];

        return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'Controle avancé du responsive' ) }
					>
						<UnitControl 
						label={__('Empiler à partir de :')}
						onChange={ (v) => setAttributes({breakUnder:v}) } 
						value={ breakUnder }
						units={units}
                        help={__('Dimension sous laquelle les blocs passent les uns sous les autres')}
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
    };
}, 'withBreakUnder' );
wp.hooks.addFilter(
    'editor.BlockEdit',
    'wab/with-columns-breakpoints',
    withBreakUnder
);
