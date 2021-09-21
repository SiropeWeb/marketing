import Faico from './icons/faicons';
import IconControl from './icons/icon-control';
import IconRender from './icons/icon-render';
import PopColorControl from './color-control/pop-color-control';
import RadioIconControl from './radio-control/radio-icon-control';
import BackgroundControl from './background-control/background-control';
import PopColorsControl from './color-control/pop-colors-control';
import BorderControl from './border-control/border-control';
import Select from 'react-select';
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { __ } = wp.i18n;
const { parse, createBlock, serialize } = wp.blocks;
const {
	Component,
	Fragment,
	renderToString,
} = wp.element;
const {
	ToggleControl,
	SelectControl,
	PanelBody,
	Panel,
	FormToggle,
	TextControl,
	Modal,
	TabPanel,
	Button,
	Spinner,
	DateTimePicker,
} = wp.components;
const { __experimentalGetSettings } = wp.date;
const { withSelect, withDispatch, select, dispatch } = wp.data;
const { compose } = wp.compose;

class KadenceMegaMenu extends Component {
	constructor() {
		super( ...arguments );
		this.saveMeta = this.saveMeta.bind( this );
		this.openModal = this.openModal.bind( this );
		this.onClose = this.onClose.bind( this );
		this.runAjaxFetch = this.runAjaxFetch.bind( this );
		this.saveDataState = this.saveDataState.bind( this );
		this.state = {
			modalOpen: false,
			isSaving: false,
			isSubOpen: false,
			isFetching: false,
			itemData: '',
			menuTitle: '',
			parentID: null,
			megaSetting: null,
			error: '',
		};
	}
	componentDidMount() {
	}
	runAjax( data ) {
		var control = this;
		jQuery.ajax({
			method:      'POST',
			url:         kadenceProMegaParams.ajax_url,
			data:        data,
			contentType: false,
			processData: false,
		})
		.done( function( response ) {
			if ( response.success ) {
				control.setState( { isSaving: false, error: '' } );
			} else {
				control.setState( { isSaving: false, itemData: '', error: response.data } );
			}
		})
		.fail( function( error ) {
			control.setState( { isSaving: false, itemData: '', error: 'Error: ' + error.statusText + ' (' + error.status + ')' } );
		});
	}
	runAjaxFetch( data ) {
		var control = this;
		jQuery.ajax({
			method:      'POST',
			url:         kadenceProMegaParams.ajax_url,
			data:        data,
			contentType: false,
			processData: false,
		})
		.done( function( response ) {
			if ( response.success ) {
				control.setState( { isFetching: false, error: '', itemData: JSON.parse( response.data ) } );
			} else {
				control.setState( { isFetching: false, itemData: '', error: response.data } );
			}
		})
		.fail( function( error ) {
			control.setState( { isFetching: false, itemData: '', error: 'Error: ' + error.statusText + ' (' + error.status + ')' } );
		});
	}
	saveMeta() {
		if ( this.state.error || ! this.state.itemData ) {
			this.setState( { error: __( 'Please reload page, data can not be saved.', 'kadence-pro' ) } );
			return;
		}
		this.setState( { isSaving:true } );
		var newData = new FormData();
		newData.append( 'action', 'kadence_save_menu_item_data' );
		newData.append( 'security', kadenceProMegaParams.ajax_nonce );
		newData.append( 'item_id', this.props.item_id );
		newData.append( 'nav_id', this.props.nav_id );
		newData.append( 'data',  JSON.stringify( this.state.itemData ) );
		this.runAjax( newData );
	}
	saveDataState( key, value ) {
		const data = this.state.itemData;
		if ( ! data ) {
			data = {};
		}
		data[ key ] = value;
		this.setState( { itemData: data } );
	}
	isJson( str ) {
		try {
			str = JSON.parse(str);
		} catch (e) {
			return false;
		}
	
		if ( typeof str === "object" && str !== null) {
			return true;
		}
		return false;
	}
	openModal() {
		var $item = jQuery( '#menu-item-' + this.props.item_id );
		let parent_id    = null;
		let mega_setting = null;
		if ( $item.hasClass( 'menu-item-depth-1' ) ) {
			mega_setting = 'child';
			const closest_parent = $item.prevAll('.menu-item-depth-0');
			const closest_parent_id = closest_parent.attr('id');
			if ( typeof closest_parent_id != 'undefined' ) {
				parent_id = closest_parent_id.replace(/[^0-9\.]/g, '');
			}
		} else if ( $item.hasClass( 'menu-item-depth-0' ) ) {
			mega_setting = 'parent';
		} else {
			mega_setting = null;
		}
		const menu_title = $item.find('.menu-item-title').text();
		var newData = new FormData();
		newData.append( 'action', 'kadence_get_menu_item_data' );
		newData.append( 'security', kadenceProMegaParams.ajax_nonce );
		newData.append( 'item_id', this.props.item_id );
		newData.append( 'nav_id', this.props.nav_id );
		newData.append( 'parent_id', parent_id );
		this.setState( { modalOpen: true, isFetching:true, megaSetting: mega_setting, parentID: parent_id, menuTitle: menu_title } );
		this.runAjaxFetch( newData );
	}
	onClose() {
		this.setState( { modalOpen: false, error:'' } )
	}
	render() {
		const { itemData, megaSetting } = this.state;
		let tabs = [];
		if ( megaSetting === null || ( megaSetting === 'child' && itemData && undefined !== itemData.parent_mega_menu && ! itemData.parent_mega_menu ) ) {
			tabs = [
				{
					name: 'label',
					title: __( 'Label Settings', 'kadence-blocks' ),
					className: 'kadence-pro-menu-label-settings',
				},
			];
		} else {
			tabs = [
				{
					name: 'label',
					title: __( 'Label Settings', 'kadence-blocks' ),
					className: 'kadence-pro-menu-label-settings',
				},
				{
					name: 'mega',
					title: __( 'Mega Settings', 'kadence-blocks' ),
					className: 'kadence-pro-menu-mega-settings',
				},
			];
		}
		const layouts = {
			'6': {
				'equal': {
					icon: 'sixcol',
				},
			},
			'5': {
				'equal': {
					icon: 'fivecol',
				},
			},
			'4': {
				'equal': {
					icon: 'fourcol',
				},
				'left-forty': {
					icon: 'lfourforty',
				},
				'right-forty': {
					icon: 'rfourforty',
				},
			},
			'3': {
				'equal': {
					icon: 'threecol',
				},
				'left-half': {
					icon: 'lefthalf',
				},
				'right-half': {
					icon: 'righthalf',
				},
				'center-half': {
					icon: 'centerhalf',
				},
				'center-wide': {
					icon: 'widecenter',
				},
			},
			'2': {
				'equal': {
					icon: 'twocol',
				},
				'left-golden': {
					icon: 'twoleftgolden',
				},
				'right-golden': {
					icon: 'tworightgolden',
				},
			},
			'1': {
				'equal': {
					icon: 'row',
				},
			}
		}
		return (
			<Fragment>
				<Button
					isSecondary
					className="kadence-mega-options-button-rendered"
					onClick={ () => this.openModal() }>
					{ __( 'Menu Item Settings' ) }
				</Button>
				{ this.state.modalOpen && (
					<Modal
						className="kadence-pro-menu-modal"
						title={ __( 'Menu Item Settings', 'kadence-pro' ) }
						onRequestClose={ () => ( this.state.isSaving || this.state.isSubOpen ) ? false : this.onClose() }>
							{ this.state.isFetching && (
								<Spinner />
							) }
							{ ! this.state.isFetching && (
								<Fragment>
									<div className="kadence-pro-menu-section">
										<div className="kadence-pro-menu-header">
											<h2>
												{ __( 'Menu Item Settings', 'kadence-pro' ) }
												{ this.state.menuTitle && (
													<span className="kadence-menu-item-label">{ __( 'Editing:', 'kadence-pro' ) } { this.state.menuTitle }</span>
												) }
											</h2>
										</div>
										<Button
											className="kadence-pro-menu-close"
											label={ __( 'Close Dialog' ) }
											icon="no-alt"
											disabled={ this.state.isSaving } 
											onClick={ () => this.state.isSaving ? false : this.onClose() }
										/>
										<TabPanel className="kadence-pro-menu-tabs"
											activeClass="active-tab"
											tabs={ tabs }>
											{
												( tab ) => {
													let tabout;
													if ( tab.name ) {
														if ( 'mega' === tab.name ) {
															tabout = (
																<Fragment>
																	<div class="menu-modal-section">
																		<h2>{ __( 'Mega Menu', 'kadence-pro' ) }</h2>
																		{ this.state.megaSetting == 'child' && (
																			<Fragment>
																				<ToggleControl
																					label={ __( 'Enable Custom Content' ) }
																					checked={ ( itemData && undefined !== itemData.menu_item_custom ? itemData.menu_item_custom : false ) }
																					onChange={ value => this.saveDataState( 'menu_item_custom', value ) }
																				/>
																				{ itemData && undefined !== itemData.menu_item_custom && itemData.menu_item_custom && (
																					<div className={ 'kadence-control-field' }>
																						<span className="customize-control-title">{  __( 'Use a Custom Element', 'kadence-pro' )  }</span>
																						<div className="kt-meta-select-wrap">
																							<Select
																								options={ JSON.parse( kadenceProMegaParams.elements ) }
																								className="kt-meta-select"
																								classNamePrefix="ktp"
																								value={ ( itemData && undefined !== itemData.menu_item_custom_element && '' !== itemData.menu_item_custom_element ? JSON.parse( itemData.menu_item_custom_element ) : {} ) }
																								isMulti={ false }
																								isSearchable={ true }
																								isClearable={ true }
																								maxMenuHeight={ 200 }
																								placeholder={ __( 'None' ) }
																								onChange={ ( val ) => {
																									if ( ! val ) {
																										this.saveDataState( 'menu_item_custom_element', '' )
																									} else {
																										this.saveDataState( 'menu_item_custom_element', JSON.stringify( val ) )
																									}
																								} }
																							/>
																						</div>
																					</div>
																				) }
																			</Fragment>
																		) }
																		{ this.state.megaSetting == 'parent' && (
																			<Fragment>
																				<ToggleControl
																					label={ __( 'Enable Mega Menu Dropdown' ) }
																					checked={ ( itemData && undefined !== itemData.mega_menu ? itemData.mega_menu : false ) }
																					onChange={ value => this.saveDataState( 'mega_menu', value ) }
																				/>
																				{ itemData && undefined !== itemData.mega_menu && itemData.mega_menu && (
																					<Fragment>
																						<SelectControl
																							label={ __( 'Mega Menu Width', 'kadence-pro' ) }
																							options={ [
																								{
																									label: __( 'Content', 'kadence-pro' ),
																									value: 'content',
																								},
																								{
																									label: __( 'Menu Container Width', 'kadence-pro' ),
																									value: 'container',
																								},
																								{
																									label: __( 'Full Width', 'kadence-pro' ),
																									value: 'full',
																								},
																							] }
																							value={ ( itemData && undefined !== itemData.mega_menu_width ? itemData.mega_menu_width : 'container' ) }
																							onChange={  value => this.saveDataState( 'mega_menu_width', value ) }
																						/>
																						<RadioIconControl
																							label={ __( 'Mega Menu Columns', 'kadence-pro' ) }
																							options={ {
																								'1': {
																									name: '1',
																								},
																								'2': {
																									name: '2',
																								},
																								'3': {
																									name: '3',
																								},
																								'4': {
																									name: '4',
																								},
																								'5': {
																									name: '5',
																								},
																								'6': {
																									name: '6',
																								},
																							} }
																							default={ '3' }
																							value={ ( itemData && undefined !== itemData.mega_menu_columns && '' !== itemData.mega_menu_columns ? itemData.mega_menu_columns : '3' ) }
																							onChange={  value => {
																								if ( value !== ( itemData && undefined !== itemData.mega_menu_columns ? itemData.mega_menu_columns : '' ) ) {
																									this.saveDataState( 'mega_menu_layout', 'equal' );
																									this.saveDataState( 'mega_menu_columns', value );
																								}
																							} }
																						/>
																						<RadioIconControl
																							label={ __( 'Mega Menu Column Layout', 'kadence-pro' ) }
																							class={ 'kadence-layout-control' }
																							options={ layouts[ ( itemData && undefined !== itemData.mega_menu_columns && '' !== itemData.mega_menu_columns ? itemData.mega_menu_columns : '3' ) ]}
																							default={ 'equal' }
																							value={ ( itemData && undefined !== itemData.mega_menu_layout && '' !== itemData.mega_menu_layout ? itemData.mega_menu_layout : 'equal' ) }
																							onChange={  value => this.saveDataState( 'mega_menu_layout', value ) }
																						/>
																					</Fragment>
																				) }
																			</Fragment>
																		) }
																	</div>
																	{ this.state.megaSetting == 'child' && (
																		<div class="menu-modal-section">
																			<BorderControl
																				label={ __( 'Column Heading Item Divider' ) }
																				value={ ( itemData && undefined !== itemData.menu_item_custom_divider && '' !== itemData.menu_item_custom_divider ? JSON.parse( itemData.menu_item_custom_divider ) : '' ) }
																				onChange={ value => {
																					this.saveDataState( 'menu_item_custom_divider', JSON.stringify( value ) );
																				} }
																			/>
																		</div>
																	) }
																	{ this.state.megaSetting == 'parent' && itemData && undefined !== itemData.mega_menu && itemData.mega_menu && (
																		<div class="menu-modal-section">
																			<h2>{ __( 'Mega DropDown Background', 'kadence-pro' ) }</h2>
																			<BackgroundControl
																				label={ __( 'Dropdown Background' ) }
																				value={ ( itemData && undefined !== itemData.menu_dropdown_background && '' !== itemData.menu_dropdown_background ? JSON.parse( itemData.menu_dropdown_background ) : {} ) }
																				colorDefault={ '' }
																				onOpen={ () => {
																					this.setState( { isSubOpen:true } );
																				} }
																				onClose={ () => {
																					this.setState( { isSubOpen:false } );
																				} }
																				onChange={ value => {
																					this.saveDataState( 'menu_dropdown_background', JSON.stringify( value ) );
																				} }
																			/>
																		</div>
																	) }
																	{ this.state.megaSetting == 'parent' && itemData && undefined !== itemData.mega_menu && itemData.mega_menu && (
																		<div class="menu-modal-section">
																			<h2>{ __( 'Mega DropDown Item Colors', 'kadence-pro' ) }</h2>
																			<PopColorsControl
																				label={ __( 'Links Color' ) }
																				value={ ( itemData && undefined !== itemData.menu_dropdown_item_color && '' !== itemData.menu_dropdown_item_color ? JSON.parse( itemData.menu_dropdown_item_color ) : {} ) }
																				options={ {
																					'color': {
																						name: 'Color',
																					},
																					'hover': {
																						name: 'Hover',
																					},
																					'active': {
																						name: 'Active',
																					},
																				} }
																				onChange={ value => {
																					this.saveDataState( 'menu_dropdown_item_color', JSON.stringify( value ) );
																				} }
																			/>
																			<PopColorsControl
																				label={ __( 'Links Background' ) }
																				value={ ( itemData && undefined !== itemData.menu_dropdown_item_background && '' !== itemData.menu_dropdown_item_background ? JSON.parse( itemData.menu_dropdown_item_background ) : {} ) }
																				options={ {
																					'color': {
																						name: 'Color',
																					},
																					'hover': {
																						name: 'Hover',
																					},
																					'active': {
																						name: 'Active',
																					},
																				} }
																				onChange={ value => {
																					this.saveDataState( 'menu_dropdown_item_background', JSON.stringify( value ) );
																				} }
																			/>
																			<BorderControl
																				label={ __( 'Links Item Divider' ) }
																				value={ ( itemData && undefined !== itemData.menu_dropdown_item_divider && '' !== itemData.menu_dropdown_item_divider && this.isJson( itemData.menu_dropdown_item_divider ) ? JSON.parse( itemData.menu_dropdown_item_divider ) : '' ) }
																				onChange={ value => {
																					this.saveDataState( 'menu_dropdown_item_divider', JSON.stringify( value ) );
																				} }
																			/>
																		</div>
																	) }
																</Fragment>
															);
														} else {
															tabout = (
																<Fragment>
																	<div class="menu-modal-section">
																		<h2>{ __( 'Menu Item', 'kadence-pro' ) }</h2>
																		<ToggleControl
																			label={ __( 'Hide Text Label' ) }
																			checked={ ( itemData && undefined !== itemData.menu_label ? itemData.menu_label : false ) }
																			onChange={ value => this.saveDataState( 'menu_label', value ) }
																		/>
																		<ToggleControl
																			label={ __( 'Disable Link' ) }
																			checked={ ( itemData && undefined !== itemData.menu_link ? itemData.menu_link : false ) }
																			onChange={ value => this.saveDataState( 'menu_link', value ) }
																		/>
																		<div class="components-base-control components-icon-choice-control">
																			<div class="components-base-control__field">
																				<h3 class="components-toggle-control__label">{ __( 'Menu Item Icon', 'kadence-pro' ) }</h3>
																				<IconControl
																					value={ ( itemData && undefined !== itemData.menu_icon ? itemData.menu_icon : '' ) }
																					onChange={ value => {
																						if ( ! value ) {
																							this.saveDataState( 'menu_icon', '' );
																							this.saveDataState( 'menu_icon_svg', '' );
																						} else {
																							this.saveDataState( 'menu_icon', value );
																							this.saveDataState( 'menu_icon_svg', renderToString( <IconRender name={ value } /> ) );
																						}
																					} }
																				/>
																			</div>
																		</div>
																	</div>
																	<div class="menu-modal-section">
																		<h2>{ __( 'Highlight Label', 'kadence-pro' ) }</h2>
																		<TextControl
																			label={ __( 'Highlight Label', 'kadence-pro' ) }
																			value={ ( itemData && undefined !== itemData.menu_highlight ? itemData.menu_highlight : '' ) }
																			onChange={ value => {
																				this.saveDataState( 'menu_highlight', value );
																			} }
																		/>
																		<div class="components-base-control components-icon-choice-control">
																			<div class="components-base-control__field">
																				<h3 class="components-toggle-control__label">{ __( 'Highlight Icon', 'kadence-pro' ) }</h3>
																				<IconControl
																					value={ ( itemData && undefined !== itemData.menu_highlight_icon ? itemData.menu_highlight_icon : '' ) }
																					onChange={ value => {
																						if ( ! value ) {
																							this.saveDataState( 'menu_highlight_icon', '' );
																							this.saveDataState( 'menu_highlight_icon_svg', '' );
																						} else {
																							this.saveDataState( 'menu_highlight_icon', value );
																							this.saveDataState( 'menu_highlight_icon_svg', renderToString( <IconRender name={ value } /> ) );
																						}
																					} }
																				/>
																			</div>
																		</div>
																		<PopColorControl
																			label={ __( 'Highlight Label Color' ) }
																			colorValue={ ( itemData && undefined !== itemData.menu_highlight_color ? itemData.menu_highlight_color : '' ) }
																			colorDefault={ '' }
																			onColorChange={ value => {
																				this.saveDataState( 'menu_highlight_color', value );
																			} }
																		/>
																		<PopColorControl
																			label={ __( 'Highlight Label Background' ) }
																			colorValue={ ( itemData && undefined !== itemData.menu_highlight_background ? itemData.menu_highlight_background : '' ) }
																			colorDefault={ '' }
																			onColorChange={ value => {
																				this.saveDataState( 'menu_highlight_background', value );
																			} }
																		/>
																	</div>
																</Fragment>
															);
														}
													}
													return <div>{ tabout }</div>;
												}
											}
										</TabPanel>
										{ this.state.error && (
											<div className="kadence-menu-error">
												{ this.state.error }
											</div>
										) }
										{ this.state.isFetching && (
											<Spinner />
										) }
									</div>
									<div className="kadence-pro-menu-footer">
										{ this.state.isSaving && (
											<Spinner />
										) }
										<Button className="kt-menu-save" isPrimary disabled={ this.state.isSaving } onClick={ () => {
												this.saveMeta( this.props.id );
											} }>
												{ __( 'Save' ) }
										</Button>
									</div>
								</Fragment>
							) }
					</Modal>
				) }
			</Fragment>
		);
	}
}
export default ( KadenceMegaMenu );