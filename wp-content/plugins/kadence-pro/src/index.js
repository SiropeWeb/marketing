/**
 * Pro Modules
 */

const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { __ } = wp.i18n;
import ProModules from './pro';
const {
	Fragment,
} = wp.element;
const {
	InspectorControls,
} = wp.blockEditor;
const {
	PanelBody,
	ToggleControl,
	TextareaControl,
	TextControl,
	Panel,
} = wp.components;
const proControl = ( FilteredComponent ) => {
	return ( props ) => (
        <Fragment>
			<ProModules />
        </Fragment>
	);
};
addFilter( 'kadence_theme_pro_modules', 'kadence_pro/license', proControl );
