# 执信团委 青年志愿者协会  [![语法检查状态](https://travis-ci.org/zhxtw/qingzhi.svg?branch=master)](https://travis-ci.org/zhxtw/qingzhi)

![logo](https://raw.githubusercontent.com/zhxtw/qingzhi/master/logo.png)

## 简介

这是执信团委信息部（2014和2015级）特约为执信团委青年志愿者协会编写的青志管理系统

报名无需登录，仅需登记个人信息；

后台管理过程：登记地点信息 -> 开放报名 -> 审核报名（可设为自动）-> 分配（可设为自动）-> 工时确认 -> 导出信息

后台可以提供~~丰富~~的功能，如自动分配、工时管理等。

**（还没做完呢）**

### 特点

* 简洁易用

  操作简单快捷，常用功能一应俱全

  （在系统完成之前这句话可以忽略...）

* 美观大方

  前台采用时尚的material design设计，后台基于bootstrap定制，更有~~自主研发~~的表格、通知框、进度条等等

* 贴心快速

  加入老人机版本，无论是IE6、小诺基亚，还是30M流量套餐、WiFi用户，都可以顺利完成报名，关闭缓存的情况下报名一次仅需5.5KB，当然啦，不包括频繁的ACK包，一次40字节
  ![省流量](http://i13.tietuku.com/88b89f099ca99888.png)

  前端js使用异步方式加载，网站使用HTTP/2.0以及Google的pagespeed插件， **人品好的情况下** 在2s内可以加载完毕 ~~+1s x2~~ ；~~如果哪天不存在的墙突然发飙让加载速度突破10s，我也无能为力~~
  ![+2s](http://i2.piimg.com/4851/222f37f8e7718965.png)

* 安全

  SQL查询全部使用PDO的prepare，理论上防所有注入

### 进度

#### 已完成

##### 前台

* 报名

* 查询

* 意见反馈

##### 后台

* 登录

* 数据查询

* 筛选、排序

* 导出Excel

* 后台用户个人中心（个人信息、修改密码、忘记密码）

* 邮件系统 `RoundCube` (不包含在代码中)

* 编辑地点

* 手动分配时间和自动分配时间（基本完成）

#### 未完成

##### 前台

* 公告

* 志愿者公布

* 工时查询

* 关于（可指向Github）

##### 后台

* 公告

* 工时

* 调换人/地点

* 系统全局设置

* 等等等等

### 展望

* 赶快报销服务器租金...

* //TODO

### 感谢

* [Twitter](https://twitter.com/)的`Bootstrap`，遵循`MIT`协议

* [John Resig](https://jquery.org/)的`jQuery`，遵循`MIT`协议

* [FezVrasta](https://github.com/FezVrasta)的`bootstrap-material-design`，遵循`MIT`协议

* [toopay](https://github.com/toopay/bootstrap-markdown)的`bootstrap-markdown`，遵循`Apache`协议的2.0版本

* [Gregwar](https://github.com/Gregwar/Captcha)的`Captcha`，遵循`MIT`协议

* [Eonasdan](https://github.com/Eonasdan/bootstrap-datetimepicker)的`bootstrap-datetimepicker`，遵循`MIT`协议

* [Github](https://github.com)提供的免费代码仓库和`Atom`编辑器

***

提示：

1. 首次使用请执行`initial.sql`，并更改`to_pdo.php`中的本地数据库的账号密码，随后便可以使用默认用户admin密码12345678登录

2. 使用老人机版本时请参照`wap/README.txt`

3. 实际使用时请打开`admin/addToken.js`和`admin/isLoggedIn.php`，并将里面的`hostnames`数组改为自己的主机，注意仿照格式

4. 建议在系统的`php.ini`中更改`session.cookie_httponly = true`


***

我们热衷于开源，不希望固步自封，欢迎大家提意见、挑漏洞。

本项目使用`GPLv3`协议。详见`LICENSE`文件。
