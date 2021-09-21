/**
 * Pro: Kadence Pro License Inputs
 */
const { addQueryArgs } = wp.url;
const { apiFetch } = wp;
/**
 * Internal block libraries
 */
import map from 'lodash/map';
const { __ } = wp.i18n;
const {
	Component,
	Fragment,
} = wp.element;
const {
	PanelBody,
	TextControl,
	Button,
	Panel,
	Spinner,
	ToggleControl,
	SelectControl,
	ExternalLink,
} = wp.components;
/**
 * Build the Measure controls
 * @returns {object} Measure settings.
 */
class ProModules extends Component {
	constructor() {
		super( ...arguments );
		this.saveConfig = this.saveConfig.bind( this );
		this.runAjax = this.runAjax.bind( this );
		this.state = {
			settings: ( kadenceProDashboardParams.settings ? JSON.parse( kadenceProDashboardParams.settings ) : {} ),
			isSaving: false,
		};
	}
	runAjax() {
		var newData = new FormData();
		newData.append( 'action', 'kadence_add_elementor' );
		newData.append( 'security', kadenceProDashboardParams.ajax_nonce );
		jQuery.ajax({
			method:      'POST',
			url:         kadenceProDashboardParams.ajax_url,
			data:        newData,
			contentType: false,
			processData: false,
		})
		.done( function( response ) {
			if ( response.success ) {
				console.log( 'element_success' );
			} else {
				console.log( response );
			}
		})
		.fail( function( error ) {
			console.log( error );
		});
	}
	saveConfig( addonID, settingToggle ) {
		if ( addonID === 'elements' && settingToggle ) {
			this.runAjax();
		}
		this.setState( { isSaving: addonID } );
		const config = ( kadenceProDashboardParams.settings ? JSON.parse( kadenceProDashboardParams.settings ) : {} );
		if ( ! config[ addonID ] ) {
			config[ addonID ] = {};
		}
		config[ addonID ] = settingToggle;
		const settingModel = new wp.api.models.Settings( { kadence_pro_theme_config: JSON.stringify( config ) } );
		settingModel.save().then( response => {
			this.setState( { isSaving: false, settings: config, isOpen: false } );
			kadenceProDashboardParams.settings = JSON.stringify( config );
		} );
	}
	render() {
		const { list, listsLoaded, isFetching, isSavedAPI, listAttr, isFetchingAttributes, listAttrLoaded, isFetchingGroups, listGroups, listGroupLoaded } = this.state;
		const headerLinks = [
			{
				title: __( 'Header Addons', 'kadence-pro' ),
				description: __( 'Adds 19 elements to the header builder.', 'kadence-pro' ),
				focus: 'kadence_customizer_header',
				type: 'panel',
				setting: 'header_addons',
			},
			{
				title: __( 'Ultimate Menu', 'kadence-pro' ),
				description: __( 'Adds menu options for mega menus, highlight tags, icons and more.', 'kadence-pro' ),
				//focus: 'kadence_customizer_header',
				//type: 'panel',
				setting: 'mega_menu',
				adminLink: 'nav-menus.php',
			},
			{
				title: __( 'Header/Footer Scripts', 'kadence-pro' ),
				description: __( 'Adds Options into the customizer to add header and footer scripts', 'kadence-pro' ),
				//focus: 'kadence_customizer_header',
				//type: 'panel',
				setting: 'scripts',
				focus: 'kadence_customizer_scripts',
				type: 'section',
			},
			{
				title: __( 'Hooked Elements', 'kadence-pro' ),
				description: __( 'Add content anywhere into your site conditionally.', 'kadence-pro' ),
				// focus: 'kadence_customizer_header',
				// type: 'panel',
				setting: 'elements',
				adminLink: 'edit.php?post_type=kadence_element',
			},
			{
				title: __( 'WooCommerce Addons', 'kadence-pro' ),
				description: __( 'Adds new options into the customizer for WooCommerce stores.', 'kadence-pro' ),
				focus: 'woocommerce',
				type: 'panel',
				setting: 'woocommerce_addons',
			},
			{
				title: __( 'Infinite Scroll', 'kadence-pro' ),
				description: __( 'Adds Infinite Scroll for archives.', 'kadence-pro' ),
				focus: 'kadence_customizer_infinite_scroll',
				type: 'section',
				setting: 'infinite',
			},
			{
				title: __( 'Local Gravatars', 'kadence-pro' ),
				description: __( 'Loads Gravatars from your servers to improve site performance.', 'kadence-pro' ),
				focus: '',
				type: '',
				setting: 'localgravatars',
			},
			{
				title: __( 'Archive Custom Page Title Backgrounds', 'kadence-pro' ),
				description: __( 'Allows you to assign a custom image for a taxonomy background.', 'kadence-pro' ),
				focus: '',
				type: '',
				setting: 'archive_meta',
			},
		];
		return (
			<Fragment>
				<h2 className="section-header">{ __( 'Pro Addons', 'kadence-pro' ) }</h2>
				<div className="two-col-grid">
					{ map( headerLinks, ( link ) => {
						const enabled = ( this.state.settings && undefined !== this.state.settings[link.setting] ? this.state.settings[link.setting] : false );
						return (
							<div className="link-item">
								<h4>{ link.title }</h4>
								<p>{ link.description }</p>
								<div className="link-item-foot">
									{ enabled && link.type && link.focus && (
										<a href={ `${kadenceProDashboardParams.adminURL}customize.php?autofocus%5B${ link.type }%5D=${ link.focus }` }>
											{ __( 'Customize', 'kadence-pro') }
										</a>
									) }
									{ enabled && link.adminLink && (
										<a href={ `${kadenceProDashboardParams.adminURL}${ link.adminLink }` }>
											{ __( 'Customize', 'kadence-pro') }
										</a>
									) }
									{ link.setting && (
										<Fragment>
											<div class="spacer"></div>
											{ this.state.isSaving && this.state.isSaving === link.setting && (
												<Spinner />
											) }
											<ToggleControl
												checked={ enabled }
												onChange={ ( value ) => this.saveConfig( link.setting, value ) }
											/>
										</Fragment>
									) }
								</div>
							</div>
						);
					} ) }
				</div>
			</Fragment>
		);
	}
}
export default ( ProModules );