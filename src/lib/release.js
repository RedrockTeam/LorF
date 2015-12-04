require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick',
        mustache: 'tools/mustache.min',
        jquery: 'tools/jquery.min',
        DateTimePicker:'tools/DateTimePicker',
        jweixin:'tools/jweixin'
    },
    shim: {
        zepto: {
            exports: '$'
        },
    }
})


require(['fastclick','zepto','jweixin'],function(FastClick,$,wx){
    $(document).ready(function(){
        FastClick.attach(document.body);

        $('#date').on('click',function(){
            show();
        })



        //jwexin share

        var title = '失物招领';
        var desc = '【失物招领】你的失联物品在这里，小帮手·青协为你提供服务～';
        var link = "{$ticket.url}";
        var imgUrl = "http://hongyan.cqupt.edu.cn/MagicLoop/Addons/Book/View/default/Public/images/share.jpg";

        wx.config({
            debug: false,
            appId: "{$appid}",
            timestamp: "{$ticket.time}",
            nonceStr: "{$ticket.nonceStr}",
            signature: "{$ticket.signature}",
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'hideAllNonBaseMenuItem'
            ]
        });
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                link: link,
                imgUrl: imgUrl,
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: link,
                imgUrl: imgUrl, // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareQQ({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: link,
                imgUrl: imgUrl, // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });

//share 结束

//日期选择开始

        var date = new Date();
        year = date.getFullYear();
        month = date.getMonth() + 1;
        day = date.getDay() - 1 ;

        $('.year').get(0).innerHTML = year;
        $('.month').get(0).innerHTML = month;
        $('.day').get(0).innerHTML = day;


        var getTime = function(){
            var select = [];
            select.push($('.year').get(0).innerHTML);
            select.push($('.month').get(0).innerHTML);
            select.push($('.day').get(0).innerHTML);
            console.log(select.toString());
            $('.datePicked').get(0).innerHTML =  select.join('-');
        }

        count = 0;
        $('.time-comp3').on('click','a',function(){
            var self = $(this);

            if($(this).get(0).id.indexOf('Add') != -1){
                var kindAdd = self.get(0).id.substring(0,1);
                count++;
                switch(kindAdd){
                    case 'y':
                        self.siblings('div').get(0).innerHTML++;
                        getTime();
                    case 'm':
                        if(self.siblings('div').get(0).innerHTML == 12) return;
                        self.siblings('div').get(0).innerHTML+1;
                        getTime();
                    case 'd':
                        if(self.siblings('div').get(0).innerHTML>30) return;
                        self.siblings('div').get(0).innerHTML++;
                        getTime();

                }
            }else{
                var kindSub = self.get(0).id.substring(0,1);
                switch (kindSub){
                    case 'y':
                        self.siblings('div').get(0).innerHTML-1;
                        getTime();
                    case 'm':
                        if(self.siblings('div').get(0).innerHTML == 1) return;
                        self.siblings('div').get(0).innerHTML-1;
                        getTime()
                    case 'd':
                        if(self.siblings('div').get(0).innerHTML == 1) return;
                        self.siblings('div').get(0).innerHTML--;
                        getTime();
                }
            }

        })


        $('.close').eq(0).on('click',function(){
            hide();
        })
        $('.cancel').eq(0).on('click',function(){
            hide();
        })

        function hide(){
            $('.datePicker').hide();
            $('.datePicker-wrapper').hide();
        }


        function show(){
            $('.datePicker').show();
            $('.datePicker-wrapper').show();
        }

        var rule = [1,3,5,7,8,10,12];

        $('.ensure').eq(0).on('click',function(){
            var selected = $('.datePicked').get(0).innerHTML;
            var arr = selected.split('-');
            if(arr[1] == 2 && arr[2]>27){
                alert('哪有这一天嘛');
                return;
            }else if(rule.indexOf(arr[1])==-1 && arr[2]==31){
                alert('哪有这一天嘛');
                return;
            }else{
                $('#date').val(arr.join('-'));
                hide();
            }
        })





//结束





        var release = true;
        var oauth2Url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx81a4a4b77ec98ff4&redirect_uri=http%3A%2F%2Fhongyan.cqupt.edu.cn%2FLorF%2Findex.php%2FHome%2FIndex%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";



        $("#back").on('click',function(){
            history.back();
        })


        $('#release').on('click',function(){
            var data = {};

            if($("#kind").val() == '' || $("#species").val() == '' || $('textarea').val() == '' || $('input[type=place]').val() =='' || $('＃date').val() == ''){
                alert('请检查 不能为空');
                return;
            }



            data.kind = $("#kind").val();
            data.species = $('#species').val();
            data.content = $('textarea').val();
            data.date = $('#date').val();
            data.place = $('input[name=place]').val();
            data.phone = $('input[name=phone]').val();
            data.qq = $('input[name=QQ]').val();


            console.log(data);
            if(release){
                $.ajax({
                    type:'POST',
                    url:'handleInfo',
                    data:data,
                    success:function(res){
                        console.log(res);
                        release = false;
                        res.status === 0 ?alert('修改失败'):location.href = oauth2Url;
                    }

                })
            }else{
                alert('请勿提交两次');
                return;
            }

        })


    })
})
