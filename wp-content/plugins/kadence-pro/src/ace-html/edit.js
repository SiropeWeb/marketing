const { Fragment, Component } = wp.element;
const { withSelect } = wp.data;
const {
	InspectorControls,
	BlockControls,
	transformStyles,
} = wp.blockEditor;
const { __ } = wp.i18n;
import brace from 'brace';
import AceEditor from 'react-ace';
//window.ace.require = window.ace.acequire;
import 'brace/mode/html';
// import 'brace/mode/css';
// import 'brace/mode/javascript';
import 'brace/theme/tomorrow';
//import "ace-builds/src-min-noconflict/ext-language_tools";
// import "ace-builds/src-noconflict/mode-html";
// import "ace-builds/src-noconflict/mode-javascript";
// import "ace-builds/src-noconflict/mode-css";
// //import "ace-builds/src-noconflict/mode-php";
// import "ace-builds/src-noconflict/theme-tomorrow";
const {
	SelectControl,
	SandBox,
	ToolbarGroup,
	PanelBody,
	Button,
} = wp.components;
class ACEHTMLEdit extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			isPreview: false,
			styles: [],
		};
		this.switchToHTML = this.switchToHTML.bind( this );
		this.switchToPreview = this.switchToPreview.bind( this );
	}

	componentDidMount() {
		const { styles } = this.props;

		// Default styles used to unset some of the styles
		// that might be inherited from the editor style.
		const defaultStyles = `
			html,body,:root {
				margin: 0 !important;
				padding: 0 !important;
				overflow: visible !important;
				min-height: auto !important;
			}
		`;

		this.setState( {
			styles: [ defaultStyles, ...transformStyles( styles ) ],
		} );
	}
	switchToPreview() {
		this.setState( { isPreview: true } );
	}
	switchToHTML() {
		this.setState( { isPreview: false } );
	}
	render() {
		const { attributes, setAttributes } = this.props;
		const { isPreview, styles } = this.state;
        var key = this.props.clientId;

        function onChangeHandler(e) {
            setAttributes( { content: e } )
        }
        function onLoadHandler(editor) {
            // solution found at https://stackoverflow.com/questions/33232632/how-can-i-remove-the-first-doctype-tooltip-of-the-ace-editor-in-my-html-editor
            var session = editor.getSession();
            session.on("changeAnnotation", function () {
                let annotations = session.getAnnotations() || []
                let i = annotations.length;
                let len = annotations.length;
                while (i--) {
                    if (/doctype first\. Expected/.test(annotations[i].text)) {
                        annotations.splice(i, 1);
                    }
                    else if (/Unexpected End of file\. Expected/.test(annotations[i].text)) {
                        annotations.splice(i, 1);
                    }
                }
                if (len > annotations.length) {
                    session.setAnnotations(annotations);
                }
            });
        }
       
        return (
			<Fragment>
				{ 'html' === ( attributes.language ? attributes.language : 'html' ) && (
					<BlockControls>
						<ToolbarGroup>
							<Button
								className="components-tab-button"
								isPressed={ ! isPreview }
								onClick={ this.switchToHTML }
							>
								<span>HTML</span>
							</Button>
							<Button
								className="components-tab-button"
								isPressed={ isPreview }
								onClick={ this.switchToPreview }
							>
								<span>{ __( 'Preview', 'kadence-pro' ) }</span>
							</Button>
						</ToolbarGroup>
					</BlockControls>
				) }
				{/* <InspectorControls>
					<PanelBody>
						<SelectControl
							label={ __( 'Language' ) }
							options={ [
								{
									label: __( 'HTML', 'kadence-pro' ),
									value: 'html',
								},
								{
									label: __( 'Mixed - Allow PHP', 'kadence-pro' ),
									value: 'php',
								},
							] }
							value={ ( attributes.language ? attributes.language : 'html' ) }
							onChange={ ( value ) => setAttributes( { language: value } ) }
						/>
					</PanelBody>
				</InspectorControls> */}
				{ isPreview && (
					<Fragment>
						<SandBox
							html={ attributes.content }
							styles={ styles }
						/>
						{ /*	
							An overlay is added when the block is not selected in order to register click events. 
							Some browsers do not bubble up the clicks from the sandboxed iframe, which makes it 
							difficult to reselect the block. 
						*/ }
						{ ! this.props.isSelected && (
							<div className="block-library-html__preview-overlay"></div>
						) }
					</Fragment>
				) }
				{ ! isPreview && (
					<AceEditor
						name= {"ace_" + key}
						placeholder={ ( attributes.language && 'php' === attributes.language ? __( 'Write Code', 'kadence-pro' ) : __( 'Write HTML…', 'kadence-pro' ) ) }
						mode={ ( attributes.language && 'php' === attributes.language ? '' : 'html' ) }
						theme="tomorrow"
						value={attributes.content}

						onChange={onChangeHandler}
						onLoad={onLoadHandler}

						height="auto"
						width="100%"
						wrapEnabled={true}
						minLines={20}
						maxLines={Infinity}
					/>
				) }
			</Fragment>
		);
	}
}
export default withSelect( ( select ) => {
	const { getSettings } = select( 'core/block-editor' );
	return {
		styles: getSettings().styles,
	};
} )( ACEHTMLEdit );