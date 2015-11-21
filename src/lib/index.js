/**
 * Created by liuhuizhi on 15/11/19.
 * email:847858699@qq.com
 */

require.config({
    baseUrl: 'lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper.3.2.0.jquery.min.js',
        fastclick: 'tools/fastclick'
    },
    shim: {
        zepto: {
            exports: '$'
        },
    }
})


require(['fastclick','zepto'],function(fc,$){
    console.log($,fc);
})
