<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="栏目" prop="category_id">
        <el-select v-model="form.category_id" placeholder="请选择栏目" value-key="name">
          <el-option v-for="(cate, index) in category" :key="index" :label="cate.name" :value="cate.id">
            <span style="float: left; color: #8492a6; font-size: 13px">{{ cate.name }}</span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="文章标题" prop="title">
        <el-input v-model="form.title" />
      </el-form-item>
      <el-form-item label="作者" prop="author">
        <el-input v-model="form.author" />
      </el-form-item>
      <el-form-item label="关键词" prop="keywords">
        <el-input v-model="form.keywords" />
      </el-form-item>
      <el-form-item label="标签" prop="tag_ids">
        <el-input v-model="form.tag_ids" />
      </el-form-item>
      <el-form-item label="内容" prop="markdown">
        <mavon-editor ref="md" v-model="form.markdown" @imgAdd="imgAdd" @imgDel="imgDel" />
      </el-form-item>
      <el-form-item label="是否置顶">
        <el-radio-group v-model="form.is_top">
          <el-radio :label="1">是</el-radio>
          <el-radio :label="0">否</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
// import with ES6
// import mavonEditor from 'mavon-editor'
import { mavonEditor } from 'mavon-editor'
// markdown-it对象：md.s_markdown, md => mavonEditor 实例
import 'mavon-editor/dist/css/index.css'
import { getToken } from '@/utils/auth'

import { axios, edit, postEdit } from '@/api/article'
import { getList } from '@/api/category'

export default {
  components: {
    mavonEditor
  },
  data() {
    return {
      item: '',
      category: [],
      form: {
        title: '',
        category_id: '',
        author: 'admin',
        keywords: '',
        tag_ids: '',
        markdown: '',
        content: '0',
        is_top: 0,
        loading: false
      },
      rules: {
        title: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ],
        category_id: [
          { required: true, message: '请选择栏目', trigger: 'blur' }
        ],
        author: [
          { required: true, message: '请输入作者', trigger: 'blur' }
        ],
        keywords: [
          { required: true, message: '请输入关键词', trigger: 'blur' }
        ],
        tag_ids: [
          { required: true, message: '请选择一个标签', trigger: 'blur' }
        ],
        markdown: [
          { required: true, message: '请输入内容', trigger: 'blur' }
        ]
      },
      redirect: '/article/index'
    }
  },
  watch: {
    item(value) {
      this.form.category_id = value
      this.getItem()
    }
  },
  created() {
    this.init()

    this.id = this.$route.params.id
    this.getData(this.id)
  },
  methods: {
    getItem() {
      this.$emit('getItem', this.form.category)
    },
    init() {
      getList({ perPage: 20 }).then(response => {
        this.category = response.data.data
      })
    },
    getData(id) {
      // this.id = this.$route.params.id
      edit(id).then(response => {
        // console.log(response)
        this.loading = false
        if (response.code === 200) {
          this.form = response.data
          this.form.item = response.data.category_id
          this.form.category_id = response.data.category_id
          this.getItem()
          // this.form.is_task = (response.data.is_task === 1)
        } else {
          this.$message.error(response.reason)
        }
      })
    },
    onSubmit(form) {
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          postEdit(this.id, this.form).then(response => {
            // console.log(response)
            this.loading = false
            if (response.code === 200) {
              this.$message({
                message: '操作成功',
                type: 'success'
              })
              this.$router.push({ path: this.redirect || '/' })
            } else {
              this.$message.error(response.reason)
            }
          })
        } else {
          // this.$message('error submit!')
          // console.log('error submit!!')
          return false
        }
      })
    },
    onCancel() {
      this.$message({
        message: 'cancel!',
        type: 'warning'
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
    // 绑定@imgAdd event
    imgAdd(pos, $file) {
      // 第一步.将图片上传到服务器.
      var formdata = new FormData()
      formdata.append('file', $file)
      axios({
        url: process.env.VUE_APP_BASE_API + '/api/upload?token=' + getToken(),
        method: 'post',
        data: formdata,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((url) => {
        // 第二步.将返回的url替换到文本原位置![...](./0) -> ![...](url)
        /**
         * $vm 指为 mavonEditor 实例，可以通过如下两种方式获取
         * 1. 通过引入对象获取: `import {mavonEditor} from ...` 等方式引入后，`$vm`为`mavonEditor`
         * 2. 通过$refs获取: html声明ref : `<mavon-editor ref=md ></mavon-editor>，`$vm`为 `this.$refs.md`
         */
        // console.log(pos)
        // console.log(url)
        this.$refs.md.$img2Url(pos, url.data)
      })
    },
    imgDel(pos) {
      // delete this.img_file[pos]
      // console.log(pos[0])
      // console.log(pos[0].name)
      // console.log(pos[1])
      // let rs = this.$refs.md.$refs.toolbar_left.$imgDelByFilename(pos[0].name)
      // console.log(rs)
      // let rs = this.$refs.md.$refs.toolbar_left.$imgDelByFilename('aa18972bd40735faee21b63393510fb30e240862.jpg')
      // console.log(rs)
    }
  }
}
</script>

<style scoped>
  .line {
    text-align: center;
  }
</style>

