/**
 * Advanced Color Control.
 *
 */

/**
 * Import Icons
 */
import PopColorControl from './pop-color-control';
import get from 'lodash/get';
import map from 'lodash/map';

/**
 * Internal block libraries
 */
const { __, sprintf } = wp.i18n;
const {
	Component,
} = wp.element;
const {
	Button,
	Popover,
	ColorIndicator,
	Tooltip,
	Dashicon,
} = wp.components;
const {
	withSelect,
} = wp.data;
/**
 * Build the Measure controls
 * @returns {object} Measure settings.
 */
class PopColorsControl extends Component {
	constructor() {
		super( ...arguments );
		this.saveSub = this.saveSub.bind( this );
		this.state = {
			value: this.props.value ? this.props.value : {},
			first: true,
		};
	}
	saveSub( color, item ) {
		let newValue = this.state.value;
		newValue[ item ] = color;
		this.setState( { value: newValue } );
		this.props.onChange( newValue );
	}
	render() {
		return (
			<div className="kt-color-popover-container new-kadence-advanced-colors kadence-control-field">
				<div className="kt-advanced-color-settings-container">
					<Button
						className="reset kadence-reset"
						disabled={ ( this.state.value === {} ) }
						onClick={ () => {
							this.setState( { value: {} } );
							if ( this.state.first ) {
								this.setState( { first: false } );
							} else {
								this.setState( { first: true } );
							}
							this.props.onChange( {} );
						} }
					>
						<Dashicon icon='image-rotate' />
					</Button>
					{ this.props.label && (
						<h3 className="kt-beside-color-label">{ this.props.label }</h3>
					) }
					<div className="kt-beside-color-click kt-colors-select-control">
						{ this.props.options && this.state.first && (
							Object.keys( this.props.options ).map( ( item ) => {
								return (
									<PopColorControl
										label={ this.props.options[ item ].name }
										colorValue={ ( this.state.value && undefined !== this.state.value[ item ] ? this.state.value[ item ] : '' ) }
										colorDefault={ '' }
										onColorChange={ value => {
											this.saveSub( value, item );
										} }
									/>
								);
							} )
						) }
						{ this.props.options && ! this.state.first && (
							Object.keys( this.props.options ).map( ( item ) => {
								return (
									<PopColorControl
										label={ this.props.options[ item ].name }
										colorValue={ ( this.state.value && undefined !== this.state.value[ item ] ? this.state.value[ item ] : '' ) }
										colorDefault={ '' }
										onColorChange={ value => {
											this.saveSub( item, value );
										} }
									/>
								);
							} )
						) }
					</div>
				</div>
			</div>
		);
	}
}
export default PopColorsControl;
