<template>
  <footer class="footer">
    <a href="http://www.beian.miit.gov.cn/" target="_blank">苏ICP备19019285号-1</a>
    <a href="https://www.guke1.com/#/">© guke1.com</a>
    <span class="copy-right">版权所有</span>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
  data() {
    return {
      activeIndex: ''
    }
  }
}
</script>

<style scoped>
  .footer {
    background-color: #9caebf;
    color: #fff;
    text-align: center;
    margin-top: 30px;
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 50px;
    z-index: 9999;
  }
  .footer > a {
    color: white;
    margin: 0 10px 0 0;
    text-align: center;
    display: inline-block;
  }
  a:hover {
    color: #6289ad;
  }

  .grid-content a{
    display: block;
  }
  ul > li > a{
    display: block;
  }
</style>
