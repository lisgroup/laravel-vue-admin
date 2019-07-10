import Vue from 'vue'
import SvgIcon from '@/components/SvgIcon'// svg component
import NavBar from '@/components/common/navBar'

// register globally
Vue.component('svg-icon', SvgIcon)
Vue.component('nav-bar', NavBar)

const req = require.context('./svg', false, /\.svg$/)
const requireAll = requireContext => requireContext.keys().map(requireContext)
requireAll(req)
