/**
 * Advanced Color Control.
 *
 */

/**
 * Import Icons
 */
import cIcons from '../color-control/kadence-color-icons';
import KadenceColorPicker from '../color-control/kadence-color-picker';
import hexToRGBA from '../color-control/hex-to-rgba';
//import background from './background-media';
import get from 'lodash/get';
import map from 'lodash/map';

/**
 * Internal block libraries
 */
const { __, sprintf } = wp.i18n;
const {
	Component,
	Fragment,
} = wp.element;
const {
	Button,
	Popover,
	ButtonGroup,
	ColorIndicator,
	Tooltip,
	FocalPointPicker,
	Dashicon,
	TabPanel,
} = wp.components;
const {
	withSelect,
} = wp.data;
const {
	MediaUpload,
	MediaUploadCheck,
} = wp.blockEditor;
/**
 * Build the Measure controls
 * @returns {object} Measure settings.
 */
class BackgroundControl extends Component {
	constructor() {
		super( ...arguments );
		this.onChangeState = this.onChangeState.bind( this );
		this.onChangeComplete = this.onChangeComplete.bind( this );
		this.onImageRemove = this.onImageRemove.bind( this );
		this.updateValues = this.updateValues.bind( this );
		this.onImageSelect = this.onImageSelect.bind( this );
		this.state = {
			alpha: false === this.props.alpha ? false : true,
			isVisible: false,
			colors: [],
			classSat: 'first',
			currentColor: '',
			currentImage: {},
			currentValue: this.props.value ? this.props.value : {},
			inherit: false,
			modalCanClose: true,
			currentOpacity: this.props.opacityValue !== undefined ? this.props.opacityValue : 1,
			isPalette: ( ( this.props.value && this.props.value.color !== undefined && this.props.value.color && this.props.value.color.startsWith( 'palette' ) ) ? true : false ),
		};
	}
	onImageRemove() {
		let image = this.state.currentValue;
		if ( image.url ) {
			image.url = '';
		}
		if ( image.id ) {
			image.id = '';
		}
		this.updateValues( image );
	}
	onImageSelect( media ) {
		let image = this.state.currentValue;
		image.id = media.id;
		image.url = media.url;
		this.updateValues( image );
	}
	updateValues( value ) {
		this.setState( { currentValue: value } );
		this.props.onChange( value );
	}
	render() {
		const toggleVisible = () => {
			this.props.onOpen();
			this.setState( { isVisible: true } );
		};
		const toggleClose = () => {
			if ( this.state.modalCanClose ) {
				if ( this.state.isVisible === true ) {
					this.setState( { isVisible: false } );
					this.props.onClose();
				}
			}
		};
		const convertOpacity = ( value ) => {
			let val = 1;
			if ( value ) {
				val = value / 100;
			}
			return val;
		};
		const defaultParams = {
			repeat: {
				'no-repeat': {
					name: __( 'No Repeat', 'kadence-pro' ),
				},
				'repeat': {
					name: __( 'Repeat', 'kadence-pro' ),
				},
				'repeat-x': {
					name: __( 'Repeat-X', 'kadence-pro' ),
				},
				'repeat-y': {
					name: __( 'Repeat-y', 'kadence-pro' ),
				},
			},
			size: {
				auto: {
					name: __( 'Auto', 'kadence-pro' ),
				},
				cover: {
					name: __( 'Cover', 'kadence-pro' ),
				},
				contain: {
					name: __( 'Contain', 'kadence-pro' ),
				},
			},
			position: {
				'left top': {
					name: __( 'Left Top', 'kadence-pro' ),
				},
				'center top': {
					name: __( 'Center Top', 'kadence-pro' ),
				},
				'right top': {
					name: __( 'Right Top', 'kadence-pro' ),
				},
				'left center': {
					name: __( 'Left Center', 'kadence-pro' ),
				},
				'center center': {
					name: __( 'Center Center', 'kadence-pro' ),
				},
				'right center': {
					name: __( 'Right Center', 'kadence-pro' ),
				},
				'left bottom': {
					name: __( 'Left Bottom', 'kadence-pro' ),
				},
				'center bottom': {
					name: __( 'Center Bottom', 'kadence-pro' ),
				},
				'right bottom': {
					name: __( 'Right Bottom', 'kadence-pro' ),
				},
			},
		};
		const currentVal = ( this.state.currentValue ? this.state.currentValue : this.props.value );
		const colorVal = ( currentVal.color ? currentVal.color : '' );
		let currentColorString = ( this.state.isPalette && this.props.colors && this.props.colors[ parseInt( colorVal.slice( -1 ), 10 ) - 1 ] ? this.props.colors[ parseInt( colorVal.slice( -1 ), 10 ) - 1 ].color : colorVal );
		if ( '' === currentColorString ) {
			currentColorString = this.props.colorDefault;
		}
		const getRadioClassName = ( item, control ) => {
			let itemClass;
			if ( undefined === currentVal ) {
				itemClass = item;
			}else if ( undefined === currentVal[ control ] ) {
				itemClass = item;
			} else if ( item === currentVal[ control ] ) {
				itemClass = 'active-radio ' + item;
			} else {
				itemClass = item;
			}
			return itemClass;
		}
		return (
			<div className="kt-color-popover-container new-kadence-advanced-colors kadence-control-field">
				<div className="kt-advanced-color-settings-container">
					<Button
					className="reset kadence-reset"
						disabled={ ( currentVal === {} ) }
						onClick={ () => {
							this.setState( { currentValue: {} } );
							this.updateValues( {} );
						} }
					>
						<Dashicon icon='image-rotate' />
					</Button>
					{ this.props.label && (
						<h3 className="kt-beside-color-label">{ this.props.label }</h3>
					) }
					{ this.props.colorValue && this.props.colorValue !== this.props.colorDefault && (
						<Button
							className="components-color-palette__clear"
							type="button"
							onClick={ () => {
								this.setState( { currentColor: this.props.colorDefault, isPalette: ( this.props.colorDefault && this.props.colorDefault.startsWith( 'palette' ) ? true : false ) } );
								this.props.onColorChange( this.props.colorDefault ? this.props.colorDefault : undefined );
								if ( this.props.onColorClassChange ) {
									this.props.onColorClassChange( '' );
								}
							} }
							isSmall
						>
							<Dashicon icon="redo" />
						</Button>
					) }
					<div className="kt-beside-color-click">
						{ this.state.isVisible && (
							<Popover position="top left" className="kt-popover-color new-kadence-advanced-colors-pop" onClose={ toggleClose }>
								<TabPanel className="kadence-popover-tabs kadence-background-tabs"
									activeClass="active-tab"
									tabs={ [
										{
											name: 'color',
											title: __( 'Color', 'kadence-pro' ),
											className: 'kadence-color-background',
										},
										{
											name: 'image',
											title: __( 'Image', 'kadence-pro' ),
											className: 'kadence-image-background',
										},
									] }
								>
									{
										( tab ) => {
											let tabout;
											if ( tab.name ) {
												if ( 'image' === tab.name ) {
													tabout = (
														<Fragment>
															<div class="attachment-media-view">
																<Button 
																	className="button button-add-media"
																	onClick={ ()=> {
																		this.setState( { modalCanClose: false } );
																		// Create the media frame.
																		const frame = wp.media({
																			multiple: false,
																			library: {type: 'image'}
																		});
																		var _self = this;
																		// When an image is selected, run a callback.
																		frame.on( 'select', function() {

																			// Grab the selected attachment.
																			var attachment = frame.state().get('selection').first();
																			frame.close();
																			let image = _self.state.currentValue;
																			image.id = attachment.attributes.id;
																			image.url = attachment.attributes.url;
																			_self.updateValues( image );
																			_self.setState( { modalCanClose: true } );
																		});

																		// Finally, open the modal.
																		frame.open();
																	} }
																>
																	{ __( 'Select Image', 'kadence-pro' ) }
																</Button>
																{ undefined !== currentVal && currentVal && currentVal.url && (
																	<Fragment>
																		<div class="actions">
																			<Button className="button is-destructive remove-button" onClick={ () => this.onImageRemove() } >{ __( 'Remove Image', 'kadence-pro' ) }</Button>
																		</div>
																		{ undefined !== currentVal && currentVal && currentVal.url && (
																			<img className="kadence-background-image-preview" src={ currentVal.url } />
																		) }
																	</Fragment>
																) }
															</div>
															<span className="customize-control-title">{ __( 'Background Position', 'kadence-pro' ) }</span>
															<ButtonGroup className="kadence-radio-container-control kadence-radio-position">
																{ Object.keys( defaultParams.position ).map( ( item ) => {
																	return (
																		<Button
																			isTertiary
																			className={ getRadioClassName( item, 'position' ) }
																			onClick={ () => {
																				let value = this.state.currentValue;
																				value.position = item;
																				this.updateValues( value );
																			} }
																		>
																			{ defaultParams.position[ item ].name && (
																					defaultParams.position[ item ].name
																			) }
																		</Button>
																	);
																} ) }
															</ButtonGroup>
															<span className="customize-control-title">{ __( 'Background Repeat', 'kadence-pro' ) }</span>
															<ButtonGroup className="kadence-radio-container-control">
																{ Object.keys( defaultParams.repeat ).map( ( item ) => {
																	return (
																		<Button
																			isTertiary
																			className={ getRadioClassName( item, 'repeat' ) }
																			onClick={ () => {
																				let value = this.state.currentValue;
																				value.repeat = item;
																				this.updateValues( value );
																			} }
																		>
																			{ defaultParams.repeat[ item ].name && (
																					defaultParams.repeat[ item ].name
																			) }
																		</Button>
																	);
																} ) }
															</ButtonGroup>
															<span className="customize-control-title">{ __( 'Background Size', 'kadence-pro' ) }</span>
															<ButtonGroup className="kadence-radio-container-control">
																{ Object.keys( defaultParams.size ).map( ( item ) => {
																	return (
																		<Button
																			isTertiary
																			className={ getRadioClassName( item, 'size' ) }
																			onClick={ () => {
																				let value = this.state.currentValue;
																				value.size = item;
																				this.updateValues( value );
																			} }
																		>
																			{ defaultParams.size[ item ].name && (
																					defaultParams.size[ item ].name
																			) }
																		</Button>
																	);
																} ) }
															</ButtonGroup>
														</Fragment>
													);
												} else {
													tabout = (
														<Fragment>
															{ this.state.classSat === 'first' && ! this.props.disableCustomColors && (
																<KadenceColorPicker
																	color={ currentColorString }
																	onChange={ ( color ) => this.onChangeState( color, '' ) }
																	onChangeComplete={ ( color ) => {
																		this.onChangeComplete( color, '' );
																	} }
																/>
															) }
															{ this.state.classSat !== 'first' && ! this.props.disableCustomColors && (
																<KadenceColorPicker
																	color={ currentColorString }
																	onChange={ ( color ) => this.onChangeState( color, '' ) }
																	onChangeComplete={ ( color ) => {
																		this.onChangeComplete( color, '' );
																	} }
																/>
															) }
															{ this.props.colors && (
																<div className="components-color-palette">
																	{ map( this.props.colors, ( { color, slug, name } ) => {
																		const style = { color };
																		const palette = slug.replace( 'theme-', '' );
																		const isActive = ( ( palette === this.props.colorValue ) || ( ! slug.startsWith( 'theme-palette' ) && this.props.colorValue === color ) );
																		return (
																			<div key={ color } className="components-color-palette__item-wrapper">
																				<Button
																					type="button"
																					className={ `components-color-palette__item ${ ( isActive ? 'is-active' : '' ) }` }
																					style={ style }
																					onClick={ () => {
																						if ( slug.startsWith( 'theme-palette' ) ) {
																							this.onChangeComplete( color, palette );
																						} else {
																							this.onChangeComplete( color, false );
																						}
																						if ( this.props.onColorClassChange ) {
																							this.props.onColorClassChange( slug );
																						}
																						// if ( 'first' === this.state.classSat ) {
																						// 	this.setState( { classSat: 'second' } );
																						// } else {
																						// 	this.setState( { classSat: 'first' } );
																						// }
																					} }
																					aria-label={ name ?
																						// translators: %s: The name of the color e.g: "vivid red".
																						sprintf( __( 'Color: %s' ), name ) :
																						// translators: %s: color hex code e.g: "#f00".
																						sprintf( __( 'Color code: %s' ), color ) }
																					aria-pressed={ isActive }
																				/>
																				{ palette === this.props.colorValue && <Dashicon icon="admin-site" /> }
																				{ ! slug.startsWith( 'theme-palette' ) && this.props.colorValue === color && <Dashicon icon="saved" /> }
																			</div>
																		);
																	} ) }
																</div>
															) }
														</Fragment>
													);
												}
											}
											return <div>{ tabout }</div>;
										}
									}
								</TabPanel>
							</Popover>
						) }
						{ this.state.isVisible && (
							<Button className={ `kt-color-icon-indicate kt-background-preview ${ ( this.state.alpha ? 'kt-has-alpha' : 'kt-no-alpha' ) }` } onClick={ toggleClose }>
								<ColorIndicator className="kt-advanced-color-indicate" colorValue={ currentColorString } />
								{ '' === currentColorString && this.state.inherit && (
									<span className="color-indicator-icon">{ cIcons.inherit }</span>
								) }
								{ ( this.props.value && this.props.value.color !== undefined && this.props.value.color && this.props.value.color.startsWith( 'palette' ) ) && (
									<span className="color-indicator-icon">{ <Dashicon icon="admin-site" /> }</span>
								) }
								{ undefined !== currentVal && currentVal && currentVal.url && (
									<img className="kadence-background-image-preview" src={ currentVal.url } />
								) }
							</Button>
						) }
						{ ! this.state.isVisible && (
							<Button className={ `kt-color-icon-indicate kt-background-preview ${ ( this.state.alpha ? 'kt-has-alpha' : 'kt-no-alpha' ) }` } onClick={ toggleVisible }>
								<ColorIndicator className="kt-advanced-color-indicate" colorValue={ currentColorString } />
								{ '' === currentColorString && this.state.inherit && (
									<span className="color-indicator-icon">{ cIcons.inherit }</span>
								) }
								{ ( this.props.value && this.props.value.color !== undefined && this.props.value.color && this.props.value.color.startsWith( 'palette' ) ) && (
									<span className="color-indicator-icon">{ <Dashicon icon="admin-site" /> }</span>
								) }
								{ undefined !== currentVal && currentVal && currentVal.url && (
									<img className="kadence-background-image-preview" src={ currentVal.url } />
								) }
							</Button>
						) }
					</div>
				</div>
			</div>
		);
	}
	onChangeState( color, palette ) {
		let opacity = 1;
		let newColor;
		if ( palette ) {
			newColor = palette;
		} else if ( undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a ) {
			newColor = 'rgba(' + color.rgb.r + ',' + color.rgb.g + ',' + color.rgb.b + ',' + color.rgb.a + ')';
		} else if ( undefined !== color.hex ) {
			newColor = color.hex;
		} else {
			newColor = color;
		}
		this.setState( { currentColor: newColor, isPalette: ( palette ? true : false ) } );
	}
	onChangeComplete( color, palette ) {
		let value = this.state.currentValue;
		let opacity = ( 100 === this.props.opacityUnit ? 100 : 1 );
		let newColor;
		if ( palette ) {
			newColor = palette;
		} else if ( undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a ) {
			newColor = 'rgba(' + color.rgb.r + ',' + color.rgb.g + ',' + color.rgb.b + ',' + color.rgb.a + ')';
		} else if ( undefined !== color.hex ) {
			newColor = color.hex;
		} else {
			newColor = color;
		}
		value.color = newColor;
		this.setState( { currentColor: newColor, isPalette: ( palette ? true : false ) } );
		this.updateValues( value );
	}
}
export default withSelect( ( select, ownProps ) => {
	const settings = JSON.parse( kadenceProMegaParams.settings );
	const colors = settings.colors;
	const disableCustomColors = ownProps.disableCustomColors === undefined ? settings.disableCustomColors : ownProps.disableCustomColors;
	return {
		colors,
		disableCustomColors,
	};
} )( BackgroundControl );
