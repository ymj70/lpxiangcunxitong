/*
 *  公共
*/

// 公共变量
    var baseUrl = 'http://192.168.100.56:9000';

// 公共函数
    // 跳转页面
        function goHref(url) {
            loaders();
            // url = url || '/';
            url = url || './index.html';
            window.location.href = url;
        }
        function goBack(url){
            // loaders();
            url = url || '';
            if (url != '') {
                window.location.href = url;
            } else {
                window.history.go(-1);
            }
        }

    // url 获取参数
        function GetQueryString(name){
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null) return unescape(r[2]);
            return null;
        }

    // 空数据
        function getEmptyDom() {
            if ($('.empty-seat').length > 0) $('.empty-seat').remove();
            return '<div class="empty-seat">'+
                '<div class="empty-img">'+
                    '<img src="'+$('input[name=public]').val()+'/image/empty.png" alt="">'+
                '</div>'+
            '</div>';
        }

    // 画廊
        function layerPhotos(el){
            el = el || ".photoBox .img-box";
            layer.photos({
                photos: el,
                tab: function(pic, layero) {
                    // console.log(pic) //当前图片的一些信息
                }
            });
        }

    // 分页
        function showLaypage(count, limit){
            layui.laypage.render({
                elem: 'pageNum',
                count: count,
                limit: limit,
                layout: ['prev', 'page', 'next'],
                jump: function(obj, first) {
                    if(!first){
                        if(typeof getData === "function") {
                            getData(obj.curr);
                        }
                    }
                }
            });
        }

    // 重新加载layui-form模块
        function layuiform(){
            layui.form.render();
        }

    // listSize
        function listSize(){
            var psize = 10,
                tel = $('.framePage-scroll .ovy-a table');
                framePageHeight = $('.framePage-scroll')[0].clientHeight,
                FontSize = parseInt($('html').css('font-size'));
            if (tel.length > 0) {
                FontSize = tel.hasClass('grid-table') ? FontSize : 0;
                psize = ~~(framePageHeight/(tel.find('thead')[0].clientHeight+FontSize));
            }
            return psize;
        }
        // 固定表头
            function flexThead(el){
                var el = $(el),
                    framePage = el.parent('.ovy-a');
                    tds = '';
                if (el[0].clientHeight > framePage[0].clientHeight) {
                    for(var i = 0; i < el.find('thead td').length; i++) {
                        var etd = el.find('thead td').eq(i);
                        tds += '<td style="width:'+etd[0].clientWidth+'px"><span>'+etd.find('span').html()+'</span></td>';
                    }
                    tds = '<div class="table-thead"><table class="'+el[0].className+'" style="table-layout: fixed"><thead><tr>'+tds+'</tr></thead></table></div>';
                    framePage.parent().append(tds);
                } else {
                    framePage.parent().find('.table-thead').remove();
                }
            }
    // table
        // 选中一
            function checkNum(el){
                var el = el || '.ovy-a table';
                return $(el).find(':checked').length;
            }

    // 导航条
        // 显隐导航条
        $('body').on('click', '.menu .header .nav-title', function() {
            var el = $('.menu');
            var stu = 1;
            if (el.hasClass('menu-cur')) {
                el.removeClass('menu-cur');
                el.find('.nava-cur').addClass('navi-cur');
                stu = 1;
            } else {
                el.addClass('menu-cur');
                el.find('.nava-cur').removeClass('navi-cur');
                stu = 2;
            }
        });
        // 切换导航条
            // 首页
            $('body').on('click', '.menu .nav >ul >li', function() {
                var el = $(this);
                if (el.index() == 0) {
                    el.siblings().find('.nava-cur').removeClass('nava-cur');
                    el.siblings().find('.nav-cur').removeClass('nav-cur');
                    el.find('>.seat').addClass('nava-cur');
                }
            });
            // 一级菜单展开
            $('body').on('click', '.menu .nav >ul >li >.seat', function() {
                if ($(this).hasClass('nava-cur')) {
                    $(this).removeClass('nava-cur');
                } else {
                    $(this).addClass('nava-cur');
                }
            });
            // 二级菜单选择
            $('body').on('click', '.menu .nav >ul >li li', function() {
                var eli = $(this).parents('li');
                eli.siblings().find('.nava-cur').removeClass('nava-cur');
                eli.find('>.seat').addClass('nava-cur');
                $(this).addClass('nav-cur').siblings().removeClass('nav-cur');
            });
            // 提示
            $(".menu .nav li .seat").hover(function (e) {
                if (!$('.menu').hasClass('menu-cur'))
                layer.tips($(this).find('span').html(), this);
            }, function (e) {
                layer.close(layer.index);
            });