<?php

/**
 * Created by VScode.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 16:04
 */

namespace app\admin\controller;

use think\Db;

class Article
{
    //热门文章的接口
    public function articlehot()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true'); //添加头部信息，因为在本地做测试，所以需要允许跨域。
        // echo "文章接口";
        // $articles = Db::query("SELECT articleTitle,substr(articleContent,1,10) FROM articles");
        $articles = Db::query("(SELECT substr(articleTitle,1,30) as articleTitle, articleLikeCount, ariticleViews, articleDate, articleCommentCount,articleId,substr(articleContent,1,90)as articleContent FROM articles order by articleLikeCount DESC )limit 20 "); //按文章表关键字atricleLikeCount进行降序 排序 查询文字标题和文章内容，并将文章内容限制为50个字符的长度。截取部分字符串=>substring(字符串表达式,开始位置,长度)
        // $articlecontent = Db::query("select articleContent from articles");
        // $articleid = Db::query("select articleId from articles");
        if ($articles) {
            //  return array("articles" =>array('articletitle'=>$articletitle,'articlecontent'=>$articlecontent));
            return $articles;
        } else {
            return "数据库数据有异常";
        }
    }
    //写的文章 保存新文章
    public function postarticle()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true'); //添加头部信息，因为在本地做测试，所以需要允许跨域。
        $postData = json_decode(file_get_contents("php://input"), true);
        $title = $postData['articletitle'];
        $content = $postData['content'];
        // return $content;
        $id = Db::query("SELECT MAX(articleId) as articleid from articles");
        $id = $id[0]['articleid']; //将json对象转为int
        $id = $id + 1;
        $time = date('Y-m-d H:i:s', time()); //获取当前时间，格式为2019-12-12 15：15：23
        // return $content;
        if ($title != null && $content != null) {
            Db::query("INSERT INTO articles ( articleTitle, articleContent, articleDate, articleId ) VALUES ( '$title', '$content',  '$time','$id')");
            return array("resultcode" => 1, "resultmsg" => "发布成功");
        } else {
            return array("resultcode" => 0, "resultmsg" => "没有文章题目或者内容");
        }
    }
    //热门文章页标签分类文章获取接口
    public function articletagget()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true'); //添加头部信息，因为在本地做测试，所以需要允许跨域。
        //按文章表关键字atricleLikeCount进行降序 排序 查询文字标题和文章内容，并将文章内容限制为50个字符的长度。截取部分字符串=>substring(字符串表达式,开始位置,长度)
        $articles = Db::query("SELECT articleTitle, articleLikeCount, ariticleViews, articleDate, articleCommentCount,articleId,substr(articleContent,1,90)as articleContent FROM articles limit 10 ");
        if ($articles) {
            //  return array("articles" =>array('articletitle'=>$articletitle,'articlecontent'=>$articlecontent));
            return $articles;
        } else {
            return "数据库数据有异常";
        }
    }
    //首页好文推荐接口
    public function articlerendom()
    {
        $idmax = Db::query("SELECT MAX(articleId) as articleid from articles"); //文章id最大值
        $idmax = $idmax[0]['articleid']; //将json对象转为int
        $idmax = $idmax + 1;
        $id = rand(1, $idmax); //随机一个在文章id之间的数用作数据库limit查询的条件，避免随机数超出文章表范围
        $id = 1;
        if ($id > 1) {
            $id = $id - 1;
        }
        $rendomarticle = Db::query("SELECT  substr(articleTitle,1,20)as articleTitle,substr(articleContent,1,80)as articleContent,articleId FROM articles ORDER BY RAND() limit 4");
        return $rendomarticle;
    }
    //首页最新文章接口
    public function lastarticleget()
    {
        $id = Db::query("SELECT MAX(articleId) as articleid from articles");
        $id = $id[0]['articleid']; //将json对象转为int
        $id = $id - 2;
        $lastarticle = Db::query("SELECT  substr(articleTitle,1,20)as articleTitle,substr(articleContent,1,80)as articleContent, articleId FROM articles ORDER BY articleId desc  limit 4");
        return $lastarticle;
    }
    //点击文章去到文章详情的请求
    public function articledatail()
    {
        //接收到前端发来的点击的articleid
        $postData = json_decode(file_get_contents("php://input"), true);
        // $postData = 2;
        $view = Db::query("SELECT ariticleViews FROM articles where articleId=$postData");
        // $view = Db::query("SELECT useariticleViewsrId FROM articles where articleId=$postData");
        // $view = $view[0]['ariticleViews'];
        if ($view[0]['ariticleViews'] == null) {
            $view = 0;
        } else {
            $view = $view[0]['ariticleViews'];
        }
        $view = $view + 1;
        Db::query("UPDATE articles SET ariticleViews = '$view' where articleId=$postData");
        $userid = Db::query("SELECT userId FROM articles where articleId=$postData");
        $userid = $userid[0]['userId'];
        // return $userid;
        $userName = Db::query("SELECT userName FROM users where userId=$userid");
        $articledetail = Db::query("SELECT * FROM articles where articleId=$postData");
        //获取推荐文章数据
        $tuijianarticles = Db::query("SELECT articleTitle, articleLikeCount, ariticleViews, articleDate, articleCommentCount,articleId,substr(articleContent,1,50)as articleContent FROM articles order by RAND() limit 5");
        // $tuijianarticles = Db::query("SELECT  substr(articleTitle,1,20)as articleTitle,substr(articleContent,1,80)as articleContent,articleId FROM articles ORDER BY RAND() limit 4")
        return array('article' => $articledetail, 'username' => $userName, 'tuijianarticles' => $tuijianarticles);
    }
    //写好文章后去看看
    public function writetosee()
    {
        $idmax = Db::query("SELECT MAX(articleId) as articleid from articles");
        $idmax = $idmax[0]['articleid'];
        $article = Db::query("SELECT * FROM articles where articleId=$idmax");
        $tuijianarticles = Db::query("(SELECT articleTitle, articleLikeCount, ariticleViews, articleDate, articleCommentCount,articleId,substr(articleContent,1,50)as articleContent FROM articles order by articleLikeCount DESC )limit 5");
        return array('article' => $article, 'tuijianaraticles' => $tuijianarticles);
    }
}
