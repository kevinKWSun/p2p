layui.use(['layer', 'element'], function () {
    var $ = layui.$, element = layui.element, layer = layui.layer;
    $(window).on('resize', function () {
        var $content = $('#admin-tab-container');
        $content.height($(this).height() - 104);
        $content.find('iframe').each(function () {
            $(this).height($content.height());
        });
        //var width = $(".y-role").width();
        //$('#gridList,#gbox_gridList,#gview_gridList,#gridPager,.ui-jqgrid-hdiv,#ui-jqgrid-hbox,.layui-body').width(width - 2);
    }).resize();
    var $menu = $('#menu');
    $menu.find('li.layui-nav-item').each(function () {
        var $this = $(this);
        $this.on('click', function () {
			$menu.find('li').css('background-color','');
			$this.css('background-color','#009688');
            var id = $this.find('a').data('fid');
            $.get('/menus', {pid: id}, function (result) {
				$('ul.layui-nav-tree').html(result);
				element.render('side');
            });
        });
    });
    $('.layui-layout-left').find('a[data-fid=1]').click();
	$('body').on('click','.do-admin', function () {
		var url = $(this).data('url');
		layer.load(1);
		$('iframe').attr('src', url);
		layer.closeAll();
		return false;
	});
	$('.layui-logo i').on('click', function () {
		var rel = $(this).attr('rel');
		if(rel == 1){
			$(this).attr('rel', 2);
			$(this).html('　&#xe66b;');
			$('.layui-side').css('width','82px');
			$('.layui-side-scroll').css('width','82px');
			$('.layui-nav-tree').css('width','82px');
			$('.layui-logo').css('width','82px');
			$('.layui-logo img').attr('src','./src/css/logo.png');
			$('#menu').animate({
				left: '82px'
			});
			$('.layui-body').animate({
				left: '82px'
			});
			$('.layui-footer').animate({
				left: '82px'
			});
		}else if(rel == 2){
			$(this).attr('rel', 1);
			$(this).html('　&#xe668;');
			$('.layui-side').css('width','200px');
			$('.layui-side-scroll').css('width','200px');
			$('.layui-nav-tree').css('width','200px');
			$('.layui-logo').css('width','200px');
			$('.layui-logo img').attr('src','./src/images/OPay.png');
			$('#menu').animate({
				left: '200px'
			});
			$('.layui-body').animate({
				left: '200px'
			});
			$('.layui-footer').animate({
				left: '200px'
			});
		}
	});
	$('body').on('mouseenter', '*[lay-tips]', function(){
		var othis = $(this)
		,tips = othis.attr('lay-tips')
		,offset = othis.attr('lay-offset') 
		,index = layer.tips(tips, this, {
			tips: 2
			,time: -1
			,success: function(layero, index){
				if(offset){
					layero.css('margin-left', offset + 'px');
				}
			}
		});
		othis.data('index', index);
	}).on('mouseleave', '*[lay-tips]', function(){
		layer.close($(this).data('index'));
	});
});