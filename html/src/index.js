layui.extend({
  setter: 'config' //配置文件
  ,admin: 'lib/admin' //核心模块
}).define(['setter', 'admin'], function(exports){
  var setter = layui.setter
  ,admin = layui.admin
  ,view = admin.view
  
  //根据路由渲染页面
  ,renderPage = function(){
    var router = layui.router()
    ,path = router.path;
    
    if(!path.length) path = [''];
    
    if(path[path.length - 1] === ''){
      path[path.length - 1] = setter.entry;
    }
    
    layui.config({
      base: setter.base + 'controller/'
    });
    
    view().render(path.join('/')).then(function(){
      layui.use('common', layui.cache.callback.common);
      
      $win.on('resize', layui.data.resize);
    });
    
    //格式化
    renderPage.haveInit && layer.closeAll();
    renderPage.haveInit = true;
    $(APP_BODY).scrollTop(0);
    
    //resize
    layui.data.resize && $win.off('resize', layui.data.resize);
    delete layui.data.resize;
  }
  ,APP_BODY = '#LAY_app_body', $ = layui.$, $win = $(window);
  
  //初始主体结构
  layui.link(setter.base + 'style/admin.css', function(){
    view(setter.container).render('layout').then(function(){
      renderPage();
      layui.element.render();
      
      //容器 scroll 事件，剔除吸附层
      $(APP_BODY).on('scroll', function(){
        var elemDate = $('.layui-laydate')
        ,layerOpen = $('.layui-layer')[0];

        //关闭 layDate
        if(elemDate[0]){
          elemDate.each(function(){
            var othis = $(this);
            othis.hasClass('layui-laydate-static') || othis.remove();
          });
        }
        
        //关闭 Tips 层
        layerOpen && layer.closeAll('tips');
      });
    }); 
    
  }, 'layuiAdmin');
  
  //监听Hash改变
  window.onhashchange = function(){
    renderPage();
     //执行 {setter.MOD_NAME}.hash 下的事件
    layui.event.call(this, setter.MOD_NAME, 'hash({*})', layui.router());
  };
  
  //扩展 lib 目录下的其它模块
  layui.each(setter.dependencies, function(index, item){
    var mods = {};
    mods[item] = '{/}' + setter.base + 'lib/' + item;
    layui.extend(mods);
  });

  //对外输出
  exports('index', {
    render: renderPage
  });
});
