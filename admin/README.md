# 后台tableutils使用指引

为了迎合后台重复使用表格次数多的特点，tableutils因此产生。

## 表格

### 简(chui)介(shui)

这是一个十分elegant的表格，特技到处都有（css3的transition做在全局页面上），十分养眼

表格灰白相间，保证无视觉疲劳

选中指定项仅需鼠标/触屏轻敲，选中的项目自动变黄，取消选中亦如此

### 使用方法

在页面中找好地方，`<?php require_once("mktable.php"); ?>`即可

详细插入位置可仿照manage.php

### 注意事项

表格中切勿再嵌套表格，否则事件冒泡无法阻止

## 过滤器及可选项的功能（简称filter）

### 简(chui)介(shui)

即manage.php中有颜色的按钮的上面一排 **高端大气上档次** 的筛选或者功能按钮，如果有应用筛选的话会变得超级骚哦~

### 使用方法

常用的filters已经定义在tableutils.json.php中，修改注释已经十分详细

使用对应的filter请在`window.onload`事件中加入

`mkfilters(['aaa','bbb','ccc']); //aaa,bbb,ccc均为filters定义中的id`

然后js就会自动生成一排~~超炫丽~~的按钮

### 注意事项

* 一定要有对应的处理方式

* 一定要引用表格（废话）

## 按钮

### 简(chui)介(shui)

扁平化的五彩按钮，为页面增色不少

### 使用方法

这个没做生成函数（好像没必要），所以要在每个页面的`<?php require_once("mktable.php"); ?>`下面加上

```
<center><br>
<button class="btn btn-primary" onclick="updatePageCount()"><span class="glyphicon glyphicon-refresh"></span> 刷新列表</button>
<button ... >...</button>
</center>
```

改一下中间的内容和事件即可

顺便提一句，对应的事件中可以用getSelected函数获取选中的人的信息，然后仿照passOrNot函数调用后端页面进行对应操作（这部分也可以单独抽出来，以后再说）

## 页面选择器

### 简(chui)介(shui)

表格底部用来翻页用的，省略号用在数据多的场合是不是特别人性化呢（不用省略号简直......）

### 使用方法

按钮下面添加

```
<nav class="text-center">
	<ul class="pagination" id="page1">
	</ul>
</nav>
```

即可，setPages函数会自动添上去的

---

暂时写这么多，一个人维护将近500行的tableutils、mktable等js代码还是有点吃力的
