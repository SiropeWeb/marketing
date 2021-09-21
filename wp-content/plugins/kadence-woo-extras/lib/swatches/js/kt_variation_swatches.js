/*
 * kt variations plugin
 */
var kt_woo_extra_isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (kt_woo_extra_isMobile.Android() || kt_woo_extra_isMobile.BlackBerry() || kt_woo_extra_isMobile.iOS() || kt_woo_extra_isMobile.Opera() || kt_woo_extra_isMobile.Windows());
    }
};
jQuery(document).ready(function ($) {
	var bootstrap_enabled = (typeof $().tooltip == 'function');
	var select2Enable = (typeof $().select2 == 'function');
	if(bootstrap_enabled == false){
		if ( ! kt_woo_extra_isMobile.any() ) {
			/*
			 * Tooltips bootstrap
			 */
			if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(t){"use strict";var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<9||1==e[0]&&9==e[1]&&e[2]<1||e[0]>3)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")}(jQuery),+function(t){"use strict";function e(e){return this.each(function(){var o=t(this),n=o.data("bs.tooltip"),s="object"==typeof e&&e;!n&&/destroy|hide/.test(e)||(n||o.data("bs.tooltip",n=new i(this,s)),"string"==typeof e&&n[e]())})}var i=function(t,e){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",t,e)};i.VERSION="3.3.7",i.TRANSITION_DURATION=150,i.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},i.prototype.init=function(e,i,o){if(this.enabled=!0,this.type=e,this.$element=t(i),this.options=this.getOptions(o),this.$viewport=this.options.viewport&&t(t.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var n=this.options.trigger.split(" "),s=n.length;s--;){var r=n[s];if("click"==r)this.$element.on("click."+this.type,this.options.selector,t.proxy(this.toggle,this));else if("manual"!=r){var a="hover"==r?"mouseenter":"focusin",l="hover"==r?"mouseleave":"focusout";this.$element.on(a+"."+this.type,this.options.selector,t.proxy(this.enter,this)),this.$element.on(l+"."+this.type,this.options.selector,t.proxy(this.leave,this))}}this.options.selector?this._options=t.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},i.prototype.getDefaults=function(){return i.DEFAULTS},i.prototype.getOptions=function(e){return e=t.extend({},this.getDefaults(),this.$element.data(),e),e.delay&&"number"==typeof e.delay&&(e.delay={show:e.delay,hide:e.delay}),e},i.prototype.getDelegateOptions=function(){var e={},i=this.getDefaults();return this._options&&t.each(this._options,function(t,o){i[t]!=o&&(e[t]=o)}),e},i.prototype.enter=function(e){var i=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return i||(i=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,i)),e instanceof t.Event&&(i.inState["focusin"==e.type?"focus":"hover"]=!0),i.tip().hasClass("in")||"in"==i.hoverState?void(i.hoverState="in"):(clearTimeout(i.timeout),i.hoverState="in",i.options.delay&&i.options.delay.show?void(i.timeout=setTimeout(function(){"in"==i.hoverState&&i.show()},i.options.delay.show)):i.show())},i.prototype.isInStateTrue=function(){for(var t in this.inState)if(this.inState[t])return!0;return!1},i.prototype.leave=function(e){var i=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return i||(i=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,i)),e instanceof t.Event&&(i.inState["focusout"==e.type?"focus":"hover"]=!1),i.isInStateTrue()?void 0:(clearTimeout(i.timeout),i.hoverState="out",i.options.delay&&i.options.delay.hide?void(i.timeout=setTimeout(function(){"out"==i.hoverState&&i.hide()},i.options.delay.hide)):i.hide())},i.prototype.show=function(){var e=t.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(e);var o=t.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(e.isDefaultPrevented()||!o)return;var n=this,s=this.tip(),r=this.getUID(this.type);this.setContent(),s.attr("id",r),this.$element.attr("aria-describedby",r),this.options.animation&&s.addClass("fade");var a="function"==typeof this.options.placement?this.options.placement.call(this,s[0],this.$element[0]):this.options.placement,l=/\s?auto?\s?/i,p=l.test(a);p&&(a=a.replace(l,"")||"top"),s.detach().css({top:0,left:0,display:"block"}).addClass(a).data("bs."+this.type,this),this.options.container?s.appendTo(this.options.container):s.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var h=this.getPosition(),f=s[0].offsetWidth,u=s[0].offsetHeight;if(p){var c=a,d=this.getPosition(this.$viewport);a="bottom"==a&&h.bottom+u>d.bottom?"top":"top"==a&&h.top-u<d.top?"bottom":"right"==a&&h.right+f>d.width?"left":"left"==a&&h.left-f<d.left?"right":a,s.removeClass(c).addClass(a)}var v=this.getCalculatedOffset(a,h,f,u);this.applyPlacement(v,a);var g=function(){var t=n.hoverState;n.$element.trigger("shown.bs."+n.type),n.hoverState=null,"out"==t&&n.leave(n)};t.support.transition&&this.$tip.hasClass("fade")?s.one("bsTransitionEnd",g).emulateTransitionEnd(i.TRANSITION_DURATION):g()}},i.prototype.applyPlacement=function(e,i){var o=this.tip(),n=o[0].offsetWidth,s=o[0].offsetHeight,r=parseInt(o.css("margin-top"),10),a=parseInt(o.css("margin-left"),10);isNaN(r)&&(r=0),isNaN(a)&&(a=0),e.top+=r,e.left+=a,t.offset.setOffset(o[0],t.extend({using:function(t){o.css({top:Math.round(t.top),left:Math.round(t.left)})}},e),0),o.addClass("in");var l=o[0].offsetWidth,p=o[0].offsetHeight;"top"==i&&p!=s&&(e.top=e.top+s-p);var h=this.getViewportAdjustedDelta(i,e,l,p);h.left?e.left+=h.left:e.top+=h.top;var f=/top|bottom/.test(i),u=f?2*h.left-n+l:2*h.top-s+p,c=f?"offsetWidth":"offsetHeight";o.offset(e),this.replaceArrow(u,o[0][c],f)},i.prototype.replaceArrow=function(t,e,i){this.arrow().css(i?"left":"top",50*(1-t/e)+"%").css(i?"top":"left","")},i.prototype.setContent=function(){var t=this.tip(),e=this.getTitle();t.find(".tooltip-inner")[this.options.html?"html":"text"](e),t.removeClass("fade in top bottom left right")},i.prototype.hide=function(e){function o(){"in"!=n.hoverState&&s.detach(),n.$element&&n.$element.removeAttr("aria-describedby").trigger("hidden.bs."+n.type),e&&e()}var n=this,s=t(this.$tip),r=t.Event("hide.bs."+this.type);return this.$element.trigger(r),r.isDefaultPrevented()?void 0:(s.removeClass("in"),t.support.transition&&s.hasClass("fade")?s.one("bsTransitionEnd",o).emulateTransitionEnd(i.TRANSITION_DURATION):o(),this.hoverState=null,this)},i.prototype.fixTitle=function(){var t=this.$element;(t.attr("title")||"string"!=typeof t.attr("data-original-title"))&&t.attr("data-original-title",t.attr("title")||"").attr("title","")},i.prototype.hasContent=function(){return this.getTitle()},i.prototype.getPosition=function(e){e=e||this.$element;var i=e[0],o="BODY"==i.tagName,n=i.getBoundingClientRect();null==n.width&&(n=t.extend({},n,{width:n.right-n.left,height:n.bottom-n.top}));var s=window.SVGElement&&i instanceof window.SVGElement,r=o?{top:0,left:0}:s?null:e.offset(),a={scroll:o?document.documentElement.scrollTop||document.body.scrollTop:e.scrollTop()},l=o?{width:t(window).width(),height:t(window).height()}:null;return t.extend({},n,a,l,r)},i.prototype.getCalculatedOffset=function(t,e,i,o){return"bottom"==t?{top:e.top+e.height,left:e.left+e.width/2-i/2}:"top"==t?{top:e.top-o,left:e.left+e.width/2-i/2}:"left"==t?{top:e.top+e.height/2-o/2,left:e.left-i}:{top:e.top+e.height/2-o/2,left:e.left+e.width}},i.prototype.getViewportAdjustedDelta=function(t,e,i,o){var n={top:0,left:0};if(!this.$viewport)return n;var s=this.options.viewport&&this.options.viewport.padding||0,r=this.getPosition(this.$viewport);if(/right|left/.test(t)){var a=e.top-s-r.scroll,l=e.top+s-r.scroll+o;a<r.top?n.top=r.top-a:l>r.top+r.height&&(n.top=r.top+r.height-l)}else{var p=e.left-s,h=e.left+s+i;p<r.left?n.left=r.left-p:h>r.right&&(n.left=r.left+r.width-h)}return n},i.prototype.getTitle=function(){var t,e=this.$element,i=this.options;return t=e.attr("data-original-title")||("function"==typeof i.title?i.title.call(e[0]):i.title)},i.prototype.getUID=function(t){do t+=~~(1e6*Math.random());while(document.getElementById(t));return t},i.prototype.tip=function(){if(!this.$tip&&(this.$tip=t(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},i.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},i.prototype.enable=function(){this.enabled=!0},i.prototype.disable=function(){this.enabled=!1},i.prototype.toggleEnabled=function(){this.enabled=!this.enabled},i.prototype.toggle=function(e){var i=this;e&&(i=t(e.currentTarget).data("bs."+this.type),i||(i=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,i))),e?(i.inState.click=!i.inState.click,i.isInStateTrue()?i.enter(i):i.leave(i)):i.tip().hasClass("in")?i.leave(i):i.enter(i)},i.prototype.destroy=function(){var t=this;clearTimeout(this.timeout),this.hide(function(){t.$element.off("."+t.type).removeData("bs."+t.type),t.$tip&&t.$tip.detach(),t.$tip=null,t.$arrow=null,t.$viewport=null,t.$element=null})};var o=t.fn.tooltip;t.fn.tooltip=e,t.fn.tooltip.Constructor=i,t.fn.tooltip.noConflict=function(){return t.fn.tooltip=o,this}}(jQuery);
				$('.variations [data-toggle=tooltip]').tooltip();
		}
	} else {
		$('.variations [data-toggle=tooltip]').tooltip();
	}
	function init_swatches() {
		$('.variations_form').each(function(){
			var $vform = $(this);
			var $vform_select = $vform.find('.variations td.product_value select');
			var $variations = $vform.find( '.single_variation_wrap' );
			var $use_ajax = $vform.data( 'product_variations' ) === false;
			var $product_variations = $vform.data( 'product_variations' );
			var count_variations = $vform.find('.variations td.value').length;
			
			$vform.on( 'click', '.reset_variations', function() {
				$vform.find('.kad_radio_variations .selectedValue').removeClass('selectedValue');
				$vform.find('.kad_radio_variations label' ).removeClass( 'kt_disabled ');
				$vform.find('.kad_radio_variations input[type="radio"]:checked' ).prop('checked', false);
				return false;
			} );
			$vform.on( 'reset_data', function() {
				$vform.find( '.single_variation_wrap_kad' ).find('.quantity').hide();
				$vform.find( '.single_variation .price').hide();
			} );
			$vform.on('click', '.select-option', function (e) {
				e.preventDefault();
			});
			$vform.on('change', '.variations input[type="radio"]', function (e) {
					var $this = $(this);

					//Get the wrapper select div
					var $option_wrapper = $this.closest('.kt-radio-variation-container');

					//Select the option.
					var $wc_select_box = $option_wrapper.find('select').first();

					// Decode entities
					var attr_val = $this.val();
					if ( attr_val.indexOf('"') !== -1 ) {
						var current_attr_val = "value='" + attr_val + "'";
					} else {
						var current_attr_val = 'value="' + attr_val + '"';
					}
					//$wc_select_box.trigger('focusin').children("[value='" + attr_val + "']").prop("selected", "selected").change();
					$wc_select_box.trigger('focusin');
					if ( $wc_select_box.find( 'option[' + current_attr_val + ']' ).length ) {
						$wc_select_box.trigger('focusin').val(attr_val).trigger('change');
					} else {
						$vform.find( '.variations select' ).val( '' ).change();
						$vform.find('.kad_radio_variations .selectedValue').removeClass('selectedValue');
						$vform.find('.kad_radio_variations label' ).removeClass( 'kt_disabled ');
						if ( select2Enable ) {
							$('.variations .kad-select').select2({minimumResultsForSearch: -1 });
						}
						$vform.find('.kad_radio_variations input[type="radio"]:checked' ).prop('checked', false);
						$vform.trigger( 'reset_data' );
						// add in the selection
						$vform.find('.kad_radio_variations input[type="radio"][' + current_attr_val + ']' ).prop('checked', true);
						$wc_select_box.trigger('focusin').val(attr_val).trigger('change');
					}
					$vform.find('.kad_radio_variations .selectedValue').removeClass('selectedValue');
					$vform.find('.kad_radio_variations input[type="radio"]:checked').closest('label').addClass('selectedValue');

				});
				$vform.on('woocommerce_variation_has_changed', function() {
					$('.kad-select').trigger('update');
					if ( $use_ajax ) {
						if( $(window).width() > 790 && !kt_woo_extra_isMobile.any() ) {
							if ( select2Enable ) {
								$('.kad-select').select2({minimumResultsForSearch: -1 });
							}
						}
					}
				} );
				// Disable option fields that are unavaiable for current set of attributes
				$vform.on('woocommerce_variation_has_changed', function () {
					if ( $use_ajax ) {
						return;
					}
					var kt_current_settings = {};
					var variations = [];
					$vform.find( '.variations select' ).each( function() {
						$(this).trigger('focusin');
						$(this).find( 'option.enabled' ).each( function(){
							variations.push($( this ).val());
						});
					});
					$vform.find( '.variations .kad_radio_variations' ).each( function( index, el ) {

						var current_attr_radio = $( el );
						var current_attr_name = current_attr_radio.data( 'attribute_name' );
						current_attr_radio.find( 'input' ).removeClass( 'attached' );
						current_attr_radio.find( 'input' ).removeClass( 'enabled' );
						current_attr_radio.find( 'label' ).removeClass( 'kt_disabled ');

						// Loop through variations
						var i;
						for (i = 0; i < variations.length; ++i) {
							if ( variations[i].indexOf('"') !== -1 ) {
								current_attr_radio.find("input[value='" + variations[i] + "']").addClass( 'attached ' + 'enabled');
							} else {
								current_attr_radio.find('input[value="' + variations[i] + '"]').addClass( 'attached ' + 'enabled');
							}
						}
						current_attr_radio.find( 'input:not(.attached)' ).closest('label').addClass('kt_disabled');
						});
				} );


				$variations.on('hide_variation', function() {
					$(this).css('height', 'auto');
				} );
				// Upon gaining focus
				$vform_select.on( 'select2-opening', function() {
					if ( ! $use_ajax ) {
						$vform.trigger( 'woocommerce_variation_select_focusin' );
						$vform.trigger( 'check_variations', [ $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' ), true ] );
					}
				} );
			$(function() {
		    	if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
		    		$( '.variations_form' ).each( function() {
						$( this ).find('.variations input[type="radio"]:checked').change();
					});
		    	}
		    });
		});
	}
	$(document).on( 'woosq_loaded', function() {
		init_swatches();
	} );
	$( '.bundle_form .bundle_data' ).each( function() {
		$( this ).on( 'woocommerce-product-bundle-initialized', function() {
			init_swatches();
		} );
	} );
	init_swatches();
});

