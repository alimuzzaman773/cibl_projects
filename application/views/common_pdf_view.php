<html>
<head>
    <meta charset="UTF-8">
<style>

body {
    background:white;
    font-family: verdana,Arial; 
    font-size:10px; 
    margin:0 }

#content{ 
     margin:0 auto; 
     float:none; 
     width:auto 
}

.hide_in_print{display: none};

#content a { 
    font-weight:bold; 
    color:#000066; 
    text-decoration:underline 
}

h1, h2, h4, h5, h6 { page-break-after:avoid; 
    /*page-break-inside:avoid */
}
h3 {     
    margin-bottom:5px; 
    padding-bottom:0px;
    width: 100%;
    clear: both;
    font-size: 1.8em;
    text-transform: capitalize;
}

table,td,th,.tableborder,.tableborder th, .tableborder td{
    border-collapse:  collapse;
    border: 1px solid #ccc;
    padding:5px;
    text-align: left;
    vertical-align: top;
}

.tableheader, .tableheader td{
    border: none;
}

th{    
    background: #004d29;
    color:white;
    font-size: 0.8em; 
    font-weight: bold;
}

.td, td{
    font-size: 0.85em;
    padding:5px;
}
.center,#center {
 text-align: center;
}
table.no-border,.no-border td,.no-border th{
    border: 1px solid #ffffff;
}
span .small-text{
    font-size: 0.8em;
    font-weight: bold;
    color: #666666;
}
th .small-text{
    font-size: 0.8em;
    font-weight: bold;
    color: #fff;
}
.row{
    display: table;
    content: '';    
    width: 100%
}

.full-width{  
    width: 100%;
    float:left;
}

.col-md-6{  
    width: 50%;
    float:left;
}

.table{
    width: 100%
}

.hideInPrint{
    display: none;
}

.pull-right{
    float: right !important;
}

.pull-left{
    float: left !important;
}

.hide_in_print{display: none};

.bangla{
    font-family: ind_bn_1_001 !important;
}

.suttonymj{
    font-family: suttonymj
}

</style>
</head>

<body style="padding-left:10px;padding-right: 10px">
    <!--<table style="width: 100%" class="no-border">
        <tr>
            <td align="right">
                <img src="<?=ABS_SERVER_PATH.SITE_FOLDER.ASSETS_FOLDER?>images/logo.png" alt="qms" width="60px" />
            </td>
        </tr>
    </table>-->
     
        <?=$html?>        
   
</body>
</html>
