(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-04d84290"],{4920:function(t,e,a){"use strict";a("b16d")},6613:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-row",[a("el-button",{attrs:{type:"primary",size:"medium"}},[a("router-link",{attrs:{to:"/tag/add"}},[t._v("新增标签")])],1)],1),t._v(" "),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",border:"",fit:"","highlight-current-row":""}},[a("el-table-column",{attrs:{align:"center",label:"ID",width:"70"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n        "+t._s(e.row.id)+"\n      ")]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"标签名称"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n        "+t._s(e.row.name)+"\n      ")]}}])}),t._v(" "),a("el-table-column",{attrs:{align:"center",prop:"created_at",label:"创建时间",width:""},scopedSlots:t._u([{key:"default",fn:function(e){return[a("i",{staticClass:"el-icon-time"}),t._v(" "),a("span",[t._v(t._s(e.row.created_at))])]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{size:"mini"},on:{click:function(a){return t.handleEdit(e.$index,e.row)}}},[t._v("编辑")]),t._v(" "),a("el-button",{attrs:{size:"mini",type:"danger"},on:{click:function(a){return t.handleDelete(e.$index,e.row)}}},[t._v("删除")])]}}])})],1),t._v(" "),a("div",{staticClass:"pagination"},[a("el-pagination",{attrs:{total:t.total,"current-page":t.currentpage,"page-sizes":[10,20,30,50,100],"page-size":t.perpage,layout:"total, sizes, prev, pager, next, jumper"},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)},r=[],i=a("d28d"),s={filters:{statusFilter:function(t){var e={1:"success",0:"gray","-1":"danger"};return e[t]}},data:function(){return{list:null,listLoading:!0,perpage:10,total:100,currentpage:1,listQuery:{page:1}}},created:function(){this.listQuery=this.$route.query,this.currentpage=parseInt(this.listQuery.page);var t=parseInt(this.$route.query.perPage);this.perpage=isNaN(t)?this.perpage:t,this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e=Object.assign({page:this.listQuery.page},{perPage:this.perpage});Object(i["c"])(e).then((function(e){t.list=e.data.data,t.listLoading=!1,t.total=e.data.total}))},handleEdit:function(t,e){this.$router.push({path:"/tag/edit/"+e.id})},handleDelete:function(t,e){var a=this;this.$confirm("此操作将永久删除该数据, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){Object(i["a"])(e.id).then((function(t){a.loading=!1,200===t.code?(a.$message({message:"操作成功",type:"success"}),a.fetchData()):a.$message.error(t.reason)})),a.$message({type:"success",message:"删除成功!"})})).catch((function(){a.$message({type:"info",message:"已取消删除"})}))},handleSizeChange:function(t){this.perpage=t,this.$router.push({path:"",query:{page:this.listQuery.page,perPage:t}}),this.fetchData()},handleCurrentChange:function(t){this.listQuery={page:t},this.$router.push({path:"",query:{page:t,perPage:this.perpage}}),this.fetchData({page:t})},goSearch:function(t){var e=this;this.$refs[t].validate((function(t){if(!t)return!1;e.listLoading=!0;var a={wd:e.form.input};Object(i["f"])(a).then((function(t){e.listLoading=!1,200===t.code?(e.form.isShow=!0,e.list=t.data.data,e.total=t.data.total):e.$message.error(t.reason)}))}))}}},u=s,o=(a("4920"),a("2877")),c=Object(o["a"])(u,n,r,!1,null,"070d7352",null);e["default"]=c.exports},b16d:function(t,e,a){},d28d:function(t,e,a){"use strict";a.d(e,"c",(function(){return r})),a.d(e,"d",(function(){return i})),a.d(e,"b",(function(){return s})),a.d(e,"e",(function(){return u})),a.d(e,"a",(function(){return o})),a.d(e,"f",(function(){return c}));var n=a("b775");function r(t){return Object(n["a"])({url:"/api/tag",method:"get",params:t})}function i(t){return n["a"].post("/api/tag",t)}function s(t){return n["a"].get("/api/tag/"+t)}function u(t,e){return n["a"].patch("/api/tag/"+t,e)}function o(t){return n["a"].delete("/api/tag/"+t)}function c(t){return Object(n["a"])({url:"/api/tag_search",method:"get",params:t})}}}]);