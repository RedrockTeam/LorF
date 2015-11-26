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

        var from = {},
            end = {};
        from.lost = from.found = 0;

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




        var getUrl = function(){
            var key = $('select').val();
            goUrl(key);
        }

        getUrl();

        $('select').on('click',getUrl);

        function goUrl(key){

            if($('#url').attr('href').indexOf('key') == -1){
                $('#url').attr('href', $('#url').attr('href')+'/key/'+key);
                return;
            }

            var url = $('#url').attr('href');
            url = url.substr(0,url.length-1)+key;
            //url = url+key;
            $('#url').attr('href', url);
        }

        $('.button').on('click',function(){
            var kind = undefined,
                self = this,
                interval = 4;

            $(this).hide();
            $("#loading").show();


            kind = $(this).attr('data-id');
            console.log(kind);
            $.ajax({
                url:'http://hongyan.cqupt.edu.cn/LorF/index.php/Home/Index/nextPage',
                type:'GET',
                data:{
                    key: 'redrockswzllhzwjp',
                    from: kind == 1 ? from.lost :from.found,
                    num: interval,
                    Lorf: kind
                },
                dataType:'json',
                success:function(res){
                    if(res.status == 0){
                        return false;
                    }
                    $(self).show();
                    $("#loading").hide();
                    var nextPage = res;
                    var template =  kind == 0 ? $('#template').html():$('#template-1').html();
                    Mustache.parse(template);
                    var template_wrapper = kind == 0 ? $("#template-wrapper"):$("#template-wrapper-1");
                    var rendered = Mustache.render(template,nextPage);
                    template_wrapper.append(rendered);
                    kind == 0? from.found+=interval : from.lost+=interval;
                    console.log(from.found)
                }
            })
        })

    })
})
