<div class="jbxx">
	<img src="/images/mrtx_tu.jpg"
		class="tx_tu" />
	<h3><?php echo get_member_info(QUID)['phone'];?></h3>
	<ul>
		<li><img src="/images/yz_tu1.gif" />
			<i class="icon yz_biao"></i></li>
		<li>
			<img src="/images/yz_tu3.gif" />
			<?php if(IS_CHECK){?><i class="icon yz_biao"></i><?php }else{?><i class="icon wyz_biao"><?php }?>
		</li>
	</ul>
</div>
<div class="zhzx_nav">
	<ul>
		<?php if(!IS_COMPANY) { ?>
			<li>
				<h3>账户管理</h3>
				<ul>
					<li><a href="/infos.html" class="<?php if(CONTROLLER=='infos'){echo 'on';}?>">个人信息</a></li>
					<li><a href="/safe.html" class="<?php if(CONTROLLER=='safe' && METHOD==''){echo 'on';}?>">安全管理</a></li>
				</ul>
			</li>
			<li>
				<h3>推荐管理</h3>
				<ul>
					<li><a href="/investor.html" class="<?php if(CONTROLLER=='investor'){echo 'on';}?>">推荐出借人</a></li>
					<li><a href="/investors/lists.html" class="<?php if(CONTROLLER=='investors' && METHOD=='lists'){echo 'on';}?>">出借人列表</a></li>
					<li><a href="/investors.html" class="<?php if(CONTROLLER=='investors' && METHOD==''){echo 'on';}?>">出借人明细</a></li>
				</ul>
			</li>
			<li>
				<h3>资金管理</h3>
				<ul>
					<li><a href="/account.html" class="<?php if(CONTROLLER=='account'){echo 'on';}?>">我的资产</a></li>
					<li><a href="/trade.html" class="<?php if(CONTROLLER=='trade'){echo 'on';}?>">交易记录</a></li>
				</ul>
			</li>
			
			<?php if(IS_FINANCE) { ?>
				<li>
					<h3>借款管理</h3>
					<ul>
						<li><a href="/apply/index.html" class="<?php if(CONTROLLER=='apply' && METHOD=='index'){echo 'on';}?>">借款申请</a></li>
						<li><a href="/apply/lists.html" class="<?php if(CONTROLLER=='apply' && METHOD=='lists'){echo 'on';}?>">借款列表</a></li>
					</ul>
				</li>
			<?php } else { ?>
				<li>
					<h3>出借管理</h3>
					<ul>
						<li><a href="/record.html" class="<?php if(CONTROLLER=='record'){echo 'on';}?>">项目出借</a></li>
					</ul>
				</li>
			<?php } ?>
			<!--li>
				<h3>借款管理</h3>
				<ul>
					<li><a href="/apply/index.html" class="<?php if(CONTROLLER=='apply' && METHOD=='index'){echo 'on';}?>">借款申请</a></li>
					<li><a href="/apply/lists.html" class="<?php if(CONTROLLER=='apply' && METHOD=='lists'){echo 'on';}?>">借款列表</a></li>
				</ul>
			</li-->
			<li>
				<h3>积分商城</h3>
				<ul>
					<li><a href="/mall/index.html" class="<?php if(CONTROLLER=='mall' && METHOD=='index'){echo 'on';}?>">商品列表</a></li>
					<li><a href="/mall/lists.html" class="<?php if(CONTROLLER=='mall' && METHOD=='lists'){echo 'on';}?>">兑换记录</a></li>
					<li><a href="/mall/address.html" class="<?php if(CONTROLLER=='mall' && METHOD=='address'){echo 'on';}?>">我的地址</a></li>
					<li><a href="/mall/prize.html" class="<?php if(CONTROLLER=='mall' && METHOD=='prize'){echo 'on';}?>">抽奖记录</a></li>
				</ul>
			</li>
		<?php } else { ?>
			<li>
				<h3>账户管理</h3>
				<ul>
					<li><a href="/infos.html" class="<?php if(CONTROLLER=='infos'){echo 'on';}?>">个人信息</a></li>
					<li><a href="/safe.html" class="<?php if(CONTROLLER=='safe'){echo 'on';}?>">安全管理</a></li>
					<li><a href="https://cg.eqqnsyh.com/des-manage/index.html#/home/merchant-enterprise" target="_blank">企业认证</a></li>
				</ul>
			</li>
			<li>
				<h3>资金管理</h3>
				<ul>
					<li><a href="/account.html" class="<?php if(CONTROLLER=='account'){echo 'on';}?>">我的资产</a></li>
					<li><a href="/trade.html" class="<?php if(CONTROLLER=='trade'){echo 'on';}?>">交易记录</a></li>
				</ul>
			</li>
			<li>
				<h3>借款管理</h3>
				<ul>
					<li><a href="/apply/index.html" class="<?php if(CONTROLLER=='apply' && METHOD=='index'){echo 'on';}?>">借款申请</a></li>
					<li><a href="/apply/blists.html" class="<?php if(CONTROLLER=='apply' && METHOD=='blists'){echo 'on';}?>">借款列表</a></li>
				</ul>
			</li>
		<?php } ?>
	</ul>
</div>