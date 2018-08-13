<?php

function runningRowAverage3($A)
{  //assume first column is the legend
    $nrows=count($A);
	$ncols=count($A[0]);
	$data=array();	
	for($i=0;$i<$nrows;$i++)
	{
	 $data[$i]=array();
	}
for($i=1; $i< count($A)-1; $i++)
	for($j=1;$j<$ncols;$j++)
    {
	 $data[$i][$j] = ($A[$i-1][$j]+$A[$i][$j]+$A[$i+1][$j])/3.00;
    }

for($i=0; $i< count($A); $i++)
{
	$data[$i][0]=$A[$i][0];
}
	for($j=0;$j<$ncols;$j++)
    {
	 $data[0][$j] = $A[0][$j];
	 $data[$nrows-1][$j] = $A[$nrows-1][$j];
    }

return $data;
}

function get10($q)
{
 $M=getTableData($q);
 return  $M[1][0];
}


function readMatrix($file)
{
$handle = fopen($file, "r");
$data =array();
$userinfo = fscanf($handle, "%d %d\n");
list ($m, $n) = $userinfo;

  
for($i=0;$i<$m;$i++)
{
  $row=getLineRow($handle,$n);
  $data[]=$row;
}
fclose($handle);
return $data;
}

function readDosimetryData($file,&$fields,&$depths)
{
$handle = fopen($file, "r");
$header=getLineRow($handle,1);
$matrixsize= getLineRow($handle,1);
$n=$matrixsize[0];
$m=$matrixsize[1];
$fields=getLineRow($handle,1);

$data =array();

for($i=0;$i<$m;$i++)
{
  $row=array();
  $row=getLineRow($handle,$n);
  $data[]=$row;
}
fclose($handle);
$depths=array();
for($i=0;$i<$m;$i++)
	$depths[$i]=$data[$i][0];

$data1 = array();
for($i=0;$i<$m;$i++)
{
  $row1=array();
  for($j=0;$j<$n;$j++)
  $row1[$j]=$data[$i][$j+1];
  $data1[]=$row1;
}

return $data1;
}



function getLineRow($handle,$minlength)
{
	$row=array();
    $userinfo = fgets($handle,400);

	//echo $userinfo; echo "<br>";
	$pieces = preg_split("/[\s,]+/",$userinfo);//explode(" ", trim($userinfo));
	
	 
	if(count($pieces)<$minlength)return ;//getLineRow($handle,$minlength);
	$cnt=0;
	for($j=0;$j<count($pieces);$j++)
	{
		$test=trim($pieces[$j]);
		//echo $test; echo "<br>";
       
			$row[$cnt]=$test;
			$cnt++;
	    
		
	}
	return $row;
}


function boldColumn(&$M,$j0)
{
 for($i=0;$i<count($M);$i++)
  $M[$i][$j0]= "<b>" . $M[$i][$j0] .  "</b>";
}

function getColumn($M,$j0)
{
 $c=array();
 for($i=0;$i<count($M);$i++)
  $c[$i]=$M[$i][$j0];
 return $c;
}

function getRow($M,$j0)
{
 $c=array();
 for($i=0;$i<count($M[0]);$i++)
  $c[$i]=$M[$j0][$i];//row j
 return $c;
}

function decode($c,$n)
{
 $M=array();
 $row=array();
 $M[] = array("Sm","Rx","Cn","Pl","Rv","Ph","pM","pP");
 
 for($i=1;$i<count($c);$i++)
 {
  for($j=0;$j<$n;$j++)
  {
  $row[$j] = ($c[$i] & pow(2,$j))/pow(2,$j);
  if($row[$j]==1)$row[$j]="<img src = 'check.png' width=10 />";
  else if($row[$j]==0)$row[$j]="<img src = 'cross.png' width=7 />";
  }
  $M[] =$row;
 }
return $M;
}

function appendColumns($M, $M1)
{
 $N=array();
 $nrows=count($M);
 $ncols=count($M[0]);
 $ncols1=count($M1[0]);
 
 for($r=0;$r<$nrows;$r++)
 {
  $row=array();
  for($i=0;$i<$ncols;$i++){$row[$i]=$M[$r][$i]; }
  for($i=0;$i<$ncols1;$i++)$row[$i+$ncols]=$M1[$r][$i];
  $N[]=$row; //append a row
 }

 return $N;
}

function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}

