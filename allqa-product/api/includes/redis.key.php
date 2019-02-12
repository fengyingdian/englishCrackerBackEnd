<?php

/* 字典数据 system */
define("REDIS_KEY_ONE_SYSTEM","one_system_%s");

/* 通过id获取单条用户信息 */
define("REDIS_KEY_ONE_ACCOUNT_INFO","one_account_info_%s");

/* 通过手机号获取单条用户信息 */
define("REDIS_KEY_ONE_MOBILE_ACCOUNT_ID","one_mobile_account_id_%s");

/* 通过手机号获取单条教师信息 */
define("REDIS_KEY_ONE_MOBILE_TEACHER_ID","one_mobile_teacher_id_%s");

/* 通过用户id获取单条教师信息 */
define("REDIS_KEY_ONE_TEACHER_INFO","one_mobile_teacher_info_%s");

/* 通过id获取单个题目信息 */
define("REDIS_KEY_ONE_QUESTION_INFO","one_question_info_%s");

/* 通过 knowledgeid获取单个知识点名称 */
define("REDIS_KEY_ONE_KNOWLEDGE_INFO","one_knowledge_info_%s");

/* 通过course_id获取学科信息 */
define("REDIS_KEY_ONE_COURSE_INFO","one_course_info_%s");

/* 通过教材版本id获取单条信息 */
define("REDIS_KEY_ONE_BOOK_VERSION_INFO","one_book_version_info_%s");

/* 通过教材id获取单条信息 */
define("REDIS_KEY_ONE_BOOK_INFO","one_book_info_%s");

/* 通过id获取单条分享信息 */
define("REDIS_KEY_ONE_ACCOUNT_SHARE_INFO","one_account_share_info_%s");

/* 通过id获取单条提问信息内容 */
define("REDIS_KEY_ONE_QUESTION_ASK_INFO","one_question_ask_info_%s");

/* 通过订单id获取单条提问信息 */
define("REDIS_KEY_ONE_ASK_QUESTION_ORDER_INFO","one_ask_question_order_info_%s");

/* 通过id获取单条只是分享评论内容 */
define("REDIS_KEY_ONE_SHARE_COMMENT_INFO","one_share_comment_info_%s");

/* 通过account_id share_id获取已付费的分享内容 */
define("REDIS_KEY_ONE_ACCOUNT_PURCHASED_SHARE_INFO","one_account_purchased_share_info_%s");

/* 通过问答题id 获取对应的解答视频 */
define("REDIS_KEY_ONE_QUESTION_ANSWER_VEDIO_INFO","one_question_answer_vedio_info_%s");

/* 通过id获取对应问答题解答的评论 */
define("REDIS_KEY_ONE_ANSWER_COMMENT_INFO","one_answer_comment_info_%s");

/* 通过id获取用户介绍 */
define("REDIS_KEY_ONE_ACCOUNT_INTRO_INFO","one_account_intro_info_%s");

/* 通过account_id获取解答总数 */
define("REDIS_KEY_ONE_COUNT_ANSWERED_NUM","one_count_answered_num_%s");

/* 通过account_id获取粉丝总数 */
define("REDIS_KEY_ONE_COUNT_ANS_NUM","one_count_fans_num_%s");

/* 通过share_id获取点赞总数 */
define("REDIS_KEY_ONE_COUNT_SHARE_LIKE_NUM","one_count_share_like_num_%s");

/* 用户是否点赞 */
define("REDIS_KEY_ONE_ACCOUNT_SHARE_RELATION","one_account_share_relation_%s");

/* 通过id获取提问-解答-评价信息 */
define("REDIS_KEY_ONE_ACCOUNT_EXPLAIN_COMMENT_INFO","one_account_explain_comment_info_%s");

/* 用户关注信息 */
define("REDIS_KEY_ONE_FOLLOW_INFO","one_follow_info_%s");

/* 通过account_id,order_sn获取订单信息 */
define("REDIS_KEY_ONE_PAY_ORDER_INFO","one_pay_order_info_%s");

/* 通过订单编号获取问题信息 */
define("REDIS_KEY_ONE_ORDER_ASK_QUESTION_ID","one_order_ask_question_id_%s");

/* 通过id获取充值套餐信息 */
define("REDIS_KEY_ONE_RECHARGE_PACKAGE_INFO","one_recharge_package_info_%s");

/* 地域列表 */
define("REDIS_KEY_LIST_SYS_REGION","list_sys_region_%s");

/* 评价标签列表 */
define("REDIS_KEY_LIST_SYS_EVALUATION_TAG","list_sys_evaluation_tag_%s");


/* v1.1.0添加 */

/* 微课信息 */
define("REDIS_KEY_ONE_ACCOUNT_MICRO_CLASS_INFO","one_acount_micro_class_info_%s");

/* 学段学科信息 */
define("REDIS_KEY_ONE_COURSE_BY_SYS_ID_INFO","one_course_by_sys_id_info_%s");

/* 根据用户id,问题id获取一对一评论 */
define("REDIS_KEY_ONE_EXPLAIN_COMMENT_BY_ACCOUNT_INFO","one_explain_comment_by_account_info_%s");

/* 根据学校id获取学校信息 */
define("REDIS_KEY_ONE_SCHOOL_INFO","one_school_info_%s");
?>