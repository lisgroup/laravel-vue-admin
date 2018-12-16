import Vue from 'vue'
import SvgIcon from '@/components/SvgIcon' // svg组件
import NavBar from '@/components/common/navBar'

// register globally
Vue.component('svg-icon', SvgIcon)
Vue.component('nav-bar', NavBar)

const requireAll = requireContext => requireContext.keys().map(requireContext)
const req = require.context('./svg', false, /\.svg$/)
requireAll(req)
