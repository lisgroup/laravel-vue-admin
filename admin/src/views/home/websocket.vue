<template>
  <div class="test">1111
    <span style="display:block;">点击</span>
  </div>
</template>

<script>
export default {
  name: 'WebSocket',
  data() {
    return {
      websock: null
    }
  },
  created() {
    this.initWebSocket()
  },
  destroyed() {
    this.websock.close() // 离开路由之后断开 websocket 连接
  },
  methods: {
    initWebSocket() { // 初始化 weosocket
      if ('WebSocket' in window) {
        const url = 'ws://127.0.0.1:5200?id=6'
        this.websock = new WebSocket(url)
        this.websock.onmessage = this.onmessage
        this.websock.onopen = this.onopen
        this.websock.onerror = this.onerror
        this.websock.onclose = this.close
      } else {
        // 浏览器不支持 WebSocket，使用 ajax 轮询
        console.log('Your browser does not support WebSocket!')
      }
    },
    onopen() { // 连接建立之后执行send方法发送数据
      // const actions = { 'id': '7' }
      // const str = JSON.stringify(actions)
      // console.log(str)
      // // 数据发送
      // const rs = this.send(str)
      // console.log(rs)
    },
    onerror() { // 连接建立失败重连
      this.initWebSocket()
    },
    onmessage(e) { // 数据接收
      console.log(e.data)
      const redata = JSON.parse(e.data)
      console.log(redata)
    },
    send(Data) {
      this.websock.send(Data)
    },
    close(e) { // 关闭
      console.log('断开连接', e)
    }
  }
}
</script>

<style>
</style>
