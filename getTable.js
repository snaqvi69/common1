function isIE () {
            var myNav = navigator.userAgent.toLowerCase();
            return (myNav.indexOf('msie') != -1 || myNav.indexOf('trident') != -1) ? true : false;
        }
		
 function isFirefox () {
            var myNav = navigator.userAgent.toLowerCase();
            return (myNav.indexOf('firefox') != -1 || myNav.indexOf('trident') != -1) ? true : false;
        }


		
		
function getTable(table1)
{
var table = document.getElementById(table1);
var nrows=table.rows.length;
var ncols=table.rows[0].cells.length;
var M=new Array(nrows);
for(var i=0;i<nrows;i++){M[i]= new Array(ncols);}
for(var i=0;i<nrows;i++)
 for(var j=0;j<ncols;j++) M[i][j]=table.rows[i].cells[j].innerHTML;

return M;

}
 

function isDateToday(sim)
{
  var d= new Date;
  var st = getDateString(d,"-");
  var year=d.getFullYear();
  var month=d.getMonth();
  var date=d.getDate();

  var sam=sim.split("-");
  var mm=Number(sam[1])-1;
  var dd=Number(sam[2]);
  var yy=Number(sam[0]);
   if(Number(yy)==Number(year) && Number(mm)==Number(month) && Number(dd)==Number(date)) return true;
	
}

 
function getDateString(d, sep)
{
//d is date object in JS
var year=d.getFullYear();
var date = d.getDate();
var month= d.getMonth()+1;
var dateString=date.toString();
var monthString=month.toString();
if(dateString.length==1)dateString= "0" + dateString;
if(monthString.length==1)monthString="0" + monthString;
var st = monthString + sep + dateString + sep + year.toString();
return st;

}


function getFirstRow(matrix,columnindex,searchword)
{
var first=matrix.length-1;
for(var m=0;m<matrix.length;m++)
  if(matrix[m][columnindex].search(searchword)>=0)
  {
   if(m<first)first=m;
  }
return first;
}


function getLastRow(matrix,columnindex,searchword)
{
var last=0;
for(var m=0;m<matrix.length;m++)
  if(matrix[m][columnindex].search(searchword)>=0)
  {
   if(m>last)last=m;
  }
return last;
}

function getRowIndex(M,tag)
{
var i=0;
for(i=0;i<M[0].length;i++)
 {
    if(M[0][i].search(tag)>=0)break;
 }
 return i;
}



function ajaxObject()
{
 if (window.XMLHttpRequest) { xhr = new XMLHttpRequest(); } 
  else if (window.ActiveXObject)
  {
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  }  
 return xhr;
}


function ajax(document,url, custom, result_div)  
{  

var xhr = ajaxObject();

  var data = document.getElementById(custom).value;  

     xhr.open("GET", url+data, false);   
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                    
     xhr.send(data);  
     xhr.onreadystatechange = display_data;  
    function display_data()
	{  
       if (xhr.readyState == 4) {  
        if (xhr.status == 200) {  
         //alert(xhr.responseText);        
        //document.getElementById(result_div).innerHTML = xhr.responseText;  
        }  
       }  
    }  
var newdiv = document.createElement("div");
newdiv.innerHTML = xhr.responseText;
var container = document.getElementById(result_div);
document.getElementById(result_div).innerHTML="";
while (container.firstChild)
  {container.removeChild(myNode.firstChild);}
   container.appendChild(newdiv);
  
} 



