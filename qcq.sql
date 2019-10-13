CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号,主键,自动增长',
  `nickname` varchar(100) not null default '',
  `open_id` varchar (100) not null default '',
  `logo` varchar (255) not null default '',
  `pre_log_time` TIMESTAMP default current_timestamp ,
  primary key (`id`),
  key (`open_id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=10023 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员信息基本表';


  create table `court` (
      `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号,主键,自动增长',
      `name` varchar (55) not null default '',
      `user_id` int(11) not null comment '发布者id',
      `address` varchar (255) not null default '',
      `lat` float (10) not null default '0.00',
      `long` float (10) not null default '0.00',
      `is_money` tinyint(2) not null default 1,
      primary key (`id`),
      key (`user_id`)
  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='球场表';


  create table `activity` (
    `id` int(11) not null auto_increment comment '',
    `user_id` bigint not null ,
    `title` varchar(55) not null default '',
    `message` varchar(255) not null default '',
    `is_money` tinyint(2) not null default 1 comment '是否收费1是2否',
    `money` DECIMAL(10, 0) not null default 0 comment '收费多少每人',
    `start_time` timestamp not null default current_timestamp comment'开始时间',
    `join_end_time` timestamp not null default current_timestamp comment '报名截止时间',
    `max_num` tinyint(2) not null default 0,
    `logo` varchar(255) not null default '',
    `address` varchar(155) not null ,
    `lat`  decimal(10,7) not null ,
    `long` decimal(10,7) not null,
    `created_at` timestamp not null default current_timestamp,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `order` tinyint(2) not null default 99,
    `status` tinyint(2) not null default 1,
    `is_top` tinyint(2) not null default 2 comment '是否置顶 1是2否',
    primary key (`id`),
    key(`user_id`),
    key(`created_at`),
    key(`order`)
  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动表';


  create table `activity_join` (
    `id` int(11) not null auto_increment comment '',
    `user_id` bigint not null ,
    `activity_id` bigint not null ,
    `create_time` timestamp default current_timestamp not null,
    `status` tinyint(2) not null default 1 comment '1 正常 2拒绝',
    `update_time`  timestamp default current_timestamp ,
    primary key(id),
    key(`user_id`),
    key(`activity_id`)
  )ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动申请表';


  create table `notice` (
      `id` int(11) not null auto_increment,
      `user_id` int(11) not null default 0,
      `title` varchar(100) not null default '',
      `content` text not null,
      `type` tinyint(2) not null default 1 comment '系统通知',
      `created_at` timestamp not null default current_timestamp,
      `updated_at` timestamp not null default current_timestamp on update current_timestamp,
      `status` tinyint(2) not null default 1,
      primary key(`id`),
      key(`user_id`)
  )engine=InnoDB,DEFAULT CHARSET=utf8 COMMENT='通知表';