function getTableData($querytext)
 {
  $M=array();
 $con=connectDB();
$result=mysqli_query($con,$querytext);

 $rowCount=mysqli_num_rows($result); 
  $nfields = mysqli_num_fields($result);
  for($nf=0;$nf<$nfields;$nf++)
   $header[$nf] = mysqli_field_name($result,$nf);
  $M[]=$header;

  //draw table body
 for($i=0; $i<$rowCount ;$i++) 
 {
   for($n=0;$n<count($header);$n++)
   $entry[$n]=mysqli_result($result,$i,$header[$n]);
	 
	$M[]=$entry;
 }
 mysqli_close($con);
 return $M;
 }
 
 function getSingleEntry($qu)
 {
   $M=getTableData($qu);
   if(count($M)>1) $mm=$M[1][0];
   return $mm;
 }
 
 function getTableDataForDateRange($queryselect, $event, $d1, $d2, $group,$order)
 {
  $or="";
  $gr="";
   echo strlen($group);
  if(strlen($group)>0)$gr="group by $group";
  if(strlen($order)>0)$or="order by $order";
  $queryadd = "$queryselect and $event >= '$d1' and $event <= '$d2' $gr $or";
   echo $queryadd;
  return getTableData($queryadd);
 }
 
function getCellStyles($M, $key, $color, $fieldkey)
{
  return getCellStyles1($M,$key,$color,$fieldkey,"background");
}

function getCellStyles1($M, $key, $color, $fieldkey, $what)
 {
  $ss=array();
  for($id=0; $id<count($M[0]); $id++) 
    if(stristr($M[0][$id],$fieldkey))break; //get column that matches key
	
 for($i=0; $i<count($M) ;$i++) 
 {
  $row=array();
  for($j=0;$j<count($M[0]);$j++)
  { 
   $col="";
   for($k=0;$k<count($key);$k++)
     if(stristr($M[$i][$id],$key[$k]))
	   {
	    $col="";//"$what: $color[$k]";
	   }
	 $row[]=$col;
  }
   $ss[]  = $row;
  }
 return $ss;
}

function displayTableDataWithCellStyles($M, $st)
 {
  $tags = "class='sample' id='mytable' class='tablesorter'";
  displayTableDataWithCellStylesTags($M, $st, $tags);
}



function displayTableDataWithCellStylesTags($M, $st,$tags)
 {
  echo "<table $tags>"; echo "\n";
  echo "<thead>"; echo "\n";
    echo "<tr>";
	for($i=0; $i<count($M[0]) ;$i++)
    {
	 echo "<th>" ; print_r($M[0][$i]);  echo "</th>";
	}		
	 echo "</tr>";
     echo "</thead>"; echo "\n";
  
  echo "<tbody>"; echo "\n";
 for($i=1; $i<count($M) ;$i++) 
 {
   echo "<tr>";
    for($n=0;$n<count($M[0]);$n++)
    {
	  echo "<td style = ' " ;  print_r($st[$i][$n]);  echo "'  >" ; 
  	  print_r($M[$i][$n]);  echo "</td>";
	}
   echo "</tr>";
   echo "\n";
 }
  echo "</tbody>"; echo "\n"; echo "</table>";echo "\n";
}

function displayTableDataWithColor($M, $key, $color, $fieldkey)
 {
  for($id=0; $id<count($M[0]) ;$id++) 
    if(stristr($M[0][$id],$fieldkey))break;; //get column that matches key
	
  $bg="";
  echo "<table class='sample'>";
 for($i=0; $i<count($M) ;$i++) 
 {
   for($k=0;$k<count($key);$k++)if(stristr($M[$i][$id],$key[$k]))$bg=$color[$k];
   echo "<tr>";
   echo "<td style = 'background: $bg'>" ; print_r($M[$i][0]);  echo "</td>";
 
   for($n=1;$n<count($M[0]);$n++)
    {echo "<td style = 'background: $bg'>" ; print_r($M[$i][$n]);  echo "</td>";}
   echo "</tr>";
 }
  echo "</table>";
}

