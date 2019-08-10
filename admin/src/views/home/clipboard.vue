<template>
  <div>
    <div slot="content" @click="copy">>点击复制</div>
    <input type="text" class="tag-read" value="" v-model="input">
  </div>
</template>

<script type="text/ecmascript-6">
import Clipboard from 'clipboard'

export default {
  name: 'Clipboard',
  data() {
    return {
      input: 'Hello'
    }
  },
  methods: {
    copy() {
      const clipboard = new Clipboard('.tag-read')
      clipboard.on('success', e => {
        console.log('复制成功') // 这里你如果引入了elementui的提示就可以用，没有就注释即可
        // 释放内存
        clipboard.destroy()
      })
      clipboard.on('error', e => {
        // 不支持复制
        console.log('该浏览器不支持自动复制')
        // 释放内存
        clipboard.destroy()
      })
    }
  }
}
</script>

<style lang="stylus" rel="stylesheet/stylus">

</style>
