/**
 * Created by liuhuizhi on 15/11/21.
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


require(['zepto','swiper'],function($,swiper){
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



        //页面交互逻辑
        $('.solved').on('click',function(){
            show();
            $('.ensure').on('click',function(){
                hide()
            })

        })
        $('.cancel').on('click',function(){
           hide()
        })



        function show(){
            $('.shade').show();
            $('.banner').show();
        }


        function hide(){
            $('.shade').hide();
            $('.banner').hide()
        }
    })
})