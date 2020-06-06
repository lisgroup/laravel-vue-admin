<template>
  <div class="container">
    <input v-model="message" type="text">
    <button type="button" @click="doCopy('add me!')">Copy!</button>
    <Footer />
  </div>
</template>
<script>
import { Footer } from '../../layout/components'

export default {
  components: {
    Footer
  },
  data() {
    return {
      message: 'Copy These Text'
    }
  },
  methods: {
    dataProcessing(val) {
      this.message = this.message + ' ' + val
    },
    doCopy(val) {
      this.dataProcessing(val)
      this.$copyText(this.message).then(function(e) {
        alert('Copied')
        console.log(e)
      }, function(e) {
        alert('Can not copy')
        console.log(e)
      })
    }
  }
}
</script>
