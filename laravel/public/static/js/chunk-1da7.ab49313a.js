(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-1da7"],{LnHU:function(e,t,r){"use strict";r.r(t);var n=r("xHYf"),a={data:function(){return{form:{name:"",url:"",sort:"1",loading:!1},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"}],keywords:[{required:!0,message:"请输入链接地址",trigger:"blur"}]},redirect:"/nav"}},created:function(){this.id=this.$route.params.id,this.getData(this.id)},methods:{getData:function(e){var t=this;Object(n.b)(e).then(function(e){t.loading=!1,200===e.code?(t.form=e.data,t.form.is_task=1===e.data.is_task):t.$message.error(e.reason)})},onSubmit:function(e){var t=this;this.$refs[e].validate(function(e){if(!e)return!1;t.loading=!0,Object(n.e)(t.id,t.form).then(function(e){t.loading=!1,200===e.code?(t.$message({message:"操作成功",type:"success"}),t.$router.push({path:t.redirect||"/"})):t.$message.error(e.reason)})})},onCancel:function(){this.$message({message:"cancel!",type:"warning"})},resetForm:function(e){this.$refs[e].resetFields()}}},o=(r("kPLr"),r("KHd+")),i=Object(o.a)(a,function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"app-container"},[r("el-form",{ref:"form",attrs:{model:e.form,rules:e.rules,"label-width":"120px"}},[r("el-form-item",{attrs:{label:"导航名称",prop:"name"}},[r("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),r("el-form-item",{attrs:{label:"链接地址",prop:"url"}},[r("el-input",{model:{value:e.form.url,callback:function(t){e.$set(e.form,"url",t)},expression:"form.url"}})],1),e._v(" "),r("el-form-item",{attrs:{label:"排序",prop:"sort"}},[r("el-input",{model:{value:e.form.sort,callback:function(t){e.$set(e.form,"sort",t)},expression:"form.sort"}})],1),e._v(" "),r("el-form-item",[r("el-button",{attrs:{type:"primary"},on:{click:function(t){e.onSubmit("form")}}},[e._v("提交")]),e._v(" "),r("el-button",{on:{click:function(t){e.resetForm("form")}}},[e._v("重置")])],1)],1)],1)},[],!1,null,"175290cc",null);i.options.__file="edit.vue";t.default=i.exports},k3DR:function(e,t,r){},kPLr:function(e,t,r){"use strict";var n=r("k3DR");r.n(n).a},xHYf:function(e,t,r){"use strict";r.d(t,"c",function(){return a}),r.d(t,"d",function(){return o}),r.d(t,"b",function(){return i}),r.d(t,"e",function(){return s}),r.d(t,"a",function(){return u}),r.d(t,"f",function(){return c});var n=r("t3Un");function a(e){return Object(n.a)({url:"/api/nav",method:"get",params:e})}function o(e){return n.a.post("/api/nav",e)}function i(e){return n.a.get("/api/nav/"+e)}function s(e,t){return n.a.patch("/api/nav/"+e,t)}function u(e){return n.a.delete("/api/nav/"+e)}function c(e){return Object(n.a)({url:"/api/nav_search",method:"get",params:e})}}}]);