function displayTableDataWithColorNoHeader($M, $key, $color, $fieldkey,$styling)
 {
  for($id=0; $id<count($M[0]) ;$id++) 
    if(stristr($M[0][$id],$fieldkey))break;; //get column that matches key
	
  $bg="";
  echo "<table $styling>";
 for($i=1; $i<count($M) ;$i++) 
 {
   for($k=0;$k<count($key);$k++)if(stristr($M[$i][$id],$key[$k]))$bg=$color[$k];
   echo "<tr>";
   echo "<td style = 'background: $bg; white-space: nowrap;'>" ; print_r($M[$i][0]);  echo "</td>";
 
   for($n=1;$n<count($M[0]);$n++)
    {
	 if($n==1)$whitespace='white-space: nowrap';else $whitespace='';
	  echo "<td style= '$whitespace'>" ; print_r($M[$i][$n]);  echo "</td>";}
   echo "</tr>";
 }
  echo "</table>";
}

function displayTableData($M)
{
 displayTableDataWithStyle($M, "style = 'sample'");
}


function displayTableDataWithStyle($M, $style)
 {
  echo "<table $style>"; echo "\n";
  echo "<thead>"; echo "\n";
    echo "<tr>";
	for($i=0; $i<count($M[0]) ;$i++)
    {
	 echo "<th>" ; print_r($M[0][$i]);  echo "</th>";
	}		
	 echo "</tr>";
     echo "</thead>"; echo "\n";
  
  echo "<tbody>"; echo "\n";
 for($i=1; $i<count($M) ;$i++) 
 {
   echo "<tr>";
   for($n=0;$n<count($M[0]);$n++)
    {echo "<td>" ; print_r($M[$i][$n]);  echo "</td>";}
   echo "</tr>";
   echo "\n";
 }
  echo "</tbody>"; echo "\n"; echo "</table>";echo "\n";
}
function displayTableDataWithStyleNoHeader($M, $style)
 {
  echo "<table $style>";
 for($i=1; $i<count($M) ;$i++) 
 {
   echo "<tr>";
 
   for($n=0;$n<count($M[0]);$n++)
    {echo "<td>" ; print_r($M[$i][$n]);  echo "</td>";}
   echo "</tr>";
 }
  echo "</table>";
}

function displayTableDataNoTopRow(&$M, $style)
 {
  echo "<table class='$style'>";
 for($i=1; $i<count($M) ;$i++) 
 {
   echo "<tr>";
 
   for($n=0;$n<count($M[0]);$n++)
    {echo "<td>" ; print_r($M[$i][$n]);  echo "</td>";}
   echo "</tr>";
 }
  echo "</table>";
}


function displayColumnDataWithStyle($M, $style)
 {
  echo "<table $style>"; echo "\n";
  
 for($i=0; $i<count($M) ;$i++) 
 {
   echo "<tr>";
   echo "<td>"; print_r($M[$i]); echo "</td>";
   echo "</tr>";
   echo "\n";
 }
 echo "\n";
 echo "</table>";echo "\n";
}

function getTableDataAsString($M, $col1, $col2)
{
	//coalesce date, alternate colors.
 $str="";
 for($i=1; $i<count($M) ;$i++) 
 {
   if($i%2==0)$tag1="<b style = 'color: $col1'>";
   if($i%2==1)$tag1="<b style = 'color: $col2'>";
   $tag2="</b>";
   $str=$str.$tag1;
   for($n=0;$n<count($M[0]);$n++)
    {$str = $str.$M[$i][$n];
     $str = $str." ";
    }
    $str=$str.$tag2;
 }
 return $str;
}

function getTableDataAsLines($M, $col1, $col2)
{
	//coalesce date, alternate colors.
 $str="";
 for($i=1; $i<count($M) ;$i++) 
 {
   if($i%2==0)$tag1="<p style = 'color: $col1'; margin-bottom: 100px>";
   if($i%2==1)$tag1="<p style = 'color: $col2'>";
   $tag2="</p><br>";
   $str=$str.$tag1;
   for($n=0;$n<count($M[0]);$n++)
    {
	 if($n==0)$str=$str."<b style='color: gold'>";
	 $str = $str.$M[$i][$n];
     $str = $str." ";
	 if($n==0)$str=$str."</b>";
    }
    $str=$str.$tag2;
 }
 return $str;
}


function extractColumns(&$M0,$mask)
 {
  $M=array();
  $rowCount=count($M0);
  $nfields = count($M0[0]);

  //draw table body
 for($i=0; $i<$rowCount ;$i++) 
  { 
    $entry=array();
    for($n=0;$n<$nfields;$n++)
     if($mask[$n]==1)
       $entry[]=$M0[$i][$n];
	 
	 $M[]=$entry;
  }
 return $M;
}

