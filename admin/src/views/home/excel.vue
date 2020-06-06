<template>
  <div>
    <nav-bar />
    <el-upload
      ref="upload"
      :data="dataObj"
      :action="uploadUrl"
      :on-preview="handlePreview"
      :on-remove="handleRemove"
      :before-upload="beforeUpload"
      :file-list="fileList"
      class="upload-demo"
    >
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <div slot="tip" class="el-upload__tip">只能上传 Excel 文件，且不超过 500kb</div>
    </el-upload>

    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>数据预览</span>
      </div>
      <div class="text item">
        <el-table :data="tableData" border highlight-current-row style="width: 100%;">
          <el-table-column :label="tableTitle">
            <el-table-column v-for="item in tableHeader" :key="item" :prop="item" :label="item" min-width="150" />
          </el-table-column>
        </el-table>
      </div>
    </el-card>
    <Footer />
  </div>
</template>

<script>
import XLSX from 'xlsx'
import request from '../../utils/request'
import { Footer } from '../../layout/components'

export default {
  name: 'Excel',
  components: {
    Footer
  },
  data() {
    return {
      fileList: [],
      upLoadNumber: 100000,
      tableTitle: '',
      tableData: [],
      tableHeader: '',
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
            const tableTitle = workbook.SheetNames[0]
            console.log(tableTitle)
            const worksheet = workbook.Sheets[workbook.SheetNames[0]]
            const header = _this.get_header_row(worksheet)
            console.log(header)
            const results = XLSX.utils.sheet_to_json(worksheet)
            console.log(results)
            _this.generateDate({ tableTitle, header, results })
            // console.log(batteryArr)
            if (batteryArr.length > _this.upLoadNumber) {
              console.log('不能超过')
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
    },
    generateDate({ tableTitle, header, results }) {
      console.log(tableTitle)
      console.log(header)
      console.log(results)
      this.tableTitle = tableTitle
      this.tableData = results
      this.tableHeader = header
    },
    handleDrop(e) {
      e.stopPropagation()
      e.preventDefault()
      const files = e.dataTransfer.files
      if (files.length !== 1) {
        this.$message.error('Only support uploading one file!')
        return
      }
      const itemFile = files[0] // only use files[0]
      this.readerData(itemFile)
      e.stopPropagation()
      e.preventDefault()
    },
    handleDragover(e) {
      e.stopPropagation()
      e.preventDefault()
      e.dataTransfer.dropEffect = 'copy'
    },
    readerData(itemFile) {
      if (itemFile.name.split('.')[1] !== 'xls' && itemFile.name.split('.')[1] !== 'xlsx') {
        this.$message({ message: '上传文件格式错误，请上传xls、xlsx文件！', type: 'warning' })
      } else {
        const reader = new FileReader()
        reader.onload = e => {
          const data = e.target.result
          const fixedData = this.fixdata(data)
          // const fixedData = data
          const workbook = XLSX.read(btoa(fixedData), { type: 'base64' })
          const firstSheetName = workbook.SheetNames[0] // 第一张表 sheet1
          console.log(firstSheetName)
          const worksheet = workbook.Sheets[firstSheetName] // 读取sheet1表中的数据 delete worksheet['!merges']
          const A_l = worksheet['!ref'].split(':')[1] // 当excel存在标题行时
          worksheet['!ref'] = `A2:${A_l}`
          const tableTitle = firstSheetName
          const header = this.get_header_row(worksheet)
          console.log(header)
          const results = XLSX.utils.sheet_to_json(worksheet)
          console.log(results)
          this.generateDate({ tableTitle, header, results })
        }
        reader.readAsArrayBuffer(itemFile)
      }
    },
    fixdata(data) {
      let o = ''
      let l = 0
      const w = 10240
      for (l = 0; l < data.byteLength / w; ++l) {
        o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w, l * w + w)))
        o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w)))
        return o
      }
    },
    get_header_row(sheet) {
      const headers = []
      const range = XLSX.utils.decode_range(sheet['!ref'])
      let C
      const R = range.s.r /* start in the first row */
      for (C = range.s.c; C <= range.e.c; ++C) { /* walk every column in the range */
        var cell = sheet[XLSX.utils.encode_cell({ c: C, r: R })] /* find the cell in the first row */
        var hdr = 'UNKNOWN ' + C // <-- replace with your desired defaultif (cell && cell.t)
        hdr = XLSX.utils.format_cell(cell)
        headers.push(hdr)
      }
      return headers
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
