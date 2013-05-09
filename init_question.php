<?php

/**
 * 题目管理
 * @author suhuiling
 * @version 1
 * @package ost_sys
 */
?>

<style type="text/css">
<!--
body{
background-color:#ECF5FF;
font-size:12px;}
.inp {
margin-left:15px;
}
.su{
margin-left:100px;}
.border2 {
	border: 1px solid #D5E4F4;
		border-bottom: 1px solid #ccc;
		height:18px;

}
.font14 {
	font-size: 14px;
	font-family: "Tahoma";
}
input {
	font-family: "Verdana", "Arial", "Helvetica", "sans-serif";
	font-size: 12px;
	border: 1px solid #999999;
	height: 20px;

}
.main{
width:600px;
	border: 1px solid #CCCCCC;
	background:#FFFFFF;
	margin-left:200px;
	margin-top:10px;
	text-align:center;
	padding-top:30px;
}
.notice{
	color:#FF0033;
	font-size: 14px;
	font-weight: bold;
}
.zt{
height:25px;}

-->
 </style>
<script laguage="javascript">
<!--
function form1_onsubmit()
{
if (document.form1.question.value=="")
    {
      alert("请输入问题！")
      document.form1.question.focus()
      return false
     }
else if(document.form1.answer.value=="")
    { 
      alert("请选择答案！")
      document.form1.answer.focus()
      return false
     }
else if(document.form1.choice_a.value=="")
    { 
      alert("至少要有两个选项内容！")
      document.form1.choice_a.focus()
      return false
     }
}
-->
</script>
  <div class="main"> 
 

<form id="form1" name="form1" method="post" action="post2mysql.php?action=choice" onsubmit="return form1_onsubmit()">
 
 </label> 

   <p class="inp">问题：
 <input name="question" type="text" class="border2" size="60" maxlength="100"  />
 </p> 
 <p >
 <p> <span class="zt">主题图：<input name="img" type="text" class="border2" size="20" maxlength="100"  /> 
 </span><iframe   name="frame"   frameborder="0"   width="180px"   height="20px"   scrolling="no"   src="../inc_upload.php"   >   
  </iframe>
 </p>
 
  <p class="inp">选项A：
  <input name="choice_a" type="text" class="border2" size="40" maxlength="100" />
  </p>

   <p class="inp">选项B：
  <input name="choice_b" type="text" class="border2" size="40" maxlength="100" />
  </p>
 
   <p class="inp">选项C：
  <input name="choice_c" type="text" class="border2" size="40" maxlength="100" />
  </p>
  
   <p class="inp">选项D：
  <input name="choice_d" type="text" class="border2" size="40" maxlength="100" />
  </p>
 
<p class="inp"> 正确答案： 
  A:<input name="answer" type="radio" value="A" />　　B:<input name="answer" type="radio" value="B" />　　C:<input name="answer" type="radio" value="C" />　　D:<input name="answer" type="radio" value="D" />
  </p>
 
 <p> <br />
 <input type="submit" name="Submit" value="添　加　试　题"  class="font14"/>
 </p>


</form>

</div>
