import {Message, Tag, Option, OptionGroup, Input, Button} from 'element-ui'
const element = {
  install: function (Vue) {
	Vue.use(Tag)
    Vue.use(Option)
    Vue.use(OptionGroup)
    Vue.use(Input)
	Vue.use(Button)
	Vue.prototype.$message = Message
  }
}
export default element