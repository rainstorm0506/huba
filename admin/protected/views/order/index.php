<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>虎吧管理平台</title>
    <link rel="stylesheet" rev="stylesheet" href="<?php echo A_CSS_URL;?>style.css" type="text/css" media="all"/>


    <script language="JavaScript" type="text/javascript">
        function tishi() {
            var a = confirm('数据库中保存有该人员基本信息，您可以修改或保留该信息。');
            if (a != true)return false;
            window.open("冲突页.htm", "", "depended=0,alwaysRaised=1,width=800,height=400,location=0,menubar=0,resizable=0,scrollbars=0,status=0,toolbar=0");
        }

        function check() {
            document.getElementById("aa").style.display = "";
        }


        function link() {
            alert('保存成功！');
            document.getElementById("fom").action = "xuqiumingxi.htm";
            document.getElementById("fom").submit();
        }


    </script>
    <style type="text/css">


        body {
            margin: 0;
            padding: 0;
            background-color: white;
        }

        .atten {
            font-size: 12px;
            font-weight: normal;
            color: #F00;
        }

        .topBack {
            background-color: ghostwhite;
        }

        input {
            width: 15%;
            height: 25px;

        }

        select {
            width: 6%;
            height: 30px;
        }

        #checkbox {
            width: 25px;
            height: 25px;
        }

        button {
            width: 5%;
            height: 30px;
            background-color: #0099FF;
            border: none;
            color: white;
        }

        #sarch {
            width: 100%;
            height: 30px;
            margin-left: 50px;
            margin-top: 20px;
        }

        #dingBtn {
            width: 10%;
            height: 20px;
            background-color: orange;
            color: white;
            border: none;
            float: right;
            margin-right: 10px;
            margin-top: 30px;

        }

        .checkboxBtn {
            width: 15px;
            height: 15px;
        }

        #dianBtn {
            width: 8%;
            height: 30px;
            background-color: #0099FF;
            border: none;
            color: white;
        }

    </style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="fom" id="fom" target="sypost">
    <div class="MainDiv">
        <table width="99%" border="0" cellpadding="0" cellspacing="0" style="border: 2px solid #D6DEF1">
            <tr>
                <td style="width: 50%;float: left">
                    <div id="sarch">订单查询列表</div>
                </td>
                <td style="width: 50%;float: right;">
                    <button id="dingBtn">订单</button>
                </td>
            </tr>
            <tr>
                <td class="CPanel">

                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%">
                        <hr style="border: 1px solid #D6DEF1;margin-bottom: 20px;width: 100%"/>
                        <TR class="topBack" style="width: 80% ;height: 45px">
                            <TD>
                                <i></i><label>订单号：</label><input type="text"/>
                                <label>收货人：</label><input type="text"/>
                                <label>订单状态：</label>
                                <select>
                                    <option>待确定</option>
                                    <option>请选择</option>
                                    <option>请选择</option>
                                    <option>请选择</option>
                                </select>
                                <label>订单类型：</label>
                                <select>
                                    <option>请选择</option>
                                    <option>请选择</option>
                                    <option>请选择</option>
                                </select>
                                <button>搜索</button>
                                <span><a href="#">待确认</a></span>
                                <span><a href="#">待付款</a></span>
                                <span><a href="#">待发货</a></span>
                            </TD>
                        </TR>
                    </TABLE>
                    <p></p>
                    <table cellpadding="0" cellspacing="0" border="1"
                           style="width: 100%;text-align: center;border: 1px solid #D6DEF1">
                        <tr class="topBack" style="width: 100% ;height: 35px;border: 1px solid silver">
                            <td style="width: 15%;height: 30px"><input type="checkbox" class="checkboxBtn"/>订单号</td>
                            <td style="width: 9%">订单类型</td>
                            <td style="width: 10%">下单时间</td>
                            <td style="width: 15%">收货人</td>
                            <td style="width: 9%">总金额</td>
                            <td style="width: 9%">应付金额</td>
                            <td style="width: 9%">订单来源</td>
                            <td style="width: 15%">订单状态</td>
                            <td style="width: 10%">操作</td>
                            <!--<td colspan="3">订单类型</td>-->
                        </tr>
                        <tr style="height: 40px">
                            <td><input type="checkbox" class="checkboxBtn"/>20160032588</td>
                            <td>一般订单</td>
                            <td>rain<br/>
                                11-11 8:45
                            </td>
                            <td>萌萌哒[TEL-]<br/>
                                高新区xx街
                            </td>
                            <td>300.0</td>
                            <td>0.0</td>
                            <td>pc</td>
                            <td>已分单.已付款.收货确认</td>
                            <td>查看</td>
                        </tr>
                        <tr style="height: 40px">

                            <td><input type="checkbox" class="checkboxBtn"/>20160032588</td>
                            <td>一般订单</td>
                            <td>rain<br/>
                                7-22 19:15
                            </td>
                            <td>萌萌哒[TEL-]<br/>
                                高新区xx街
                            </td>
                            <td>500.0</td>
                            <td>50.0</td>
                            <td>moblie</td>
                            <td>已确认.已付款.未发送</td>
                            <td>查看</td>


                        </tr>
                        <tr style="height: 40px">
                            <td><input type="checkbox" class="checkboxBtn"/>20160032588</td>
                            <td>一般订单</td>
                            <td>rain<br/>
                                2-15 10:45
                            </td>
                            <td>萌萌哒[TEL-]<br/>
                                高新区xx街
                            </td>
                            <td>189.99</td>
                            <td>18.9</td>
                            <td>pc</td>
                            <td>已分单.已付款.收货确认</td>
                            <td>查看</td>

                        </tr>
                        <tr style="height: 40px">
                            <td><input type="checkbox" class="checkboxBtn"/>20160032588</td>
                            <td>一般订单</td>
                            <td>rain<br/>
                                10-22 17:45
                            </td>
                            <td>萌萌哒[TEL-]<br/>
                                高新区xx街
                            </td>
                            <td>0.10</td>
                            <td>0.0</td>
                            <td>pc</td>
                            <td>已确认.已付款.未发送</td>
                            <td>查看</td>

                        </tr>
                        <tr style="height: 40px">
                            <td><input type="checkbox" class="checkboxBtn"/>20160032588</td>
                            <td>一般订单</td>
                            <td>rain<br/>
                                12-12 7:45
                            </td>
                            <td>萌萌哒[TEL-]<br/>
                                高新区xx街
                            </td>
                            <td>55.59</td>
                            <td>5.59</td>
                            <td>mobile</td>
                            <td>已确认.已付款.未发货</td>
                            <td>查看</td>


                        </tr>
                        <tr style="height: 40px">
                            <td><input type="checkbox" class="checkboxBtn"/>20160032588</td>
                            <td>一般订单</td>
                            <td>rain<br/>
                                12-22 17:45
                            </td>
                            <td>萌萌哒[TEL-]<br/>
                                高新区xx街
                            </td>
                            <td>10.0</td>
                            <td>1.0</td>
                            <td>pc</td>
                            <td>未确认.未付款</td>
                            <td>查看</td>

                        </tr>
                    </table>

                </td>
            </tr>

        </TABLE>


        </td>
        </tr>


        </table>

    </div>
</form>
</body>
</html>
