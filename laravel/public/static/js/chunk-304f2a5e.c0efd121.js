(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-304f2a5e"],{"1f6b":function(t,e,n){},"40c1":function(t,e,n){"use strict";(function(t){n("a481");var e=n("7618");(function(n,o){"object"===Object(e["a"])(t)&&"object"===Object(e["a"])(t.exports)?t.exports=n.document?o(n,!0):function(t){if(!t.document)throw new Error("Geetest requires a window with a document");return o(t)}:o(n)})("undefined"!==typeof window?window:void 0,(function(t,n){if("undefined"===typeof t)throw new Error("Geetest requires browser environment");var o=t.document,r=t.Math,i=o.getElementsByTagName("head")[0];function a(t){this._obj=t}function c(t){var e=this;new a(t)._each((function(t,n){e[t]=n}))}a.prototype={_each:function(t){var e=this._obj;for(var n in e)e.hasOwnProperty(n)&&t(n,e[n]);return this}},c.prototype={api_server:"api.geetest.com",protocol:"http://",type_path:"/gettype.php",fallback_config:{slide:{static_servers:["static.geetest.com","dn-staticdown.qbox.me"],type:"slide",slide:"/static/js/geetest.0.0.0.js"},fullpage:{static_servers:["static.geetest.com","dn-staticdown.qbox.me"],type:"fullpage",fullpage:"/static/js/fullpage.0.0.0.js"}},_get_fallback_config:function(){var t=this;return u(t.type)?t.fallback_config[t.type]:t.new_captcha?t.fallback_config.fullpage:t.fallback_config.slide},_extend:function(t){var e=this;new a(t)._each((function(t,n){e[t]=n}))}};var s=function(t){return"number"===typeof t},u=function(t){return"string"===typeof t},l=function(t){return"boolean"===typeof t},f=function(t){return"object"===Object(e["a"])(t)&&null!==t},d=function(t){return"function"===typeof t},p={},g={},h=function(){return parseInt(1e4*r.random())+(new Date).valueOf()},m=function(t,e){var n=o.createElement("script");n.charset="UTF-8",n.async=!0,n.onerror=function(){e(!0)};var r=!1;n.onload=n.onreadystatechange=function(){r||n.readyState&&"loaded"!==n.readyState&&"complete"!==n.readyState||(r=!0,setTimeout((function(){e(!1)}),0))},n.src=t,i.appendChild(n)},v=function(t){return t.replace(/^https?:\/\/|\/$/g,"")},w=function(t){return t=t.replace(/\/+/g,"/"),0!==t.indexOf("/")&&(t="/"+t),t},b=function(t){if(!t)return"";var e="?";return new a(t)._each((function(t,n){(u(n)||s(n)||l(n))&&(e=e+encodeURIComponent(t)+"="+encodeURIComponent(n)+"&")})),"?"===e&&(e=""),e.replace(/&$/,"")},y=function(t,e,n,o){e=v(e);var r=w(n)+b(o);return e&&(r=t+e+r),r},_=function(t,e,n,o,r){var i=function i(a){var c=y(t,e[a],n,o);m(c,(function(t){t?a>=e.length-1?r(!0):i(a+1):r(!1)}))};i(0)},k=function(e,n,o,r){if(f(o.getLib))return o._extend(o.getLib),void r(o);if(o.offline)r(o._get_fallback_config());else{var i="geetest_"+h();t[i]=function(e){"success"===e.status?r(e.data):e.status?r(o._get_fallback_config()):r(e),t[i]=void 0;try{delete t[i]}catch(n){}},_(o.protocol,e,n,{gt:o.gt,callback:i},(function(t){t&&r(o._get_fallback_config())}))}},j=function(t,e){var n={networkError:"网络错误"};if("function"!==typeof e.onError)throw new Error(n[t]);e.onError(n[t])},x=function(){return!!t.Geetest};x()&&(g.slide="loaded");var C=function(e,n){var o=new c(e);e.https?o.protocol="https://":e.protocol||(o.protocol=t.location.protocol+"//"),k([o.api_server||o.apiserver],o.type_path,o,(function(e){var r=e.type,i=function(){o._extend(e),n(new t.Geetest(o))};p[r]=p[r]||[];var a=g[r]||"init";"init"===a?(g[r]="loading",p[r].push(i),_(o.protocol,e.static_servers||e.domains,e[r]||e.path,null,(function(t){if(t)g[r]="fail",j("networkError",o);else{g[r]="loaded";for(var e=p[r],n=0,i=e.length;n<i;n+=1){var a=e[n];d(a)&&a()}p[r]=[]}}))):"loaded"===a?i():"fail"===a?j("networkError",o):"loading"===a&&p[r].push(i)}))};return t.initGeetest=C,C}))}).call(this,n("dd40")(t))},"5d58":function(t,e,n){t.exports=n("d8d6")},"67bb":function(t,e,n){t.exports=n("f921")},"735d":function(t,e,n){"use strict";n("ef08")},7618:function(t,e,n){"use strict";n.d(e,"a",(function(){return c}));var o=n("67bb"),r=n.n(o),i=n("5d58"),a=n.n(i);function c(t){return c="function"===typeof r.a&&"symbol"===typeof a.a?function(t){return typeof t}:function(t){return t&&"function"===typeof r.a&&t.constructor===r.a&&t!==r.a.prototype?"symbol":typeof t},c(t)}},"9ed6":function(t,e,n){"use strict";n.r(e);var o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"login-container"},[n("el-form",{ref:"loginForm",staticClass:"login-form",attrs:{model:t.loginForm,rules:t.loginRules,"auto-complete":"on","label-position":"left"}},[n("h3",{staticClass:"title"},[t._v("admin-login")]),t._v(" "),n("el-form-item",{attrs:{prop:"username"}},[n("span",{staticClass:"svg-container"},[n("svg-icon",{attrs:{"icon-class":"user"}})],1),t._v(" "),n("el-input",{attrs:{name:"username",type:"text","auto-complete":"on",placeholder:"username"},model:{value:t.loginForm.username,callback:function(e){t.$set(t.loginForm,"username",e)},expression:"loginForm.username"}})],1),t._v(" "),n("el-form-item",{attrs:{prop:"password"}},[n("span",{staticClass:"svg-container"},[n("svg-icon",{attrs:{"icon-class":"password"}})],1),t._v(" "),n("el-input",{attrs:{type:t.pwdType,name:"password","auto-complete":"on",placeholder:"password"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleLogin(e)}},model:{value:t.loginForm.password,callback:function(e){t.$set(t.loginForm,"password",e)},expression:"loginForm.password"}}),t._v(" "),n("span",{staticClass:"show-pwd",on:{click:t.showPwd}},[n("svg-icon",{attrs:{"icon-class":"eye"}})],1)],1),t._v(" "),n("el-form-item",[n("el-row",[n("el-col",{staticStyle:{height:"42px"},attrs:{id:"captcha",span:10}},[n("p",{staticClass:"show",attrs:{id:"wait"}}),t._v(" "),n("p",{staticClass:"hide",attrs:{id:"notice"}},[t._v("请先完成验证")])])],1)],1),t._v(" "),n("el-form-item",[n("el-button",{staticStyle:{width:"100%"},attrs:{loading:t.loading,type:"primary"},nativeOn:{click:function(e){return e.preventDefault(),t.handleLogin(e)}}},[t._v("\n        Sign in\n      ")])],1)],1)],1)},r=[],i=(n("40c1"),n("b775")),a=n("61f7"),c={name:"Login",data:function(){var t=function(t,e,n){Object(a["b"])(e)?n():n(new Error("请输入正确的用户名"))},e=function(t,e,n){e.length<5?n(new Error("密码不能小于5位")):n()};return{loginForm:{username:"",password:""},loginRules:{username:[{required:!0,trigger:"blur",validator:t}],password:[{required:!0,trigger:"blur",validator:e}]},loading:!1,pwdType:"password",redirect:void 0,gtCapValid:"",uuidData:""}},watch:{$route:{handler:function(t){this.redirect=t.query&&t.query.redirect},immediate:!0}},created:function(){this.init()},methods:{showPwd:function(){"password"===this.pwdType?this.pwdType="":this.pwdType="password"},handleLogin:function(){var t=this;if(!this.gtCapValid)return this.$message({message:"请先点击按钮验证",type:"warning"}),!1;this.$refs.loginForm.validate((function(e){if(!e)return!1;t.loading=!0;var n=t.mergeJsonObject(t.loginForm,{uuid:t.uuidData});n=t.mergeJsonObject(n,t.gtCapValid),t.$store.dispatch("user/login",n).then((function(){t.loading=!1,t.$router.push({path:t.redirect||"/"})})).catch((function(){t.init(),t.loading=!1}))}))},uuid:function(){for(var t=[],e="0123456789abcdef",n=0;n<36;n++)t[n]=e.substr(Math.floor(16*Math.random()),1);return t[14]="4",t[19]=e.substr(3&t[19]|8,1),t[8]=t[13]=t[18]=t[23]="-",t.join("")},mergeJsonObject:function(t,e){var n={};for(var o in t)n[o]=t[o];for(var r in e)n[r]=e[r];return n},init:function(){var t=this;this.uuidData=this.uuid(),this.gtCapValid="",i["a"].get("/api/user/startCaptcha?uuid="+this.uuidData).then((function(e){var n=e.data;n.challenge&&n.gt&&window.initGeetest({gt:n.gt,challenge:n.challenge,offline:!n.success,new_captcha:n.new_captcha,product:"float",width:"448px"},(function(e){document.getElementById("captcha").innerHTML="",e.appendTo("#captcha"),e.onReady((function(){})),e.onSuccess((function(){t.gtCapValid=e.getValidate()})),e.onError((function(){t.$notify.error({title:"错误",message:"网络错误，请稍后重试"})}))}))}))}}},s=c,u=(n("735d"),n("d3b9"),n("2877")),l=Object(u["a"])(s,o,r,!1,null,"b86a6eb2",null);e["default"]=l.exports},d3b9:function(t,e,n){"use strict";n("1f6b")},dd40:function(t,e){t.exports=function(t){if(!t.webpackPolyfill){var e=Object.create(t);e.children||(e.children=[]),Object.defineProperty(e,"loaded",{enumerable:!0,get:function(){return e.l}}),Object.defineProperty(e,"id",{enumerable:!0,get:function(){return e.i}}),Object.defineProperty(e,"exports",{enumerable:!0}),e.webpackPolyfill=1}return e}},ef08:function(t,e,n){}}]);