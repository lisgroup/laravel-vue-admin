(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-a3c32d3e"],{"7bd2":function(e,t,n){},af99:function(e,t,n){"use strict";n("7bd2")},eb43:function(e,t,n){"use strict";n.r(t);var r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"app-container"},[n("el-form",{ref:"form",attrs:{model:e.form,rules:e.rules,"label-width":"120px"}},[n("el-form-item",{attrs:{label:"名称",prop:"name"}},[n("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"开启新老接口",prop:"default_open"}},[n("el-col",{attrs:{span:4}},[n("el-input",{model:{value:e.form.default_open,callback:function(t){e.$set(e.form,"default_open",t)},expression:"form.default_open"}})],1),e._v(" "),n("el-col",{attrs:{span:20}},[e._v(" 开启公交配置情况--【1: 老接口，2: 新接口，3: 先老接口再新接口，4: 先新接口后老接口】")])],1),e._v(" "),n("el-form-item",{attrs:{label:"是否启用"}},[n("el-switch",{model:{value:e.form.state,callback:function(t){e.$set(e.form,"state",t)},expression:"form.state"}})],1),e._v(" "),n("el-form-item",[n("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("提交")]),e._v(" "),n("el-button",{on:{click:function(t){return e.resetForm("form")}}},[e._v("重置")])],1)],1)],1)},a=[],o=(n("7f7f"),n("b775"));function i(e){return Object(o["a"])({url:"/api/config",method:"get",params:e})}function s(e){return o["a"].post("/api/config",e)}var l={data:function(){return{form:{name:"",default_open:1,state:!0,loading:!1},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"}],default_open:[{required:!0,message:"请输入开启公交配置情况",trigger:"blur"}]},redirect:"/config/index"}},created:function(){this.init()},methods:{init:function(){var e=this;i({perPage:20}).then((function(t){var n=t.data;e.form.name=n.name,e.form.default_open=n.default_open}))},onSubmit:function(e){var t=this;this.$refs[e].validate((function(e){if(!e)return!1;t.loading=!0,s(t.form).then((function(e){t.loading=!1,200===e.code?t.$message({message:"操作成功",type:"success"}):t.$message.error(e.reason)}))}))},onCancel:function(){this.$message({message:"cancel!",type:"warning"})},resetForm:function(e){this.$refs[e].resetFields()}}},f=l,u=(n("af99"),n("2877")),c=Object(u["a"])(f,r,a,!1,null,"246b69e0",null);t["default"]=c.exports}}]);