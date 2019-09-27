module.exports = {
  devServer: {
		port: 8080, // 端口号
		host: '0.0.0.0',
		https: false, // https:{type:Boolean}
		disableHostCheck: true,
		open: false, //配置自动启动浏览器
		// proxy: 'http://localhost:4000' // 配置跨域处理,只有一个代理
		proxy: {
			'/tencent': {
					target: 'https://pacaio.match.qq.com',   // 需要请求的地址
					changOrigin: true,  // 是否跨域
					pathRewrite: {
							'^/tencent': '/'  // 替换target中的请求地址，也就是说，在请求的时候，url用'/proxy'代替'http://ip.taobao.com'
					}
			},
			'/api': {
				target: 'https://www.lzjrys.store/',   // 需要请求的地址
				changOrigin: true,  // 是否跨域
				pathRewrite: {
						'^/api': '/'  // 替换target中的请求地址，也就是说，在请求的时候，url用'/proxy'代替'http://ip.taobao.com'
				}
			}
		}  // 配置多个代理
	}
}