function concatColumns(&$M,$c1,$c2,$sep)
 {
  $rowCount=count($M);
  $nfields = count($M[0]);

  //draw table body
 for($i=0; $i<$rowCount ;$i++) 
  { 
    $M[$i][$c1] = $M[$i][$c1] . ($sep . $M[$i][$c2]);
  }
}



function getColorByStatus($mystring, $mystring2)
{
      if(stristr($mystring,"replanning")) 	$bgcolor="#FF00FF";		  
		  else if(stristr($mystring,"planning")) 	$bgcolor="#ac8dd7";	
            else if(stristr($mystring,"pending"))		 $bgcolor="#EEEEFF";
            else if(stristr($mystring,"ready")) 		 $bgcolor="yellowgreen";
            else if(stristr($mystring,"completed"))	 $bgcolor="gray";
			else if(stristr($mystring,"Rx Incomplete")) $bgcolor="red";
			else if(stristr($mystring,"R&V Entry")) 	 $bgcolor="gold";
			else if(stristr($mystring,"EMR Entry")) 	 $bgcolor="gold";
			else if(stristr($mystring,"QA")) 	 $bgcolor="skyblue";
			else if(stristr($mystring,"signature"))	 $bgcolor="#FBA16C";
            else if(stristr($mystring,"physics")) 		 $bgcolor="#55DDFF";
			else if(stristr($mystring,"simulated"))  	 $bgcolor="yellow";
	     	else if(stristr($mystring,"Premature"))  	 $bgcolor="#FF5555";
			else if(stristr($mystring,"review")) $bgcolor ="steelblue";
			else if(stristr($mystring,"Implant"))  $bgcolor="pink";
			else if(stristr($mystring,"PostPlan"))  $bgcolor="purple";
            else   $bgcolor="lavender"; 


return $bgcolor;

}

function expandDate($d)
{
$dd=$d;
if(stristr($d,"0000"))return $dd;
$atoms = explode('-',$d);
$year = $atoms[0];
$month = $atoms[1];
$day = $atoms[2];
if ($atoms[2]>0)$dd= date('D. m-d-Y', strtotime($d)); 
return $dd;
}


function generateHdrDecayTable($ref_activity,$ref_dwelltime,$A0,$date0,$ndays)
{
//Ir-192 source only
$date = new DateTime($date0);
//$nn=$_POST['fx'];
$M=array();
$N=array();
$N[0]="Elapsed";
$N[1]="Treatment Date";
$N[2]="Activity [Ci]";
$N[3]="Dwell time [s]";
$N[4]="Fraction #/Signature";
$M[]=$N;
//$fp = fopen('hdr/dates.txt', 'w');
//$date1='2014-08-04';
for($i=0;$i<$ndays;$i++)
{
 $dds = $date->format("D: M j,  Y"); 
 $A = $A0*pow(0.5,$i/73.83);
$date->modify('+1 day');
$N[0]=$i;
$N[1]=$dds;
$N[2]=sprintf("%.3f",$A);
$N[3]=sprintf("%.1f",$ref_dwelltime*$ref_activity/$A);
$N[4]="";
$M[]=$N;
}
return $M;

}

function commonDosimetry($querytext, $mask, $chkflag, $colorwhat)
{
$key1=array("EMR", "replanning",	"planning",		"pending",	"ready",
            "completed", 	"Rx Incomplete", "R&V", 	"signature", 
		    "physics",   	"simulated",     "Premature", "review",
			"PostImplant",	"PreImplant", "Treatment","PostPlan","QA approval");
$color1=array("gold","#FF00FF",	"#c276fd",	"#eeeeff",		"#88FF00",
              "gray",   	"red",   	"gold","#FBA16C",
			  "#66EEFF",	"yellow",	"#FF5555", "#22AA22",
			  "pink","pink", "#9999AA","#ac8dd6","skyblue");

$MMM=getTableData($querytext);
//boldColumn($MMM,2);
concatColumns($MMM,2,3,', ');


$checklist = getColumn($MMM,$chkflag);
if($chkflag==15)$check6 = decode($checklist,8);
$MMM=extractColumns($MMM,$mask);
if($chkflag==15)$MMM=appendColumns($MMM,$check6);
$ss=getCellStyles1($MMM,$key1,$color1,"status",$colorwhat);
//for($i=0;$i<count($ss);$i++)$ss[$i][0]="background: #445566; color: cornsilk";
//for($i=0;$i<count($ss[0]);$i++)$ss[0][$i]="background: #445566; color: cornsilk";
for($i=1;$i<count($MMM);$i++) $MMM[$i][3]=expandDate($MMM[$i][3]);

echo "<br>";
displayTableDataWithCellStyles($MMM,$ss);

}


