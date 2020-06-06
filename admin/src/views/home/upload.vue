<template>
  <div>
    <nav-bar />

    <el-container>
      <el-header>图片识别模块</el-header>
      <el-container>
        <el-aside width="500px">

          <div>点击下面的加号 上传图片</div>
          <el-upload
            :data="dataObj"
            :action="uploadUrl"
            :limit="100"
            :on-preview="handlePictureCardPreview"
            :before-upload="handleBeforeUpload"
            :on-success="handleSuccess"
            :on-remove="handleRemove"
            list-type="picture-card"
          >
            <i class="el-icon-plus" />
          </el-upload>
          <el-dialog :visible.sync="dialogVisible">
            <img :src="dialogImageUrl" width="100%" alt="">
          </el-dialog>

        </el-aside>
        <el-main>
          <el-input v-model="desc" type="textarea" rows="50" />
        </el-main>
      </el-container>
    </el-container>
    <Footer />
  </div>
</template>

<script>
import request from '../../utils/request'
import { Footer } from '../../layout/components'

export default {
  name: 'Upload',
  components: {
    Footer
  },
  data() {
    return {
      uploadUrl: 'https://up.qiniup.com',
      dialogImageUrl: '',
      dialogVisible: false,
      desc: '',
      dataObj: {}
    }
  },
  created() {
    // 加载页面时候需要获取本次上传七牛的 Token ，并且赋值
    const that = this
    request.get('/api/getToken').then(res => {
      // console.log(that.dataObj)
      that.dataObj = { 'token': res.data.token }
      console.log(that.dataObj)
    })
  },
  methods: {
    handleRemove(file, fileList) {
      console.log(file, fileList)
    },
    handlePictureCardPreview(file) {
      console.log(file)
      this.dialogImageUrl = file.url
      this.dialogVisible = true
    },
    handleBeforeUpload(file) {
      const name = file.name
      // 上传文件后缀
      const extName = name.substring(name.lastIndexOf('.'), name.length).toLowerCase()
      const data = new Date()
      const year = data.getFullYear()
      const month = data.getMonth() + 1
      const day = data.getDate()
      const dataOrder = year + '' + month + '' + day + '' + data.getHours() + '' + data.getMinutes()
      this.dataObj.key = dataOrder + '_' + Math.floor(Math.random() * 10000) + extName
      console.log(this.dataObj)
    },
    handleSuccess(response, file) {
      console.log(file)
      console.log(response)
      this.desc = response.data.words
    },
    handleUpload(response) {
      console.log(response)
    }
  }
}
</script>

<style scoped>
  .el-header, .el-footer {
    background-color: #B3C0D1;
    color: #333;
    text-align: center;
    line-height: 60px;
  }

  .el-aside {
    background-color: #D3DCE6;
    color: #333;
    text-align: center;
    line-height: 200px;
  }

  .el-main {
    background-color: #E9EEF3;
    color: #333;
    text-align: center;
    line-height: 160px;
  }

  body > .el-container {
    margin-bottom: 40px;
  }

  .el-container:nth-child(5) .el-aside,
  .el-container:nth-child(6) .el-aside {
    line-height: 260px;
  }

  .el-container:nth-child(7) .el-aside {
    line-height: 320px;
  }
</style>
