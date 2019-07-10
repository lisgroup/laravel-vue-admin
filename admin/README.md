# vue-admin
> 这是一个极简的 vue admin 管理后台。它只包含了 Element UI & axios & iconfont & permission control & lint，这些搭建后台必要的东西。

目前版本基于 `vue-cli` 进行构建

## 安装

```bash

# 切换目录
cd admin

# 安装 npm 包
npm install

# npm 下载速度慢的可以使用
npm install --registry=https://registry.npm.taobao.org

# 启动测试服务，默认 http://localhost:9090
npm run dev

# Build for production with minification
npm run build

# Build for production and view the bundle analyzer report
npm run build --report
```


# vue-admin-template





## Build Setup

```bash
# 克隆项目
git clone https://github.com/PanJiaChen/vue-admin-template.git

# 进入项目目录
cd vue-admin-template

# 安装依赖
npm install

# 建议不要直接使用 cnpm 安装以来，会有各种诡异的 bug。可以通过如下操作解决 npm 下载速度慢的问题
npm install --registry=https://registry.npm.taobao.org

# 启动服务
npm run dev
```

浏览器访问 [http://localhost:9090/admin](http://localhost:9090/admin)

## 发布

```bash
# 构建测试环境
npm run build:stage

# 构建生产环境
npm run build
```

## 其它

```bash
# 预览发布环境效果
npm run preview

# 预览发布环境效果 + 静态资源分析
npm run preview -- --report

# 代码格式检查
npm run lint

# 代码格式检查并自动修复
npm run lint -- --fix
```
