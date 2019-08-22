<?php
namespace rest\index\controller;

use rest\index\base\BaseController;



class Test extends BaseController
{
   


    function autoPull(){
        /*
        {
	"zen": "Coding！ 让开发更简单",
	"hook_id": 92608,
	"hook": {
		"id": 92608,
		"name": "web",
		"type": "Repository",
		"active": true,
		"events": ["push"],
		"config": {
			"content_type": "json",
			"url": "http://120.55.59.94/index.php/index/Test/autopull"
		},
		"created_at": 1539759664000,
		"updated_at": 1539759776000
	},
	"sender": {
		"id": 1536892,
		"login": "droicao",
		"avatar_url": "https://coding.net/static/fruit_avatar/Fruit-17.png",
		"url": "https://coding.net/api/user/key/droicao",
		"html_url": "https://coding.net/u/droicao",
		"name": "droicao",
		"name_pinyin": ""
	},
	"repository": {
		"id": 3349410,
		"name": "online_front",
		"full_name": "droicao/online_front",
		"owner": {
			"id": 1536892,
			"login": "droicao",
			"avatar_url": "https://coding.net/static/fruit_avatar/Fruit-17.png",
			"url": "https://coding.net/api/user/key/droicao",
			"html_url": "https://coding.net/u/droicao",
			"name": "droicao",
			"name_pinyin": ""
		},
		"private": true,
		"html_url": "<a href='https://coding.net/u/droicao/p/online_front' target='_blank'>online_front</a>",
		"description": "",
		"fork": false,
		"url": "https://coding.net/api/user/droicao/project/online_front",
		"created_at": 1531789181000,
		"updated_at": 1531789181000,
		"clone_url": "https://git.coding.net/droicao/online_front.git",
		"ssh_url": "git@git.coding.net:droicao/online_front.git",
		"default_branch": "master"
	}
}
        */
        $json = file_get_contents("php://input");
        $json = json_decode($json,1);
        $type = I('get.type');

        $json['head_commit']['message']='release';
        if(strpos($json['head_commit']['message'],'release') !== false ){
            $jenkinsUrl = "http://120.55.59.94:8000/view/all/job/backend/build?token=backend";
            $r = curl_get_content($jenkinsUrl,[],'POST');
            echo($r);
        }

/*        if($type == 'front'){
            file_put_contents('/tmp/git_front_update.txt',1);
        }elseif($type == 'backend'){
            file_put_contents('/tmp/git_backend_update.txt',1);
        }elseif($type == 'admin'){
            file_put_contents('/tmp/git_admin_update.txt',1);
        }*/

    }

    function sendAlarm(){
        $content = I('text');
        $admin_mail = config("admin_email");
        //$admin_mail="f1f3@qq.com";
        $r = sendmail("出错啦！",$content,$admin_mail,'');
        if($r) return $content;
    }

    function logSqlToDb(){

        $files = glob(RUNTIME_PATH."*.sql");
        foreach ($files as $k=>$file){
            $pathinfo = pathinfo($file);
            $id = $pathinfo['filename'];
            if(!is_numeric($id)) continue;
            $sqlContent = file_get_contents($file);
            //$sqlContent = addslashes($sqlContent);
            $r = \think\Db::name('LogRequest')->where(['id'=>$id])->update(['response'=>$sqlContent]);
            //echo \think\Db::name('LogRequest')->getLastSql();
            if($r){
                unlink($file);
            }
        }
    }


}