function commonEquipment($querytext, $mask, $chkflag, $colorwhat)
{
$key1=array("EMR", "replanning",	"planning",		"pending",	"ready",
            "completed", 	"Rx Incomplete", "R&V", 	"signature", 
		    "physics",   	"simulated",     "Premature", "review",
			"PostImplant",	"PreImplant", "Treatment","PostPlan","QA approval");
$color1=array("gold","#FF00FF",	"#c276fd",	"#eeeeff",		"#88FF00",
              "gray",   	"red",   	"gold","#FBA16C",
			  "#66EEFF",	"yellow",	"#FF5555", "#22AA22",
			  "pink","pink", "#9999AA","#ac8dd6","skyblue");

$MMM=getTableData($querytext);


$ss=getCellStyles($MMM,$key1,$color1,"id",$colorwhat);



echo "<br>";
displayTableDataWithCellStyles($MMM,$ss);

}



function commonDosimetry2($querytext, $mask, $chkflag, $colorwhat)
{
$MMM=getTableData($querytext);
boldColumn($MMM,2);
concatColumns($MMM,2,3,', ');
$checklist = getColumn($MMM,$chkflag);
if($chkflag==15)$check6 = decode($checklist,8);
$MMM=extractColumns($MMM,$mask);
for($i=1;$i<count($MMM);$i++) $MMM[$i][3]=expandDate($MMM[$i][3]);

echo "<br>";
displayTableData($MMM);

}

//SVG
function circle($fh,$x, $y, $r,$color)
 {
  $st = "<circle cx='$x' cy='$y' r='$r' fill='$color' stroke = '$color' />" ;
   fwrite($fh,$st);
   return $st;
 }
 
 function rect($fh,$x, $y, $w, $h,$color)
 {
  $st = "<rect x='$x' y='$y' height='$h' width='$w' fill='$color' stroke = '$color' />" ;
   fwrite($fh,$st);
   return $st;
 }
 
 function line($fh,$x1, $y1, $x2, $y2, $wd, $dash,$color)
 {
  $st = "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' style='stroke:$color; stroke-width: $wd; stroke-dasharray: $dash;' />" ;
  fwrite($fh,$st);
   return $st;
 }
 
 function text($fh,$x1, $y1,$text,$color)
 {
  $st = "<text x='$x1' y='$y1' 
                 style=
                'font-family: Calibri;
                 font-size  : 16;
				 fill: $color;'> 
				 $text </text>" ;
  fwrite($fh,$st);
   return $st;
 }
 
 
function connectDB()
{
 // mysql_connect("10.110.57.159",$username,$password);
 //@mysql_select_db($database) or die( "Unable to select database");
 $db = new mysqli('10.110.57.159', 'root', '', 'stagnes');
 return $db;

}


function mainlinks()
{
 $add="http://10.110.57.159/exemples/";
 $style="float: left; margin: 2px; color: gold;  padding: 2px; border: 2px solid red;display: inline-block; white-space: nowrap;";
 
echo " <table> <td style='$style'> <a style='color: gold' href = '$add/index.html'> Main  </a> </td>
<td style='$style'> <a style='color: gold' href = '$add/Dosimetry/dosimetry.php'> Edit </a> </td>
<td style='$style'> <a style='color: gold' href = '$add/Dosimetry/dosimetry-refresh.php'> Live  </a>   </a> </td>
<td style='$style'><a style='color: gold' href = '$add/Events/generalboard.php'> Events  </a>   </a> </td>
<td style='$style'><a  style='color: gold'href = '$add/EOT/updatestatus.php'> EOT </a>   </a> </td>
<td style='$style'><a style='color: gold' href = '$add/Peer/patientnotes.php'> Peer  </a>   </a> </td>
<td style='$style'><a style='color: gold' href = '$add/mm.php'> M&M  </a>   </a> </td>
<td style='$style'><a style='color: gold' href = '$add/Peer/retrospectivepeer.php'> Retro  </a>   </a> </td>
<td style='$style'><a style='color: gold' href = '$add/Equipment/equipment.php'> Equipment  </a>   </a> </td>
 </table>
";
}

