(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-11232305"],{"0feb":function(e,t,n){"use strict";n.d(t,"c",function(){return o}),n.d(t,"d",function(){return s}),n.d(t,"e",function(){return a}),n.d(t,"b",function(){return i}),n.d(t,"f",function(){return c}),n.d(t,"a",function(){return l}),n.d(t,"g",function(){return u});var r=n("b775");function o(e){return Object(r["a"])({url:"/api/permissions",method:"get",params:e})}function s(){return r["a"].get("/api/permissions/create")}function a(e){return r["a"].post("/api/permissions",e)}function i(e){return r["a"].get("/api/permissions/"+e)}function c(e,t){return r["a"].patch("/api/permissions/"+e,t)}function l(e){return r["a"].delete("/api/permissions/"+e)}function u(e){return Object(r["a"])({url:"/api/permissions_search",method:"get",params:e})}},a0a4:function(e,t,n){"use strict";n.r(t);var r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"app-container"},[n("el-form",{ref:"form",attrs:{model:e.form,rules:e.rules,"label-width":"220px"}},[n("el-form-item",{attrs:{label:"权限名称",prop:"name"}},[n("el-col",{attrs:{span:10}},[n("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),n("el-col",{attrs:{span:14}}),e._v(" "),n("br"),e._v("示例：create-article, update-article, delete-article, show-article\n    ")],1),e._v(" "),n("el-form-item",{attrs:{label:"路由",prop:"route"}},[n("el-col",{attrs:{span:10}},[n("el-input",{model:{value:e.form.route,callback:function(t){e.$set(e.form,"route",t)},expression:"form.route"}})],1),e._v(" "),n("el-col",{attrs:{span:14}})],1),e._v(" "),n("el-form-item",{attrs:{label:"新增权限赋值角色",prop:"roles"}},[[n("el-checkbox-group",{on:{change:e.handleCheckedRolesChange},model:{value:e.form.checkedRoles,callback:function(t){e.$set(e.form,"checkedRoles",t)},expression:"form.checkedRoles"}},e._l(e.form.roles,function(t){return n("el-checkbox",{key:t.id,attrs:{label:t.id}},[e._v(e._s(t.name))])}),1)]],2),e._v(" "),n("el-form-item",[n("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.onSubmit("form")}}},[e._v("提交")]),e._v(" "),n("el-button",{on:{click:function(t){return e.resetForm("form")}}},[e._v("重置")])],1)],1)],1)},o=[],s=n("0feb"),a={data:function(){return{form:{name:"",route:"",checkedRoles:[],roles:[],isIndeterminate:!0,loading:!1},rules:{name:[{required:!0,message:"请输入名称",trigger:"blur"}]},redirect:"/permission"}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;this.listLoading=!0,Object(s["d"])().then(function(t){e.form.roles=t.data.roles,e.listLoading=!1})},handleCheckedRolesChange:function(e){var t=e.length;this.isIndeterminate=t>0&&t<this.form.roles.length},onSubmit:function(e){var t=this;this.$refs[e].validate(function(e){if(!e)return!1;t.loading=!0,Object(s["e"])(t.form).then(function(e){t.loading=!1,200===e.code?(t.$message({message:"操作成功",type:"success"}),t.$router.push({path:t.redirect||"/"})):t.$message.error(e.reason)})})},onCancel:function(){this.$message({message:"cancel!",type:"warning"})},resetForm:function(e){this.$refs[e].resetFields()}}},i=a,c=(n("e557"),n("0c7c")),l=Object(c["a"])(i,r,o,!1,null,"367ad56c",null);t["default"]=l.exports},d699:function(e,t,n){},e557:function(e,t,n){"use strict";var r=n("d699"),o=n.n(r);o.a}}]);