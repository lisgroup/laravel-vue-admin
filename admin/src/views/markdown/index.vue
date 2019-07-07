<template>
  <div id="main">
    <nav-bar />
    <mavon-editor ref="md" v-model="value" @imgAdd="imgAdd" @imgDel="imgDel" />
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      'value': ''
    }
  },
  methods: {
    // 绑定@imgAdd event
    imgAdd(pos, $file) {
      // 第一步.将图片上传到服务器.
      var formdata = new FormData()
      formdata.append('image', $file)
      axios({
        url: 'http://localhost/',
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
