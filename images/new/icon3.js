var KX_cfg_data = {};
KX_cfg_data.script = document.getElementById('kx_script');
KX_cfg_data.cnnic_pram = KX_cfg_data.script.src.indexOf('?') > -1 ? KX_cfg_data.script.src.split('?')[1] : '';
KX_cfg_data.cnnic_link = "https://ss.knet.cn/verifyseal.dll" + "?" + KX_cfg_data.cnnic_pram + "&ct=df" + "&a=1&pa=" + Math.random();
KX_cfg_data.imgUrl = ('https:' == document.location.protocol ? 'https://ss.knet.cn' : 'http://rr.knet.cn') + "/static/images/logo/cnnic.png";
//KX_cfg_data.size = KX_cfg_data.script.getAttribute('size');
KX_cfg_data.size = {'0':{'w':128,'h':47},'1':{'w':64,'h':23},'2':{'w':79,'h':28},'3':{'w':94,'h':33},'4':{'w':109,'h':38}};

KX_cfg_data.createKX = function (size){
	var oSize = KX_cfg_data.size[size];

	KX_cfg_data.container = document.getElementById(KX_cfg_data.script.getAttribute('cid'));
	KX_cfg_data.container.style.display = 'inline-block';
	KX_cfg_data.container.style.position = 'relative';
	KX_cfg_data.container.style.width = oSize.w + 'px';
	KX_cfg_data.container.style.height = oSize.h + 'px';

	KX_cfg_data.domstr = '<a id="kx_verify_link" href="' + KX_cfg_data.cnnic_link + '" tabindex="-1" target="_blank" kx_type="&#x56FE;&#x6807;&#x5F0F;" rel="nofollow"><img src="' + KX_cfg_data.imgUrl + '"  style="border:0; position: absolute; top: 0px; left: 0px;" w="' + oSize.w + '" h="' + oSize.h + '" width="' + oSize.w + '" height="' + oSize.h + '" oncontextmenu="return false;" alt="&#x53EF;&#x4FE1;&#x7F51;&#x7AD9;" /></a>';
	KX_cfg_data.container.innerHTML = KX_cfg_data.domstr;

	if (size > 0){
		var img = KX_cfg_data.container.getElementsByTagName('img')[0];
		img.onmouseover = function (){
			var w = this.getAttribute('w'), h = this.getAttribute('h'), ow, oh;
			this.removeAttribute('width');
			this.removeAttribute('height');
			ow = this.offsetWidth;
			oh = this.offsetHeight
			this.style.top = -(oh - h)/2 + 'px';
			this.style.left = -(ow - w)/2 + 'px';
			this.style.width = ow + 'px';
		}
		img.onmouseout = function (){
			var w = this.getAttribute('w'), h = this.getAttribute('h');
			this.style.width = '';
			this.style.top = this.style.left = '0';
			this.setAttribute('width', w);
			this.setAttribute('height', h);
		}
	}
}

KX_cfg_data.createKX(KX_cfg_data.script.getAttribute('size') || 0);

(function (){
	var JSONP = document.createElement('script');
	JSONP.type = 'text/javascript';
	JSONP.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'kxlogo.knet.cn/seallogo.dll?callback=KX_cfg_data.jsonpCallback&' + KX_cfg_data.cnnic_pram;
	KX_cfg_data.script.parentNode.insertBefore(JSONP, KX_cfg_data.script)
})();

KX_cfg_data.jsonpCallback = function (result) {
	var _a = document.getElementById('kx_verify_link');
	var img = _a.getElementsByTagName('img')[0];
	img.src = result.imgUrl;
	_a.href = result.splashUrl;
};