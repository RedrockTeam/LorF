/**
 * Created by liuhuizhi on 15/11/19.
 * email:847858699@qq.com
 */

require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick',
        mustache: 'tools/mustache.min'
    },
    shim: {
        zepto: {
            exports: '$'
        },
    }
})


require(['fastclick','zepto','swiper','mustache'],function(FastClick,$,swiper,Mustache){
    $(document).ready(function(){

        //console.log(Mustache.render);
        //绑定FastClick
        FastClick.attach(document.body);


        //实例化mySwiper
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

        $('.button').on('click',function(){
            var from = {},
                end = {},
                kind = undefined,
                interval = 4;
            from.lost = from.found = 0;

            kind = $(this).attr('data-id');
            //console.log(kind);
            $.ajax({
                url:'http://hongyan.cqupt.edu.cn/LorF/index.php/Home/Index/nextPage',
                type:'GET',
                data:{
                    key: 'redrockswzllhzwjp',
                    from: kind == 0 ? from.lost :from.found,
                    num: interval,
                    Lorf: kind
                },
                success:function(res){
                    console.log(res);
                }
            })
        })


        var template = $('#template').html();
        Mustache.parse(template);
        var rendered = Mustache.render(template,{});
        $('#target').html(rendered);


    })
})
