<?php 

function __user_mod_menu(){
	
}


$lang_zh_CN = array(
	'oper_succ'     => '操作成功&nbsp;!&nbsp;&nbsp;您可以继续编辑或者浏览',
	'close'         => '关闭',
	'login'         => '登录',
	'phone'         => '手机号码',
	'password'      => '密码',
	'captcha'       => '验证码',
	'reload_on_unclear' => '换一个',
	'invalid_phone' => '手机号码无效',
	'invalid_password'  => '密码无效',
	'invalid_captcha'   => '验证码无效',
	'remember_me'       => '记住我',
	'had_login'         => '您已登录！',
	'login_succeed'     => '登录成功！',
	'logout_succeed'    => '退出成功！',
	'login_first'       => '请先登录',
		
	'record_info'       => '录入信息',
	'edit_info'         => '编辑信息',
	'name'              => '姓名',
	'organization'       => '单位',
	'email'             => '邮箱',
	'VPDN_name'         => 'VPDN 名称',
	'class'             => '分类',
	'select_class'      => '获取分类',
	'add_class'         => '添加分类',
	'class_name'        => '名称',
	'class_code'        => '编码',
	'class_list'        => '列表',
	'all_class'         => '全部分类',
	'select'            => '选择',
	'delete'            => '删除',
	
	'list'              => '列表',
	'search'            => '查询',
	'add'               => '添加',
	'edit'              => '编辑',
	'user'              => '用户',
		
	'department'        => '部门',
	'add_department'    => '添加部门',
	'join_department'   => '加入相应业务部门',
	'dep_exists'        => '部门已存在',
	'member_exists'     => '成员已存在，或已加入其它部门',
	'dep_member'        => '部门成员',
	'join_time'         => '加入时间',
	'confirmed_delete_dep'         => '确认删除此部门及其成员吗？删除后，操作无法撤销',
	'dep_login'         => '部门登录',
		
	'data'              => '资料',
	'pwd_diff_or_error' => '密码不同或有误',
	'total_member'      => '共%d人',
	'member'            => '成员',
	'num'               => '数',
	'auto_login_in_next'=> '下次自动登录',
	'welcome'           => '欢迎登录快讯服务平台',
	'user_must_modify_pwd' => '用户须在首次登陆的时候修改密码',
	'invalid_device'    => '无效设备',
		
	'menu'              => function(){
		global $mbs_appenv;
		$items = array(
			$mbs_appenv->toURL('list')       => '列表',
			$mbs_appenv->toURL('class')      => '分类',
			$mbs_appenv->toURL('department') => '部门',
		);
		$sub_items = array(
			$mbs_appenv->toURL('list')       => array($mbs_appenv->toURL('edit')),
			$mbs_appenv->toURL('class')      => array($mbs_appenv->toURL('class_edit')),
			$mbs_appenv->toURL('department') => array($mbs_appenv->toURL('dep_edit')),
		);
		echo '<div class="pure-menu custom-restricted-width"><ul class="pure-menu-list">';
		foreach($items as $link => $val){
			$selected = $mbs_appenv->item('cur_action_url')==$link 
				|| in_array($mbs_appenv->item('cur_action_url'), $sub_items[$link]);
			echo '<li class="pure-menu-item',$selected?' pure-menu-selected':'',
				'"><a href="', $link, '" class="pure-menu-link">', $val, '</a></li>';
		}
		echo '</ul></div>';
	},
);

?>