function mainlinksWithStyle($style)
{
 $add="http://10.110.57.159/exemples/";
 //$style="float: left; margin: 2px; color: gold;  padding: 2px; border: 2px solid red;display: inline-block; white-space: nowrap;";
 
echo " <table> <td > <a style='$style' href = '$add/index.html'> MAIN  </a> </td>
<td ><a style='$style' href = '$add/Dosimetry/dosimetry.php'> EDIT </a> </td>
<td><a style='$style' href = '$add/Dosimetry/dosimetry-refresh.php'> LIVE  </a>   </a> </td>
<td><a style='$style' href = '$add/Events/generalboard.php'> EVENTS  </a>   </a> </td>
<td><a style='$style'href = '$add/EOT/updatestatus.php'> EOT </a>   </a> </td>
<td><a style='$style' href = '$add/Peer/patientnotes.php'> PEER  </a>   </a> </td>
<td><a style='$style' href = '$add/Peer/retrospectivepeer.php'> RETRO </a>   </a> </td>
<td><a  style='$style'href = '$add/Calender/Calender.php'> Calender </a>   </a> </td>
 </table>
";
}

function mainlinks2()
{
 $add="http://10.110.57.159/exemples/";
 $style="font-size: 8pt; float: left; margin: 2px; color: black;  padding: 2px; border: 2px solid yellowgreen;display: inline-block; white-space: nowrap;";
 
echo " <table> <td style='$style'> <a style='color: black' href = '$add/index.html'> <span>Main <span>  </a> </td>
<td style='$style'> <a style='color: black' href = '$add/Dosimetry/dosimetry.php'> Edit  </a> </td>
<td style='$style'> <a style='color: black' href = '$add/Dosimetry/dosimetry-refresh.php'> Live  </a>   </a> </td>
<td style='$style'><a style='color: black' href = '$add/Events/generalboard.php'> Events  </a>   </a> </td>
<td style='$style'><a  style='color: black'href = '$add/EOT/updatestatus.php'> EOT </a>   </a> </td>
<td style='$style'><a style='color: black' href = '$add/Peer/patientnotes.php'> Peer  </a>   </a> </td> 
<td style='$style'><a  style='color: black' href = '$add/Peer/mm.php'> M&M </a>   </a> </td>
<td style='$style'><a  style='color: black' href = '$add/Peer/retrospectivepeer.php'> Retro </a>   </a> </td>
<td style='$style'><a  style='color: black' href = '$add/Equipment/equipment.php'> Equipment </a>   </a> </td>
<td style='$style'><a  style='color: black' href = '$add/Calender/Calender.php'> Caldender </a>   </a> </td>
</table>
";
}

function mainlinks3()
{
 $add="http://10.110.57.159/exemples/";
 $style="font-size: 8pt; float: left; margin: 2px;  padding: 2px; border: 2px solid brown;display: inline-block; white-space: nowrap;";
 
echo " <table> <td style='$style'> <a style='color: gray' href = '$add/index.html'> Main   </a> </td>
<td style='$style'> <a style='color: gray' href = '$add/Dosimetry/dosimetry.php'> Edit  </a> </td>
<td style='$style'> <a style='color: gray' href = '$add/Dosimetry/dosimetry-refresh.php'> Live  </a>   </a> </td>
<td style='$style'><a style='color: gray' href = '$add/Events/generalboard.php'> Events  </a>   </a> </td>
<td style='$style'><a style='color: gray'href = '$add/EOT/updatestatus.php'> EOT </a>   </a> </td>
<td style='$style'><a style='color: gray' href = '$add/Peer/patientnotes.php'> Peer </a>   </a> </td> 
<td style='$style'><a style='color: gray' href = '$add/Peer/mm.php'> M&M  </a>   </a> </td> 
<td style='$style'><a style='color: gray' href = '$add/Peer/retrospectivepeer.php'> Retro  </a>   </a> </td> 
<td style='$style'><a style='color: gray' href = '$add/Equipment/equipment.php'> Equipment  </a>   </a> </td> 
<td style='$style'><a style='color: gray' href = '$add/Calender/calender.php'> Calender  </a>   </a> </td> 

</table>
";
}

?>