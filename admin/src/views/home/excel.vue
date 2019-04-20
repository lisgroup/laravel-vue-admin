<template>
  <div>
    <nav-bar/>
    <el-upload
      ref="upload"
      :on-preview="handlePreview"
      :on-remove="handleRemove"
      :before-upload="beforeUpload"
      :file-list="fileList"
      class="upload-demo"
      action="">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <div slot="tip" class="el-upload__tip">只能上传 Excel 文件，且不超过 500kb</div>
    </el-upload>
  </div>
</template>

<script>
import XLSX from 'xlsx'
export default {
  data() {
    return {
      fileList: []
    }
  },
  methods: {
    submitUpload() {
      this.$refs.upload.submit()
    },
    handleRemove(file, fileList) {
      console.log(file, fileList)
    },
    handlePreview(file) {
      console.log(file)
    },
    beforeUpload(file) {
      const _this = this
      // 使返回的值变成Promise对象，如果校验不通过，则reject，校验通过，则resolve
      return new Promise(function(resolve, reject) {
        // readExcel方法也使用了Promise异步转同步，此处使用then对返回值进行处理
        _this.readExcel(file).then(result => { // 此时标识校验成功，为resolve返回
          if (result) resolve(result)
        })
      })
    },
    readExcel(file) { // 解析Excel
      console.log(file)
      const _this = this
      return new Promise(function(resolve, reject) { // 返回Promise对象
        const reader = new FileReader()
        reader.onload = (e) => { // 异步执行
          try {
            // 以二进制流方式读取得到整份excel表格对象
            const data = e.target.result
            const workbook = XLSX.read(data, { type: 'binary' })
            const batteryArr = []
            console.log(workbook.SheetNames)
            for (const item in workbook.SheetNames) {
              console.log(item)
              const SheetName = workbook.SheetNames[item]
              const sheetInfos = workbook.Sheets[SheetName]
              for (const battery in sheetInfos) {
                if (battery !== '!ref') {
                  batteryArr.push(sheetInfos[battery])
                }
              }
            }
            const outdata = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]])
            console.log(outdata)
            // console.log(batteryArr)
            if (batteryArr.length > _this.upLoadNumber) {
              console.log('上传电芯数量不能超过6条')
              resolve(false)
            } else {
              resolve(true)
            }
          } catch (e) {
            reject(e.message)
          }
        }
        reader.readAsBinaryString(file)
      })
    }
  }
}
</script>

<style scoped>
  .el-input {
    width: 97%;
    margin-bottom: 3%
  }

  .input-with-select .el-input-group__prepend {
    background-color: #fff
  }
</style>
