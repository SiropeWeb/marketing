/**
 * Advanced Color Control.
 *
 */

/**
 * Import Icons
 */
import PopColorControl from '../color-control/pop-color-control';
import Icons from '../radio-control/icons.js';
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
	Toolbar,
	Dashicon,
} = wp.components;
const {
	withSelect,
} = wp.data;
/**
 * Build the Measure controls
 * @returns {object} Measure settings.
 */
class BorderControl extends Component {
	constructor() {
		super( ...arguments );
		this.saveSub = this.saveSub.bind( this );
		this.getStyleButtons = this.getStyleButtons.bind( this );
		this.createStyleControlToolbar = this.createStyleControlToolbar.bind( this );
		this.getUnitButtons = this.getUnitButtons.bind( this );
		this.createLevelControlToolbar = this.createLevelControlToolbar.bind( this );
		const defaultParams = {
			min: {
				px: '0',
				em: '0',
				rem: '0',
			},
			max: {
				px: '300',
				em: '12',
				rem: '12',
			},
			step: {
				px: '1',
				em: '0.01',
				rem: '0.01',
			},
			units: ['px', 'em', 'rem'],
			styles: ['none', 'solid', 'dashed', 'dotted', 'double'],
			color:true,
		};
		this.state = {
			value: this.props.value ? this.props.value : {},
			controlParams: defaultParams,
		};
	}
	saveSub( color, item ) {
		let newValue = this.state.value;
		newValue[ item ] = color;
		this.setState( { value: newValue } );
		this.props.onChange( newValue );
	}
	render() {
		const currentUnit = ( this.state.value && undefined !== this.state.value.unit ? this.state.value.unit : 'px' );
		const currentStyle = ( this.state.value && undefined !== this.state.value.style ? this.state.value.style : 'solid' );
		return (
			<div className="kt-color-popover-container new-kadence-advanced-colors kadence-control-field kadence-border-control">
				<div className="kt-advanced-color-settings-container">
					<Button
						className="reset kadence-reset"
						disabled={ ( this.state.value === {} ) }
						onClick={ () => {
							this.setState( { value: {} } );
							this.props.onChange( {} );
						} }
					>
						<Dashicon icon='image-rotate' />
					</Button>
					{ this.props.label && (
						<h3 className="kt-beside-color-label">{ this.props.label }</h3>
					) }
					<div className="kt-beside-color-click kt-border-control">
						<PopColorControl
							colorValue={ ( this.state.value && undefined !== this.state.value.color ? this.state.value.color : '' ) }
							colorDefault={ '' }
							onColorChange={ value => {
								this.saveSub( value, 'color' );
							} }
						/>
						<input
							value={ ( this.state.value && undefined !== this.state.value.width ? this.state.value.width : '' ) }
							onChange={ event => {
								const newValue = ( '' !== event.target.value ? Number( event.target.value ) : '' );
								this.saveSub( newValue, 'width' );
							} }
							min={this.state.controlParams.min[currentUnit]}
							max={this.state.controlParams.max[currentUnit]}
							step={this.state.controlParams.step[currentUnit]}
							type="number"
							className="components-text-control__input"
						/>
						{ this.state.controlParams.units && (
							<div className="kadence-units">
								{ this.getUnitButtons() }
							</div>
						) }
						{ this.state.controlParams.styles && (
							<div className="kadence-units kadence-style-options">
								{ this.getStyleButtons() }
							</div>
						) }
					</div>
				</div>
			</div>
		);
	}
	getStyleButtons() {
		const { styles } = this.state.controlParams;
		if ( styles.length === 1 ) {
			return ( <Button
					className="is-active is-single"
					isSmall
					disabled
			>{ Icons[ ( undefined !== this.state.value.style ? this.state.value.style : 'solid' ) ] }</Button> );
		}
		return <Toolbar
			isCollapsed={ true }
			icon={ Icons[ ( undefined !== this.state.value.style ? this.state.value.style : 'solid' ) ] }
			label={ __( 'Style', 'kadence-pro' ) }
			controls={ styles.map( ( style ) => this.createStyleControlToolbar( style ) ) }
		/> 
	}
	createStyleControlToolbar( style ) {
		return [ {
			icon: Icons[ style ],
			isActive: ( undefined !== this.state.value && undefined !== this.state.value.style && this.state.value.style === style ),
			onClick: () => {
				this.saveSub( style, 'style' );
			},
		} ];
	};
	getUnitButtons() {
		const { units } = this.state.controlParams;
		let currentUnit = ( undefined !== this.state.value.unit ? this.state.value.unit : 'px' );
		if ( units.length === 1 ) {
			return ( <Button
					className="is-active is-single"
					isSmall
					disabled
			>{ ( '%' === currentUnit ? Icons.percent : Icons[ currentUnit ] ) }</Button> );
		}
		return <Toolbar
			isCollapsed={ true }
			icon={ ( '%' === currentUnit ? Icons.percent : Icons[ currentUnit ] ) }
			label={ __( 'Unit', 'kadence-pro' ) }
			controls={ units.map( ( unit ) => this.createLevelControlToolbar( unit ) ) }
		/> 
	}
	createLevelControlToolbar( unit ) {
		return [ {
			icon: ( unit === '%' ? Icons.percent : Icons[ unit ] ),
			isActive: ( undefined !== this.state.value && undefined !== this.state.value.unit && this.state.value.unit === unit ),
			onClick: () => {
				this.saveSub( unit, 'unit' );
			},
		} ];
	};
}
export default BorderControl;
