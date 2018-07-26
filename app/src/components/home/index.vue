<template>
    <div>
        <el-input placeholder="线路名称，例：快线1, 55" v-model="input">
            <template slot="prepend">线路</template>
            <el-button slot="append" icon="el-icon-search" @click="goSearch">搜索</el-button>
        </el-input>
    </div>
</template>

<script>
  export default {
    name: "index",
    data() {
      return {
        input: ''
      }
    },
    methods: {
      goSearch() {
        let line = this.input;
        let url = "http://localhost/test1/get.php";
        this.$ajax.get(url).then(res => {
          console.log(res);
        }).catch(err => {
          console.log(err);
        });
      }
    }
  }
</script>

<style scoped>
    .el-input {
        width: 97%;
    }

    .input-with-select .el-input-group__prepend {
        background-color: #fff;
    }
</style>
