/**
 * Created by liuhuizhi on 15/11/19.
 * email:847858699@qq.com
 */

require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick'
    },
    shim: {
        zepto: {
            exports: '$'
        },
    }
})


require(['fastclick','zepto','swiper'],function(fc,$,swiper){
    $(document).ready(function(){
        var mySwiper = new Swiper('.swiper-container',{
            speed: 500,
            onSlideChangeStart: function(){
                $("nav .active").removeClass('active');
                $("nav div").eq(mySwiper.activeIndex).addClass('active');
            }
        });
        $("nav div").on('touchstart mousedown',function(e){
            e.preventDefault()
            $("nav div").removeClass('active');
            $(this).addClass('active');
            mySwiper.swipeTo( $(this).index() );
        }).click(function(e){
            e.preventDefault();
        });
    })
})
