import Icons from './icons.js';

const { __ } = wp.i18n;

const { ButtonGroup, Dashicon, Tooltip, Button } = wp.components;

const { Component, Fragment } = wp.element;
class RadioIconComponent extends Component {
	constructor() {
		super( ...arguments );
		this.updateValues = this.updateValues.bind( this );
		this.state = {
			value: this.props.value ? this.props.value : '',
			defaultValue: this.props.default ? this.props.default : '',
		};
	}
	render() {
		//console.log( this.state.value );
		const controlLabel = (
			<Fragment>
				<Button
					className="reset kadence-reset"
					disabled={ ( this.state.value === this.state.defaultValue ) }
					onClick={ () => {
						let value = this.state.defaultValue;
						this.setState( { value: this.state.defaultValue } );
						this.updateValues( value );
					} }
				>
					<Dashicon icon='image-rotate' />
				</Button>
				{ this.props.label &&
					this.props.label
				}
			</Fragment>
		);
		return (
			<div className={ `kadence-control-field kadence-radio-icon-control${ this.props.class ? ' ' + this.props.class : '' }` }>
				<Fragment>
					<div className="kadence-responsive-control-bar">
						<span className="customize-control-title">{ controlLabel }</span>
					</div>
					<ButtonGroup className="kadence-radio-container-control">
						{ this.props.options && (
							Object.keys( this.props.options ).map( ( item ) => {
								return (
									<Fragment>
										{ this.props.options[ item ].tooltip && (
											<Tooltip text={ this.props.options[ item ].tooltip }>
												<Button
													isTertiary
													className={ ( item === this.state.value ?
															'active-radio ' :
															'' ) + 'kt-radio-' + item + ( this.props.options[ item ].icon && this.props.options[ item ].name ? ' btn-flex-col' : '' ) }
													onClick={ () => {
														let value = this.state.value;
														value = item;
														this.setState( { value: item });
														this.updateValues( value );
													} }
												>
													{ this.props.options[ item ].icon && (
														<span className="kadence-radio-icon">
															{ Icons[ this.props.options[ item ].icon ] }
														</span>
													) }
													{ this.props.options[ item ].name && (
															this.props.options[ item ].name
													) }
												</Button>
											</Tooltip>
										) }
										{ ! this.props.options[ item ].tooltip && (
											<Button
													isTertiary
													className={ ( item === this.state.value ?
															'active-radio ' :
															'' ) + 'kt-radio-' + item + ( this.props.options[ item ].icon && this.props.options[ item ].name ? ' btn-flex-col' : '' ) }
															onClick={ () => {
																let value = this.state.value;
																value = item;
																this.setState( { value: item });
																this.updateValues( value );
															} }
											>
												{ this.props.options[ item ].icon && (
														<span className="kadence-radio-icon">
															{ Icons[ this.props.options[ item ].icon ] }
														</span>
													) }
													{ this.props.options[ item ].name && (
															this.props.options[ item ].name
													) }
											</Button>
										) }
									</Fragment>
								);
							} )
						) }
					</ButtonGroup>
				</Fragment>
			</div>
		);
	}
	updateValues( value ) {
		this.props.onChange( value );
	}
}
export default RadioIconComponent;
