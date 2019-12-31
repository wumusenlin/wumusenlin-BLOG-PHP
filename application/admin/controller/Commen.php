<?php

/**
 * Created by VScode.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 16:04
 */

namespace app\admin\controller;

use think\Db;

class Commen
{
    //点赞接口
    public function star()
    {
        $postData = json_decode(file_get_contents("php://input"), true);
        // $postData = 1;
        $articlelike = Db::query("SELECT articleLikeCount FROM articles where articleId=$postData");
        $articlelike = $articlelike[0]['articleLikeCount'] + 1;
        Db::query("UPDATE articles SET articleLikeCount =$articlelike where articleId=$postData ");
        return $articlelike;
    }
    //获取文章详情页的评论数据
    public function articlecomment()
    {
        //添加头部信息，因为在本地做测试，所以需要允许跨域。
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With,application/json,Content-Type/image/jpeg');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: *");

        $postData1 = json_decode(file_get_contents("php://input"), true);
        // return $postData1;
        // $postData = 27;
        // $userid = Db::query("SELECT userid FROM comments where articleId = $postData1");
        $comments = Db::query("SELECT userName, commentDate, commentContent FROM comments a LEFT JOIN users b on a.userId=b.userId where articleId = '$postData1' ");
        return $comments;
    }
    //文章详情页发布评论接口
    public function commentpost()
    {
        //添加头部信息，因为在本地做测试，所以需要允许跨域。
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With,application/json,Content-Type/image/jpeg');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: *");

        $postData = json_decode(file_get_contents("php://input"), true);
        $comment = $postData['comment'];
        $userid = $postData['userid'];
        $articleid = $postData['articleid'];
        $commentid = Db::query("select max(commentId) as commentId from comments");
        $commentid = $commentid[0]['commentId'];
        $commentid =  $commentid + 1;
        $time = date('Y-m-d h:i:s', time());
        if ($comment == null || $userid == null || $articleid == null) {
            return array("resultcode" => 0, "resultmsg" => "提交评论失败");
        } else {
            $commentNumber = Db::query("SELECT articleCommentCount FROM articles where articleId=$articleid");
            $commentNumber = $commentNumber[0]['articleCommentCount'] + 1;
            Db::query("UPDATE articles SET articleCommentCount = $commentNumber where articleId=$articleid");
            //向数据库写入评论信息
            Db::query("INSERT INTO comments (commentContent, userId, articleId, commentDate, commentId)
                VALUES
                ( '$comment', '$userid', '$articleid', '$time', '$commentid')");
            return array("resultcode" => 1, "resultmsg" => "提交评论成功");
        }
    }
}
