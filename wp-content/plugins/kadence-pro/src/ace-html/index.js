const { RawHTML } = wp.element;
const { registerBlockType, createBlock } = wp.blocks;
const { __ } = wp.i18n;
import edit from './edit';

registerBlockType( 'kadence-pro/ace-html', {
    title: 'Code Editor',
    icon: 'editor-code',
    category: 'formatting',
    supports: {
        className: false,
        html: false,
    },
    attributes: {
        content: {
            type: 'string',
            source: "html"
		},
		language: {
            type: 'string',
            default: 'html',
        }
    },
    transforms: {
        to: [
            {
                type: 'block',
                blocks: [ 'core/html' ],
                transform: function( attributes ) {
                    return createBlock( 'core/html', {
                        content: attributes.content,
                    } );
                },
            },
            {
                type: 'block',
                blocks: [ 'core/shortcode' ],
                transform: function( attributes ) {
                    return createBlock( 'core/shortcode', {
                        content: attributes.content,
                    } );
                },
            },
        ],
        from: [
            {
                type: 'block',
                blocks: [ 'core/html', 'core/shortcode' ],
                transform: function ( attributes ) {
                    return createBlock( 'kadence-pro/ace-html', {
                        content: attributes.content,
                    } );
                },
            },
        ]
    },
    edit,

    save: function( props ) {
		// let content;
		// if ( props.attributes.language === 'css' ) {
		// 	content = '<style>' + props.attributes.content + '</style>';
		// } else if ( props.attributes.language === 'javascript' ) {
		// 	content = <script>{ props.attributes.content }</script>;
		// } else {
		// 	content = props.attributes.content;
		// }
        return  (
			<RawHTML>
				{props.attributes.content}
			</RawHTML>
		);
    }
 } 
);