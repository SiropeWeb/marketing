/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
!function(e){"function"==typeof define&&define.amd?jQueryCookie.define(["jquery"],e):e(jQuery)}((function(e){function n(e){return e}function o(e){return decodeURIComponent(e.replace(r," "))}function i(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return t.json?JSON.parse(e):e}catch(e){}}var r=/\+/g,t=e.cookie=function(r,c,a){if(void 0!==c){if("number"==typeof(a=e.extend({},t.defaults,a)).expires){var u=a.expires,f=a.expires=new Date;f.setDate(f.getDate()+u)}return c=t.json?JSON.stringify(c):String(c),document.cookie=[t.raw?r:encodeURIComponent(r),"=",t.raw?c:encodeURIComponent(c),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+a.domain:"",a.secure?"; secure":""].join("")}for(var d=t.raw?n:o,p=document.cookie.split("; "),s=r?void 0:{},m=0,x=p.length;m<x;m++){var l=p[m].split("="),g=d(l.shift()),k=d(l.join("="));if(r&&r===g){s=i(k);break}r||(s[g]=i(k))}return s};t.defaults={},e.removeCookie=function(n,o){return void 0!==e.cookie(n)&&(e.cookie(n,"",e.extend({},o,{expires:-1})),!0)}}));