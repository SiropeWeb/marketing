import RadioIconComponent from './radio-icon.js';
import capitalizeFirstLetter from './capitalize-first.js';
import Select from 'react-select';
import DisplaySettings from './display-settings.js';
import UserSettings from './user-settings.js';
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { __ } = wp.i18n;
const { parse, createBlock, serialize } = wp.blocks;
const {
	Component,
	Fragment,
} = wp.element;
const {
	ToggleControl,
	SelectControl,
	PanelBody,
	RangeControl,
	TextControl,
	Panel,
	FormToggle,
	Modal,
	DateTimePicker,
} = wp.components;
const { __experimentalGetSettings } = wp.date;
const { withSelect, withDispatch, select, dispatch } = wp.data;
const { compose } = wp.compose;

class KadenceElements extends Component {
	constructor() {
		super( ...arguments );
		this.changeTemplate = this.changeTemplate.bind( this );
		this.state = {
			date: new Date(),
			modalOpen: true,
			hasBlocks: false,
		};
	}
	componentDidMount() {
		//console.log( select( 'core/editor' ).getCurrentPost() );
		const thePost = select( 'core/editor' ).getCurrentPost();
		// if ( thePost.content ) {
		// 	console.log( parse( thePost.content ) );
		// }
		this.setState( {
			hasBlocks: ( thePost.content ? true : false ),
		} );
		// if ( 'script' === this.props.meta._kad_element_type ) {
		// 	console.log( 'test' );
		// 	//wp.data.dispatch( 'core/block-editor' ).setTemplateValidity( true );
		// }
	}
	changeTemplate( type ) {

		const { resetBlocks } = dispatch('core/block-editor');
		// const postType = select( 'core/editor' ).getEditedPostAttribute( 'type' );
		// const id = select( 'core/editor' ).getEditedPostAttribute( 'id' );
		let templateOutput;
		let lockTemplate = false;
		if ( 'script' === type ) {
			if ( kadenceElementParams.ace ) {
				templateOutput = [
					createBlock( 'kadence-pro/ace-html' ),
				];
			} else {
				templateOutput = [
					createBlock( 'core/html' ),
				];
			}
			lockTemplate = true;
		} else if ( 'header' === type ) {
			templateOutput = [
				createBlock( 'core/image', {} ),
				createBlock( 'core/paragraph', { placeholder: 'Image Details' } ),
			];
		} else {
			templateOutput = [];
		}
		resetBlocks( templateOutput );
		wp.data.dispatch('core/block-editor').updateSettings( {
			templateLock: lockTemplate,
		} );
		this.setState( { modalOpen: false } )
		this.props.setMetaFieldValue( type, '_kad_element_type' );
	}
	render() {
		const settings = __experimentalGetSettings();
 
		// To know if the current timezone is a 12 hour time with look for an "a" in the time format.
		// We also make sure this a is not escaped by a "/".
		const is12HourTime = /a(?!\\)/i.test(
			settings.formats.time
				.toLowerCase() // Test only the lower case a
				.replace( /\\\\/g, '' ) // Replace "//" with empty strings
				.split( '' ).reverse().join( '' ) // Reverse the string and test for "a" not followed by a slash
		);
		const hookOptions = [].concat.apply( [], kadenceElementParams.hooks.map( option => option.options ) );
		const codeHookOptions = [].concat.apply( [], kadenceElementParams.codeHooks.map( option => option.options ) );
		const fixedHookOptions = [].concat.apply( [], kadenceElementParams.fixedHooks.map( option => option.options ) );
		const userOptions = [].concat.apply( [], kadenceElementParams.user.map( option => option.options ) );
		const userRoleSetting = ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_user_conditionals && '' !== this.props.meta._kad_element_user_conditionals ? JSON.parse( this.props.meta._kad_element_user_conditionals ) : '' );
		const displaySetting = ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_show_conditionals && '' !== this.props.meta._kad_element_show_conditionals ? JSON.parse( this.props.meta._kad_element_show_conditionals ) : '' );
		const check_show_rules = ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_show_conditionals && '' !== this.props.meta._kad_element_show_conditionals ? JSON.parse( this.props.meta._kad_element_show_conditionals ) : [] )
		const elementTypeOptions = {
			'default': {
				'name': __( 'Default', 'kadence-pro' ),
			},
			'fixed': {
				'name': __( 'Fixed', 'kadence-pro' ),
			},
			'script': {
				'name': __( 'Code', 'kadence-pro' ),
			},
		}
		const deviceOptions = [
			{
				'value' : 'desktop',
				'label': __( 'Desktop', 'kadence-pro' ),
			},
			{
				'value' : 'tablet',
				'label': __( 'Tablet', 'kadence-pro' ),
			},
			{
				'value' : 'mobile',
				'label': __( 'Mobile', 'kadence-pro' ),
			},
		];
		const featuredPositionOptions = {
			'default': {
				'name': __( 'Default', 'kadence-pro' ),
			},
			'above': {
				'name': __( 'Above', 'kadence-pro' ),
			},
			'behind': {
				'name': __( 'Behind', 'kadence-pro' ),
			},
			'below': {
				'name': __( 'Below', 'kadence-pro' ),
			},
		}
		const transparentOptions = {
			'default': {
				'name': __( 'Default', 'kadence-pro' ),
			},
			'enable': {
				'name': __( 'Enable', 'kadence-pro' ),
			},
			'disable': {
				'name': __( 'Disable', 'kadence-pro' ),
			},
		}
		const icon = <svg width="20px" height="20px"
		xmlns="http://www.w3.org/2000/svg"
		fillRule="evenodd"
		strokeLinejoin="round"
		strokeMiterlimit="2"
		clipRule="evenodd"
		viewBox="0 0 50 40"
	  >
		<path fill="#CDCDCD" d="M9.857 8.351H29.519V15.874H9.857z"></path>
		<path
		  fill="#CCC"
		  fillRule="nonzero"
		  d="M10.259 17.908h18.847c.225 0 .41.354.41.786 0 .431-.185.785-.41.785H10.259c-.225 0-.41-.354-.41-.785 0-.432.185-.786.41-.786z"
		></path>
		<path
		  fill="#8E8E8E"
		  d="M47.109 38.98H2.891a1.9 1.9 0 01-1.898-1.898V2.918A1.9 1.9 0 012.891 1.02h44.218a1.9 1.9 0 011.898 1.898v34.164a1.9 1.9 0 01-1.898 1.898zm-.102-33.614H2.993V36.98h44.014V5.366zm-8.172-2.94a.9.9 0 110 1.8.9.9 0 010-1.8zm7.153 0a.9.9 0 110 1.8.9.9 0 010-1.8zm-3.538 0a.9.9 0 110 1.8.9.9 0 010-1.8z"
		></path>
		<path
		  fill="#515151"
		  d="M40.119 13.838l4.705 4.844-10.54 9.899a110.5 110.5 0 01-3.115 1.566 64.17 64.17 0 01-2.948 1.35 32.236 32.236 0 01-1.114.445 13 13 0 01-.794.269 4.38 4.38 0 01-.619.145 1.67 1.67 0 01-.189.018h-.061c-.089-.003-.206-.018-.258-.101-.043-.068-.043-.159-.038-.235l.007-.061a2.98 2.98 0 01.179-.646c.09-.245.193-.485.301-.722.186-.408.387-.809.594-1.206.369-.708.759-1.405 1.157-2.097a104.799 104.799 0 012.183-3.624l10.55-9.844zM30.686 24.71l2.542 2.725-3.053 1.621-1.329-1.217 1.84-3.129zm11.137-12.463l2.23-2.081s1.959-1.222 4.028.819c1.729 1.706.765 3.92.765 3.92l-2.323 2.182-4.7-4.84z"
		></path>
		<path
		  fill="#E5E5E5"
		  d="M40.152 26.389v7.571h-8.567v-1.649l.108-.045c.987-.415 1.96-.862 2.92-1.336a44.58 44.58 0 001.751-.906l3.788-3.635zm0-15.912l-8.567 8.041V8.421h8.567v2.056z"
		></path>
		<path
		  fill="#CCC"
		  fillRule="nonzero"
		  d="M28.872 21.063l-.592.557s-.284.383-.716 1.015H10.259a.256.256 0 01-.039-.003.332.332 0 01-.19-.132l-.023-.033c-.01-.014-.018-.029-.027-.043a1.15 1.15 0 01-.124-.436 1.435 1.435 0 01.006-.334c.012-.091.033-.18.066-.266.009-.025.02-.049.031-.073l.032-.059.027-.041a.392.392 0 01.025-.032.308.308 0 01.177-.116.515.515 0 01.039-.004h18.613z"
		></path>
		<path
		  fill="#CDCDCD"
		  fillRule="nonzero"
		  d="M26.519 24.219a47.303 47.303 0 00-.953 1.572H10.259a.172.172 0 01-.039-.004.326.326 0 01-.19-.131.405.405 0 01-.023-.034c-.01-.014-.018-.028-.027-.043a1.156 1.156 0 01-.124-.436 1.426 1.426 0 01.006-.333c.012-.091.033-.181.066-.266.009-.025.02-.049.031-.073l.032-.059.027-.041a.392.392 0 01.025-.032.308.308 0 01.177-.116.172.172 0 01.039-.004h16.26z"
		></path>
		<path
		  fill="#CCC"
		  fillRule="nonzero"
		  d="M23.417 30.531c-.152.573-.233 1.106-.214 1.571H10.259a.256.256 0 01-.039-.003.328.328 0 01-.19-.132l-.023-.033c-.01-.014-.018-.029-.027-.043a1.162 1.162 0 01-.124-.436 1.436 1.436 0 01.006-.334c.012-.09.033-.18.066-.266.009-.025.02-.049.031-.073l.032-.059.027-.041a.392.392 0 01.025-.032.313.313 0 01.177-.116.256.256 0 01.039-.003h13.158z"
		></path>
		<path
		  fill="#CDCDCD"
		  fillRule="nonzero"
		  d="M24.701 27.375a22.69 22.69 0 00-.732 1.572h-13.71c-.013 0-.026-.002-.039-.004a.326.326 0 01-.19-.131.405.405 0 01-.023-.034c-.01-.014-.018-.028-.027-.043a1.162 1.162 0 01-.124-.436 1.436 1.436 0 01.006-.334 1.177 1.177 0 01.097-.338c.01-.021.021-.04.032-.059l.027-.042a.698.698 0 01.025-.032.312.312 0 01.177-.115.172.172 0 01.039-.004h14.442z"
		></path>
	  </svg>;
	 // console.log( kadenceElementParams.display );
		return (
			<Fragment>
				{ ! this.state.hasBlocks && this.state.modalOpen && (
					<Modal
						className="ktp-elememt-modal"
						title={ __( 'Element Setup' ) }
						onRequestClose={ () => this.changeTemplate( 'default' ) }>
						<RadioIconComponent
							label={ __( 'Choose Element Type' ) }
							value={ '' }
							//customClass="three-col-short"
							options={ elementTypeOptions }
							onChange={ value => {
								this.changeTemplate( value );
							} }
						/>
					</Modal>
				) }
				<PluginSidebarMoreMenuItem
					target="theme-meta-panel"
					icon={ icon }
				>
					{ __( 'Element Settings', 'kadence-pro' ) }
				</PluginSidebarMoreMenuItem>
				<PluginSidebar
					isPinnable={ true }
					icon={ icon }
					name="theme-meta-panel"
					title={ __( 'Element Settings', 'kadence-pro' ) }
				>
					<div className="kadence-sidebar-container components-panel__body is-opened">
						<h2 className="kt-meta-title">{ __( 'Placement', 'kadence-pro' ) }</h2>
						<div className="kt-meta-select-wrap">
							{ 'script' === ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_type && '' !== this.props.meta._kad_element_type ? this.props.meta._kad_element_type : 'default' ) && (
								<Select
									options={ kadenceElementParams.codeHooks }
									className="kt-meta-select"
									classNamePrefix="ktp"
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && '' !== this.props.meta._kad_element_hook ? codeHookOptions.filter( ( { value } ) => value === this.props.meta._kad_element_hook ) : '' ) }
									isMulti={ false }
									isSearchable={ true }
									isClearable={ true }
									maxMenuHeight={ 200 }
									placeholder={ __( 'None' ) }
									onChange={ ( val ) => {
										if ( ! val ) {
											this.props.setMetaFieldValue( '', '_kad_element_hook' );
										} else {
											this.props.setMetaFieldValue( val.value, '_kad_element_hook' );
										}
									} }
								/>
							) }
							{ 'fixed' === ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_type && '' !== this.props.meta._kad_element_type ? this.props.meta._kad_element_type : 'default' ) && (
								<Select
									options={ kadenceElementParams.fixedHooks }
									className="kt-meta-select"
									classNamePrefix="ktp"
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && '' !== this.props.meta._kad_element_hook ? fixedHookOptions.filter( ( { value } ) => value === this.props.meta._kad_element_hook ) : '' ) }
									isMulti={ false }
									isSearchable={ true }
									isClearable={ true }
									maxMenuHeight={ 200 }
									placeholder={ __( 'None' ) }
									onChange={ ( val ) => {
										if ( ! val ) {
											this.props.setMetaFieldValue( '', '_kad_element_hook' );
										} else {
											this.props.setMetaFieldValue( val.value, '_kad_element_hook' );
										}
									} }
								/>
							) }
							{ 'default' === ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_type && '' !== this.props.meta._kad_element_type ? this.props.meta._kad_element_type : 'default' ) && (
								<Select
									options={ kadenceElementParams.hooks }
									className="kt-meta-select"
									classNamePrefix="ktp"
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && '' !== this.props.meta._kad_element_hook ? hookOptions.filter( ( { value } ) => value === this.props.meta._kad_element_hook ) : '' ) }
									isMulti={ false }
									isSearchable={ true }
									isClearable={ true }
									maxMenuHeight={ 200 }
									placeholder={ __( 'None' ) }
									onChange={ ( val ) => {
										if ( ! val ) {
											this.props.setMetaFieldValue( '', '_kad_element_hook' );
										} else {
											this.props.setMetaFieldValue( val.value, '_kad_element_hook' );
										}
									} }
								/>
							) }
							{ undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && 'custom' === this.props.meta._kad_element_hook && (
								<TextControl
									label={ __( 'Custom Hook', 'kadence-pro' ) }
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook_custom && '' !== this.props.meta._kad_element_hook_custom ? this.props.meta._kad_element_hook_custom : '' ) }
									onChange={ ( val ) => {
										if ( ! val ) {
											this.props.setMetaFieldValue( '', '_kad_element_hook_custom' );
										} else {
											this.props.setMetaFieldValue( val, '_kad_element_hook_custom' );
										}
									} }
								/>
							) }
							{ undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && '' !== this.props.meta._kad_element_hook && (
								 <RangeControl
									label={ __( 'Priority', 'kadence-pro' ) }
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook_priority && '' !== this.props.meta._kad_element_hook_priority ? this.props.meta._kad_element_hook_priority : 10 ) }
									onChange={ ( val ) => {
										if ( ! val ) {
											this.props.setMetaFieldValue( 10, '_kad_element_hook_priority' );
										} else {
											this.props.setMetaFieldValue( val, '_kad_element_hook_priority' );
										}
									} }
									min={ 1 }
									max={ 100 }
								/>
							) }
							{ 'fixed' === ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_type && '' !== this.props.meta._kad_element_type ? this.props.meta._kad_element_type : 'default' ) && undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && 'fixed_on_header' === this.props.meta._kad_element_hook && (
								 <RangeControl
									label={ __( 'Scroll Down Distance till Appear', 'kadence-pro' ) }
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook_scroll && '' !== this.props.meta._kad_element_hook_scroll ? this.props.meta._kad_element_hook_scroll : 300 ) }
									onChange={ ( val ) => {
										if ( '' === val ) {
											this.props.setMetaFieldValue( 300, '_kad_element_hook_scroll' );
										} else {
											this.props.setMetaFieldValue( val, '_kad_element_hook_scroll' );
										}
									} }
									min={ 0 }
									max={ 1000 }
								/>
							) }
							{ 'fixed' === ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_type && '' !== this.props.meta._kad_element_type ? this.props.meta._kad_element_type : 'default' ) && ( ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && 'fixed_on_footer_scroll' === this.props.meta._kad_element_hook ) || ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook && 'fixed_on_footer_scroll_space' === this.props.meta._kad_element_hook ) ) && (
								 <RangeControl
									label={ __( 'Scroll Down Distance till Appear', 'kadence-pro' ) }
									value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hook_scroll && '' !== this.props.meta._kad_element_hook_scroll ? this.props.meta._kad_element_hook_scroll : 300 ) }
									onChange={ ( val ) => {
										if ( '' === val ) {
											this.props.setMetaFieldValue( 300, '_kad_element_hook_scroll' );
										} else {
											this.props.setMetaFieldValue( val, '_kad_element_hook_scroll' );
										}
									} }
									min={ 0 }
									max={ 1000 }
								/>
							) }
						</div>
					</div>
					<PanelBody
						title={ ( '' == displaySetting || ( undefined !== displaySetting[0] && undefined !== displaySetting[0].rule && '' == displaySetting[0].rule ) ? __( 'Display Settings [UNSET]' ) :  __( 'Display Settings' ) ) }
						initialOpen={ false }
					>
						<DisplaySettings
							label={ __( 'Show On', 'kadence-pro' ) }
							rules={ kadenceElementParams.display }
							value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_show_conditionals && '' !== this.props.meta._kad_element_show_conditionals ? this.props.meta._kad_element_show_conditionals : '' ) }
							id={ 'kad_element_show_conditionals' }
							onChange={ ( val ) => {
								if ( ! val ) {
									this.props.setMetaFieldValue( '', '_kad_element_show_conditionals' );
								} else {
									this.props.setMetaFieldValue( val, '_kad_element_show_conditionals' );
								}
							} }
						/>
						{ check_show_rules.length && check_show_rules.length > 1 && (
							<div className={ 'all-show-rules' } style={ {
									marginTop:'20px',
								} }
							>
								<ToggleControl
									label={ __( 'All show rules must be true?' ) }
									checked={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_all_show && '' !== this.props.meta._kad_element_all_show ? this.props.meta._kad_element_all_show : false ) }
									onChange={ ( value ) => this.props.setMetaFieldValue( value, '_kad_element_all_show' ) }
								/>
							</div>
						) }
						<div style={ {
								height: '30px',
							} }
						>
						</div>
						<PanelBody
							title={ __( 'Exclude Settings' ) }
							initialOpen={ false }
						>
							<DisplaySettings
								label={ __( 'Hide On', 'kadence-pro' ) }
								rules={ kadenceElementParams.display }
								id={ 'kad_element_hide_conditionals' }
								value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_hide_conditionals && '' !== this.props.meta._kad_element_hide_conditionals ? this.props.meta._kad_element_hide_conditionals : '' ) }
								setMetaFieldValue={ ( value, meta_field ) => this.props.setMetaFieldValue( value, meta_field ) }
								onChange={ ( val ) => {
									if ( ! val ) {
										this.props.setMetaFieldValue( '', '_kad_element_hide_conditionals' );
									} else {
										this.props.setMetaFieldValue( val, '_kad_element_hide_conditionals' );
									}
								} }
							/>
						</PanelBody>
					</PanelBody>
					<PanelBody
						title={ ( '' == userRoleSetting || ( undefined !== userRoleSetting[0] && undefined !== userRoleSetting[0].role && '' == userRoleSetting[0].role ) ? __( 'User Settings [UNSET]' ) :  __( 'User Settings' ) ) }
						initialOpen={ false }
					>
						<UserSettings
							label={ __( 'Visible to', 'kadence-pro' ) }
							roles={ kadenceElementParams.user }
							value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_user_conditionals && '' !== this.props.meta._kad_element_user_conditionals ? this.props.meta._kad_element_user_conditionals : '' ) }
							onChange={ ( val ) => {
								if ( ! val ) {
									this.props.setMetaFieldValue( '', '_kad_element_user_conditionals' );
								} else {
									this.props.setMetaFieldValue( val, '_kad_element_user_conditionals' );
								}
							} }
						/>
					</PanelBody>
					{ 'script' !== ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_type && '' !== this.props.meta._kad_element_type ? this.props.meta._kad_element_type : 'default' ) && (
						<PanelBody
							title={ __( 'Device Settings' ) }
							initialOpen={ false }
						>
							<h2 className="kt-meta-title">{ __( 'Show on Device', 'kadence-pro' ) }</h2>
							<Select
								options={ deviceOptions }
								className="kt-meta-select"
								classNamePrefix="ktp"
								value={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_device_conditionals && '' !== this.props.meta._kad_element_device_conditionals ? JSON.parse( this.props.meta._kad_element_device_conditionals ) : [] ) }
								isMulti={ true }
								isSearchable={ false }
								isClearable={ true }
								maxMenuHeight={ 200 }
								placeholder={ __( 'All' ) }
								onChange={ ( val ) => {
									if ( ! val ) {
										this.props.setMetaFieldValue( '', '_kad_element_device_conditionals' );
									} else {
										this.props.setMetaFieldValue( JSON.stringify( val ), '_kad_element_device_conditionals' );
									}
								} }
							/>
						</PanelBody>
					) }
					<PanelBody
						title={ __( 'Expires Settings' ) }
						initialOpen={ false }
					>
						<ToggleControl
							label={ __( 'Enable Expires' ) }
							checked={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_enable_expires && '' !== this.props.meta._kad_element_enable_expires ? this.props.meta._kad_element_enable_expires : false ) }
							onChange={ ( value ) => this.props.setMetaFieldValue( value, '_kad_element_enable_expires' ) }
						/>
						{ undefined !== this.props.meta && undefined !== this.props.meta._kad_element_enable_expires && this.props.meta._kad_element_enable_expires && (
							<Fragment>
								<h2 className="kt-meta-title">{ __( 'Expires', 'kadence-pro' ) }</h2>
								<DateTimePicker
									currentDate={ ( undefined !== this.props.meta && undefined !== this.props.meta._kad_element_expires && '' !== this.props.meta._kad_element_expires ? this.props.meta._kad_element_expires : new Date() ) }
									onChange={ ( date ) => {
										this.props.setMetaFieldValue( date, '_kad_element_expires' );
									} }
									is12Hour={ is12HourTime }
								/>
							</Fragment>
						) }
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		);
	}
}
export default compose(
	withSelect( ( select ) => {
		const postMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
		const oldPostMeta = select( 'core/editor' ).getCurrentPostAttribute( 'meta' );
		return {
			meta: { ...oldPostMeta, ...postMeta },
			oldMeta: oldPostMeta,
		};
	} ),
	withDispatch( ( dispatch ) => ( {
		setMetaFieldValue: ( value, field ) => dispatch( 'core/editor' ).editPost(
			{ meta: { [ field ]: value } }
		),
	} ) ),
)( KadenceElements );