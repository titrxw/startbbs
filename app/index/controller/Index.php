<?php
namespace app\index\controller;

use app\common\model\Category as CategoryModel;
use app\common\model\Topic as TopicModel;
use app\common\model\User as UserModel;
use app\common\controller\HomeBase;
use Db;
use Cache;
use Session;


use app\common\model\Attach as AttachModel;

class Index extends HomeBase
{
    public function index()
    {
        $params = $this->request->param();
        $params['order'] = empty($params['order']) ? 'ord' : $params['order'];
        $params['order'] = in_array($params['order'], array('ord', 'views')) ? $params['order'] : 'ord';
        $this->category_model = new CategoryModel();
        $this->topic_model = new TopicModel();
        
        $newtopiclist = [];
        $keyword = '';
        if (!empty($params['keyword'])) {
            $keyword = $params['keyword'];
            $newtopiclist = $this->topic_model->where([['title','like', "%$keyword%"]])->whereOr([['description','like', "%$keyword%"]])->order($params['order'],'desc')->paginate(10);
        }
        !$newtopiclist && $newtopiclist = $this->topic_model->order($params['order'],'desc')->paginate(10);
    	$page=$newtopiclist->render();
        $this->assign('newtopiclist', $newtopiclist);
        $this->assign('page', $page);
        
        $this->user_model = new UserModel();
        $user_list = $this->user_model->limit(12)->order('posts desc')->select();
        $hottopiclist = $this->topic_model->limit(10)->order('views','desc')->select();
        $this->assign('hottopiclist', $hottopiclist);
        $this->assign('user_list', $user_list);

        $link = Db::name('link')->select();

        $title = '首页';
        $this->assign('corder', $params['order']);
        $this->assign('keyword', $keyword);
        $this->assign('title', $title);
        $this->assign('link', $link);

        
        return $this->fetch();
    }
}
