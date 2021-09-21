!function(e){"use strict";e.fn.serializeJSON=function(n){var r,t,i,s,u,a,o;return a=e.serializeJSON,o=a.setupOpts(n),t=this.serializeArray(),a.readCheckboxUncheckedValues(t,this,o),r={},e.each(t,(function(e,n){i=a.splitInputNameIntoKeysArray(n.name,o),"skip"!==(s=i.pop())&&(u=a.parseValue(n.value,s,o),o.parseWithFunction&&"_"===s&&(u=o.parseWithFunction(u,n.name)),a.deepSet(r,i,u,o))})),r},e.serializeJSON={defaultOptions:{checkboxUncheckedValue:void 0,parseNumbers:!1,parseBooleans:!1,parseNulls:!1,parseAll:!1,parseWithFunction:null,customTypes:{},defaultTypes:{string:function(e){return String(e)},number:function(e){return Number(e)},boolean:function(e){return-1===["false","null","undefined","","0"].indexOf(e)},null:function(e){return-1!==["false","null","undefined","","0"].indexOf(e)?null:e},array:function(e){return JSON.parse(e)},object:function(e){return JSON.parse(e)},auto:function(n){return e.serializeJSON.parseValue(n,null,{parseNumbers:!0,parseBooleans:!0,parseNulls:!0})}},useIntKeysAsArrayIndex:!1},setupOpts:function(n){var r,t,i,s,u,a;for(r in a=e.serializeJSON,null!=n||(n={}),i=a.defaultOptions||{},t=["checkboxUncheckedValue","parseNumbers","parseBooleans","parseNulls","parseAll","parseWithFunction","customTypes","defaultTypes","useIntKeysAsArrayIndex"],n)if(-1===t.indexOf(r))throw new Error("serializeJSON ERROR: invalid option '"+r+"'. Please use one of "+t.join(", "));return u=(s=function(e){return!1!==n[e]&&""!==n[e]&&(n[e]||i[e])})("parseAll"),{checkboxUncheckedValue:s("checkboxUncheckedValue"),parseNumbers:u||s("parseNumbers"),parseBooleans:u||s("parseBooleans"),parseNulls:u||s("parseNulls"),parseWithFunction:s("parseWithFunction"),typeFunctions:e.extend({},s("defaultTypes"),s("customTypes")),useIntKeysAsArrayIndex:s("useIntKeysAsArrayIndex")}},parseValue:function(n,r,t){var i,s;return s=e.serializeJSON,(i=t.typeFunctions&&t.typeFunctions[r])?i(n):t.parseNumbers&&s.isNumeric(n)?Number(n):!t.parseBooleans||"true"!==n&&"false"!==n?t.parseNulls&&"null"==n?null:n:"true"===n},isObject:function(e){return e===Object(e)},isUndefined:function(e){return void 0===e},isValidArrayIndex:function(e){return/^[0-9]+$/.test(String(e))},isNumeric:function(e){return e-parseFloat(e)>=0},optionKeys:function(e){if(Object.keys)return Object.keys(e);var n=[];for(var r in e)n.push(r);return n},splitInputNameIntoKeysArray:function(n,r){var t,i,s,u,a;return i=(u=(a=e.serializeJSON).extractTypeFromInputName(n,r))[0],s=u[1],t=i.split("["),""===(t=e.map(t,(function(e){return e.replace(/]/g,"")})))[0]&&t.shift(),t.push(s),t},extractTypeFromInputName:function(n,r){var t,i,s;if(t=n.match(/(.*):([^:]+)$/)){if((i=(s=e.serializeJSON).optionKeys(r?r.typeFunctions:s.defaultOptions.defaultTypes)).push("skip"),-1!==i.indexOf(t[2]))return[t[1],t[2]];throw new Error("serializeJSON ERROR: Invalid type "+t[2]+" found in input name '"+n+"', please use one of "+i.join(", "))}return[n,"_"]},deepSet:function(n,r,t,i){var s,u,a,o,c,l;if(null==i&&(i={}),(l=e.serializeJSON).isUndefined(n))throw new Error("ArgumentError: param 'o' expected to be an object or array, found undefined");if(!r||0===r.length)throw new Error("ArgumentError: param 'keys' expected to be an array with least one element");s=r[0],1===r.length?""===s?n.push(t):n[s]=t:(u=r[1],""===s&&(c=n[o=n.length-1],s=l.isObject(c)&&(l.isUndefined(c[u])||r.length>2)?o:o+1),""===u||i.useIntKeysAsArrayIndex&&l.isValidArrayIndex(u)?!l.isUndefined(n[s])&&e.isArray(n[s])||(n[s]=[]):!l.isUndefined(n[s])&&l.isObject(n[s])||(n[s]={}),a=r.slice(1),l.deepSet(n[s],a,t,i))},readCheckboxUncheckedValues:function(n,r,t){var i,s,u,a,o;null==t&&(t={}),o=e.serializeJSON,i="input[type=checkbox][name]:not(:checked):not([disabled])",(s=r.find(i).add(r.filter(i))).each((function(r,i){u=e(i),(a=u.attr("data-unchecked-value"))?n.push({name:i.name,value:a}):o.isUndefined(t.checkboxUncheckedValue)||n.push({name:i.name,value:t.checkboxUncheckedValue})}))}}}(window.jQuery||window.$),function(e){"use strict";redux.customizer=redux.customizer||{},e(document).ready((function(){redux.customizer.init()})),redux.customizer.init=function(){e("body").addClass(redux_customizer.body_class),e(".accordion-section.redux-section, .accordion-section.redux-panel, .accordion-section-title").click((function(){e.redux.initFields()})),redux.args.disable_save_warn=!0;var n=redux_change;redux_change=function(r){r=e(r),n.apply(this,arguments),redux.customizer.save(r)};var r=e.redux.initFields;e.redux.initFiles=function(){r()}},redux.customizer.save=function(e){var n=e.hasClass("redux-field")?e:e.parents(".redux-field-container:first");redux.customizer.inputSave(n)},redux.customizer.inputSave=function(n){var r;if(n.hasClass("redux-field-container")||(n=n.parents('[class^="redux-field-container"]')),!(r=n.parent().find(".redux-customizer-input").data("id")))var r=(n=n.parents(".redux-container-repeater:first")).parent().find(".redux-customizer-input").data("id");var t=n.find(":input").serializeJSON();e.each(t,(function(e,n){t=n}));var i=n.parent().find(".redux-customizer-input").data("key");t[i]&&(t=t[i]);var s=wp.customize.control(r);JSON.stringify(s.setting._value)!==JSON.stringify(t)&&(s.setting._value=null),s.setting.set(t)}}(jQuery);