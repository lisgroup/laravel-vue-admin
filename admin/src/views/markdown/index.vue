<template>
  <div id="main">
    <nav-bar />
    <mavon-editor ref="md" v-model="value" @imgAdd="imgAdd" @imgDel="imgDel" />
  </div>
</template>

<script>
// import with ES6
// import mavonEditor from 'mavon-editor'
import { mavonEditor } from 'mavon-editor'
// markdown-it对象：md.s_markdown, md => mavonEditor 实例
//                 or
//                 mavonEditor.markdownIt
import 'mavon-editor/dist/css/index.css'
// use
// Vue.use(mavonEditor)
// import axios from 'axios'

export default {
  components: {
    mavonEditor
  },
  data() {
    return {
      'value': ''
    }
  },
  methods: {
    // 绑定@imgAdd event
    imgAdd(pos, $file) {
      // 图片不上传服务器
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
