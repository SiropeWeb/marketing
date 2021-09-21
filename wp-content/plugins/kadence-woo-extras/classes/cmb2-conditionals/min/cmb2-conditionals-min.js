/**
 * Conditional logic for CMB2 library
 * @author    Awran5 <github.com/awran5>
 * @version   1.0.0
 * @license   under GPL v2.0 (https://github.com/awran5/CMB2-conditional-logic/blob/master/LICENSE)
 * @copyright © 2018 Awran5. All rights reserved.
 * 
 */
!function(e){"use strict";function t(){e("[data-conditional-id]").each((t,a)=>{function c(e){return o.includes(e)&&""!==e}let n=a.dataset.conditionalId,o=a.dataset.conditionalValue,i=a.closest(".cmb-row"),d;if(i.classList.contains("cmb-repeat-group-field")){let e,t;n=`${i.closest(".cmb-repeatable-group").getAttribute("data-groupid")}[${i.closest(".cmb-repeatable-grouping").getAttribute("data-iterator")}][${n}]`}e('[name="'+n+'"]').each(function(t,a){"select-one"===a.type?(!c(a.value)&&e(i).hide(),e(a).on("change",function(t){c(t.target.value)?e(i).show():e(i).hide()})):"radio"===a.type?(!c(a.value)&&a.checked&&e(i).hide(),e(a).on("change",function(t){c(t.target.value)?e(i).show():e(i).hide()})):"checkbox"===a.type&&(!a.checked&&e(i).hide(),e(a).on("change",function(t){t.target.checked?e(i).show():e(i).hide()}))})})}t(),e(".cmb2-wrap > .cmb2-metabox").on("cmb2_add_row",function(){t()})}(jQuery);