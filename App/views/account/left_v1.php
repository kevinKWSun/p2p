
<div class="jbxx_v2">
	<img src="/images/new/image/img-user-icon.png" class="tx_tu" />
	<h3><?php echo get_member_info(QUID)['phone'];?></h3>
	<ul>
		<li><img src="/images/new/image/yz_tu1.png" class="<?php if(!IS_CHECK){echo "dim-v2";}?>"/></li>
		<li><img src="/images/new/image/yz_tu3.png" /></li>
	</ul>
</div>
<div class="zhzx_nav_v2">
	<ul>
		<?php if(!IS_COMPANY) { ?>
			<li>
				<dl class="account_v2">
					<dt class="">账户管理</dt>
					<dd><a href="/infos.html" class="<?php if(CONTROLLER=='infos'){echo 'on';}?>">个人信息</a></dd>
					<dd><a href="/safe.html" class="<?php if(CONTROLLER=='safe' && METHOD==''){echo 'on';}?>">安全管理</a></dd>
				</dl>
			</li>
			<li>
				<dl class="capital_v2">
					<dt class="">资金管理</dt>
					<dd><a href="/account/index.html" class="<?php if(CONTROLLER=='account'  && METHOD=='index'){echo 'on';}?>">我的资产</a></dd>
					<dd><a href="/account/packet.html" class="<?php if(CONTROLLER=='account' && METHOD=='packet'){echo 'on';}?>">我的红包</a></dd>
					<dd><a href="/account/withdraw.html" class="<?php if(CONTROLLER=='account' && METHOD=='withdraw'){echo 'on';}?>">提现</a></dd>
					<dd><a href="/account/recharge.html" class="<?php if(CONTROLLER=='account' && METHOD=='recharge'){echo 'on';}?>">充值</a></dd>
					<dd><a href="/trade.html" class="<?php if(CONTROLLER=='trade'){echo 'on';}?>">交易记录</a></dd>
					<?php if(!IS_COMPANY) { ?>
					<dd><a href="/record.html" class="<?php if(CONTROLLER=='record'){echo 'on';}?>">项目出借</a></dd>	
					<?php }?>
				</dl>
			</li>
			<li>
				<dl class="recommend_v2">
					<dt class="">借款管理</dt>
					<dd><a href="/apply/index.html" class="<?php if(CONTROLLER=='apply' && METHOD=='index'){echo 'on';}?>">借款申请</a></dd>
					<?php if(IS_FINANCE) { ?>
						<dd><a href="/apply/lists.html" class="<?php if(CONTROLLER=='apply' && METHOD=='lists'){echo 'on';}?>">借款列表</a></dd>
					<?php } ?>
				</dl>
			</li>
			<?php if(!IS_COMPANY) { ?>
			<li>
				<dl class="recommend_v2">
					<dt class="">推荐管理</dt>
					<dd><a href="/investor.html" class="<?php if(CONTROLLER=='investor'){echo 'on';}?>">推荐出借人</a></dd>
					<dd><a href="/investors/lists.html" class="<?php if(CONTROLLER=='investors' && METHOD=='lists'){echo 'on';}?>">出借人列表</a></dd>
					<dd><a href="/investors.html" class="<?php if(CONTROLLER=='investors' && METHOD==''){echo 'on';}?>">出借人明细</a></dd>
				</dl>
			</li>
			<?php }?>
			<li>
				<dl class="integral_v2">
					<dt class="">积分商城</dt>
					<dd><a href="/mall/index.html" class="<?php if(CONTROLLER=='mall' && METHOD=='index'){echo 'on';}?>">商品列表</a></dd>
					<dd><a href="/mall/lists.html" class="<?php if(CONTROLLER=='mall' && METHOD=='lists'){echo 'on';}?>">兑换记录</a></dd>
					<dd><a href="/mall/address.html" class="<?php if(CONTROLLER=='mall' && METHOD=='address'){echo 'on';}?>">我的地址</a></dd>
					<dd><a href="/mall/ztree.html" class="<?php if(CONTROLLER=='mall' && METHOD=='ztree'){echo 'on';}?>">优果派兑换</a></dd>
				<dl>
			</li>
		<?php } else { ?>
			<li>
				<dl class="account_v2">
					<dt class="">账户管理</dt>
					<dd><a href="/infos.html" class="<?php if(CONTROLLER=='infos'){echo 'on';}?>">个人信息</a></dd>
					<dd><a href="/safe.html" class="<?php if(CONTROLLER=='safe' && METHOD==''){echo 'on';}?>">安全管理</a></dd>
					<dd><a href="https://cg.eqqnsyh.com/des-manage/index.html#/home/merchant-enterprise" target="_blank">企业认证</a></dd>
				</dl>
			</li>

			<li>
				<dl class="capital_v2">
					<dt class="">资金管理</dt>
					<dd><a href="/account/index.html" class="<?php if(CONTROLLER=='account'  && METHOD=='index'){echo 'on';}?>">我的资产</a></dd>
					<dd><a href="/account/withdraw.html" class="<?php if(CONTROLLER=='account' && METHOD=='withdraw'){echo 'on';}?>">提现</a></dd>
					<dd><a href="/account/recharge.html" class="<?php if(CONTROLLER=='account' && METHOD=='recharge'){echo 'on';}?>">充值</a></dd>
					<dd><a href="/trade.html" class="<?php if(CONTROLLER=='trade'){echo 'on';}?>">交易记录</a></dd>
				</dl>
			</li>
			<li>
				<dl class="recommend_v2">
					<dt class="">借款管理</dt>
					<dd><a href="/apply/index.html" class="<?php if(CONTROLLER=='apply' && METHOD=='index'){echo 'on';}?>">借款申请</a></dd>
					<dd><a href="/apply/lists.html" class="<?php if(CONTROLLER=='apply' && METHOD=='lists'){echo 'on';}?>">借款列表</a></dd>
				</dl>
			</li>
		<?php } ?>
		
	</ul>
</div>

<script>
	$(".zhzx_nav_v2 li dl dd a.on").parents("dl").children("dt").addClass("on");
</script>