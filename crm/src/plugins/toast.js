
import vue from 'vue'
// 这里就是我们刚刚创建的那个静态组件
import toastComponent from '../components/toast.vue'
// 返回一个 扩展实例构造器
const ToastConstructor = vue.extend(toastComponent)
// 定义弹出组件的函数 接收2个参数, 要显示的文本 和 显示时间
function showToast(text, duration = 10000) {
    // 实例化一个 toast.vue
    const toastDom = new ToastConstructor({
        el: document.createElement('div'),
        data() {
            return {
                text: text,
                show: true,
                showContent:true,  // 作用:在隐藏组件之前,显示隐藏动画
            }
        }
    });
    // 把 实例化的 toast.vue 添加到 body 里
    document.body.appendChild(toastDom.$el);
    // 过了 duration 时间后隐藏
    setTimeout(() => {toastDom.showContent = false} ,duration - 1250)  // 过了 duration 时间后隐藏整个组件
    setTimeout(() => {toastDom.show = false}, duration)
}
// 注册为全局组件的函数
function registryToast() {
    // 将组件注册到 vue 的 原型链里去,
    // 这样就可以在所有 vue 的实例里面使用 this.$toast()
    vue.prototype.$toast = showToast
}
export default registryToast