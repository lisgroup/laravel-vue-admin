<template>
  <div class="app-container">
    <!-- Table -->
    <el-button type="text" @click="dialogTableVisible = true">打开嵌套表格的 Dialog</el-button>

    <el-dialog :visible.sync="dialogTableVisible" title="收货地址">
      <el-table :data="gridData">
        <el-table-column property="date" label="日期" width="150"/>
        <el-table-column property="name" label="姓名" width="200"/>
        <el-table-column property="address" label="地址"/>
      </el-table>
    </el-dialog>

    <!-- Form -->
    <el-button type="text" @click="dialogFormVisible = true">打开嵌套表单的 Dialog</el-button>

    <el-dialog :visible.sync="dialogFormVisible" title="收货地址">

      <el-form ref="form" :model="form" :rules="rules" label-width="120px">
        <el-form-item label="接口" prop="api_param_id">
          <el-select v-model="item" placeholder="请选择接口" value-key="name">
            <el-option v-for="(cate, index) in apiParam" :key="index" :label="cate.name" :value="cate.id">
              <span style="float: left; color: #8492a6; font-size: 13px">{{ cate.name }}</span>
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="appkey" prop="appkey">
          <el-col :span="11">
            <el-input v-model="form.appkey"/>
          </el-col>
          <el-col :span="13"/>
        </el-form-item>
        <el-form-item label="并发请求" prop="concurrent">
          <el-col :span="2">
            <el-input v-model="form.concurrent"/>
          </el-col>
          <el-col :span="22">
            &nbsp;&nbsp; 任务执行时并发请求的数量，字段必须是数字默认： 5
          </el-col>
        </el-form-item>
        <el-form-item label="上传文件" prop="upload_url" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
          <input v-model="form.upload_url" type="hidden">
          <el-upload
            :action="uploadUrl"
            :on-preview="handlePreview"
            :on-remove="handleRemove"
            :on-success="handleSuccess"
            :before-remove="beforeRemove"
            :on-exceed="handleExceed"
            :file-list="fileList"
            multiple
            class="upload-demo">
            <el-button size="small" type="primary">点击上传</el-button>
            <div slot="tip" class="el-upload__tip">只能上传 xls/xlsx 文件，且不超过 20M</div>
          </el-upload>
        </el-form-item>
        <el-form-item label="描述内容" prop="description">
          <el-col :span="11">
            <el-input v-model="form.description" size="medium" placeholder="请输入内容" />
          </el-col>
          <el-col :span="13"/>
        </el-form-item>
        <el-form-item label="自动删除时间" prop="auto_delete">
          <el-col :span="2">
            <el-input v-model="form.auto_delete"/>
          </el-col>
          <el-col :span="22">
            &nbsp;&nbsp; 任务执行完成后自动删除的时间（单位：天），默认： 2 天
          </el-col>
          <el-col :span="13"/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">提交</el-button>
          <el-button @click="resetForm('form')">重置</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      gridData: [{
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄'
      }, {
        date: '2016-05-04',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄'
      }, {
        date: '2016-05-01',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄'
      }, {
        date: '2016-05-03',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄'
      }],
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        name: '',
        region: '',
        date1: '',
        date2: '',
        delivery: false,
        type: [],
        resource: '',
        desc: ''
      },
      formLabelWidth: '120px'
    }
  }
}
</script>

<style scoped>
  .line {
    text-align: center;
  }
</style>

