<?php

//批量支付 https://doc.open.alipay.com/doc2/detail?treeId=64&articleId=103569&docType=1 
//curl "https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo=银行卡卡号&cardBinCheck=true"
//银联代付 https://open.unionpay.com/ajweb/product/detail?id=67
//银联批量代付文件标准 https://open.unionpay.com/ajweb/help?id=207#4.4.2
class CWalletDef extends CModDef {
	protected function desc() {
		return array(
			self::MOD => array(
				self::G_NM=>'wallet',
				self::M_CS=>'utf-8',
				self::G_TL=>'用户钱包',
				self::G_DC=>'提供支付功能，包括ali，银联等等'
			),
			self::TBDEF => array(
				'wallet_pay' => '(
					id                 int unsigned not null auto_increment,
					user_id            int unsigned not null,
					product_desc       varchar(16) not null,
					product_img_url    varchar(128),
					product_num        int unsigned,
					product_unit_price int unsigned,
					product_extra      varchar(32), -- product extra info 
					create_ts          int unsigned not null, -- register timestamp
					pay_type           tinyint unsigned, -- 1:points, 2: voucher, 4:alipay, 8:unionpay, 16:weixin, 4:..
					pay_extra          varchar(32) not null, -- extra info of which pay_type
					status             tinyint unsigned, -- 0:unpaid, 1:paid
					primary key(id),
					key(user_id)
				)',
			    'wallet_unipay_resp' => '(
			        order_id    varchar(32) not null,
			        query_id    varchar(32) not null,
			        send_resp   varchar(255) not null, -- msg(code)
			        settle_md   char(4) not null -- mmdd(1002),
			        settls_resp varchar(255) not null, -- msg(code)
			        primary key(order_id)
			    )',
			    'wallet_info' => '(
			        uid            int unsigned not null,
			        amount         int not null, -- available = (amount-withdraw_amount)
			        withdraw_amount int not null, -- locked if withdraw is applying
			        history_amount int unsigned not null,
			        change_ts      int unsigned not null,
			        status         tinyint not null, -- 0: normal, 1: banned(banned by sys if the user operate with some exceptions)
			        primary key(uid)
			    )',
			    'wallet_history' => '( -- M(a_uid, id)
			        id         int unsigned auto_increment not null,
			        a_uid      int unsigned not null, -- a->b($10): insert(a, b, -10),(b,a, +10)
			        b_uid      int unsigned not null,
			        type       tinyint unsigned not null, -- 0: get by submiting task, 1: withdraw
			        amount     int not null,
			        create_ts  int unsigned not null,
			        mark       varchar(16) not null,
			        primary key(id),
			        key(a_uid),
			    )',
			    // insert the record to 'wallet_withdraw_history' if successful and delete it after user visit
			    'wallet_withdraw_apply' => '(
			        uid          int unsigned not null,
			        amount       int unsigned not null,
			        dest_account varchar(64) not null,
			        account_name varchar(32) not null,
			        account_type tinyint not null, -- 0:uni-pay, 1:wx-pay, 2:ali-pay
			        submit_ts    int unsigned not null,
			        update_ts    int unsigned not null,
			        status       tinyint not null, -- 0: user submit, 1: sys submit, 2: successful, 3: failure
			        fault_msg    varchar(255) not null, -- payment result, successful or failure(msg:code)
			        attempt_num  tinyint not null,
			        primary key(uid)
			    )',
			    'wallet_withdraw_history' => '( -- M(uid, id)
			        id           int unsigned auto_increment not null,
			        uid          int unsigned not null,
			        amount       int unsigned not null,
			        dest_account varchar(64) not null,
			        account_name varchar(8) not null,
			        account_type tinyint not null,
			        submit_ts    int unsigned not null,
			        success_ts   int unsigned not null,
			        primary key(id),
			        key(uid)
			    )',
			),
			self::PAGES => array(
				'unionpay_notify' => array(
					self::P_TLE => '银联支付后回调接口',
					self::G_DC  => '银联处理完成回调此接口，用于通知支付是否成功(具体流程看银联文档)。即更新表中status字段',
					self::P_ARGS => array(
					),
					self::P_OUT => '{success:0/1, msg:"如果失败， 返回错误提示.成功后返回user_id", user_id:111}',
				),
			    'myinfo' => array(
			        self::P_TLE => '钱包信息',
			        self::G_DC  => '获取我的钱包信息，如果没有则初始化',
			        self::P_ARGS => array(
			        ),
			        self::P_OUT => '{详见#wallet_info#}',
			    ),
			    'history' => array(
			        self::P_TLE => '历史记录',
			        self::G_DC  => '获取钱包的历史记录',
			        self::P_ARGS => array(
			            'page_id'   => array(self::G_DC=>'分页id', self::PA_TYP=>'integer'),
			        ),
			        self::P_OUT => '{详见#wallet_history#, desc:任务#1323奖励/提现/...}',
			    ),
			    'withdraw' => array(
			        self::P_TLE => '钱包提现申请',
			        self::G_DC  => '接受申请后的几个工作日内完成',
			        self::P_ARGS => array(
			            'amount'   => array(self::G_DC=>'提现金额', self::PA_TYP=>'integer'),
			            'account'  => array(self::G_DC=>'提现帐号', self::PA_TYP=>'string'),
			            'acc_name' => array(self::G_DC=>'帐号姓名', self::PA_TYP=>'string'),
			        ),
			        self::P_OUT => '{}',
			    ),
			),
		);
	}
}

?>