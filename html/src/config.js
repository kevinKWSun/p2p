layui.define(['laytpl', 'layer', 'element', 'util'], function(exports){
  exports('setter', {
    container: 'LAY_app' //容器ID
    ,base: layui.cache.base //记录layuiAdmin文件夹所在路径
    ,views: layui.cache.base + 'views/' //视图所在目录
    ,entry: 'index' //入口文件名
    ,engine: '.html' //模板文件后缀名
    //,cache: true //是否开启模板文件缓存，暂时不支持
    
    ,name: 'layuiAdmin Pro'
    ,tableName: 'layuiAdmin' //本地存储表名
    ,MOD_NAME: 'admin' //模块事件名
    
    ,response: {
      statusName: 'code' //数据状态的字段名称
      ,statusCode: 0 //成功的状态码
      ,msgName: 'msg' //状态信息的字段名称
      ,dataName: 'data' //数据详情的字段名称
    }
    
    //依赖的第三方模块
    ,dependencies: [
      'echarts', //echarts 核心包
      'echartsTheme' //echarts 主题
    ]
    
    //主题配置
    ,theme: {
      //配色方案，如果用户未设置主题，第一个将作为默认
      color: [{
        header: '#23262E' //头部背景色
        ,side: '#393D49' //侧边背景色
        ,alias: 'default' //别名
      },{
        header: '#009688'
        ,side: '#393D49'
        ,alias: 'green' //墨绿
      },{
        header: '#1E9FFF'
        ,side: '#324057'
        ,alias: 'blue' //亮蓝
      },{
        header: '#AC2828'
        ,side: '#26333E'
        ,alias: 'red' //大红
      },{
        header: '#339558'
        ,side: '#24512E'
        ,alias: 'green-pardon' //原谅绿
      },{
        header: '#2F4056'
        ,side: '#2F4056'
        ,alias: 'black-blue' //黑蓝
      },{
        header: '#4E023A'
        ,side: '#1D0117'
        ,alias: 'purple' //紫红
      },{
        header: '#5D3400'
        ,side: '#241500'
        ,alias: 'golden-brown' //金棕
      },{
        header: '#fff'
        ,side: '#393D49'
        ,alias: 'white' //白
      }]
      ,hideSideIcon: false //是否隐藏侧边菜单图标
    }
  });
});
