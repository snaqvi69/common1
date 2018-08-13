
function nrows(a)
{return a.length;}

function ncols(a)
{return a[0].length;}

function multiply(ma, mb)
{
  var aNumRows = ma.length, aNumCols = ma[0].length,
      bNumRows = mb.length, bNumCols = mb[0].length;
var mc = new Array(aNumRows);  // initialize array of rows
  for (var r = 0; r < aNumRows; ++r) {
    mc[r] = new Array(bNumCols); // initialize the current row
    for (var c = 0; c < bNumCols; ++c) {
      mc[r][c] = 0;             // initialize the current cell
      for (var i = 0; i < aNumCols; ++i) {
        mc[r][c] += ma[r][i] * mb[i][c];
      }
    }
  }
  return mc;
}

function multiply1(ma, mb)
{
   var aNumRows = ma.length;
   var aNumCols = ma[0].length;
   var mc = new Array(aNumRows);  // initialize array of rows
  for (var i = 0; i < aNumRows; i++) 
  {
      mc[i] = 0.0;             // initialize the current cell
      for (var j = 0; j < aNumCols; j++)
      {
        mc[i] += ma[i][j] * mb[j];
      }
    
  }
  return mc;
}

function copyMatrix(ma)
{
 var mc = new Array(ma.length);
 for(var i=0;i<ma.length;i++)
   {mc[i]= new Array(ma[0].length);}
 
 for(var i=0; i<ma.length; i++)
  for(var j=0; j<ma[0].length;j++)
   {mc[i][j]= ma[i][j];}  
   return mc;
}

function appendColumn(ma,vb)
{
 var ncol = ncols(ma);
 var mc = new Array(ma.length);
 for(var i=0;i<ma.length;i++)
   {mc[i]= new Array(ma[0].length+1);}
 
    
 for(var i=0; i<ma.length; i++)
  for(var j=0; j<ma[0].length;j++)
   {mc[i][j]= ma[i][j];}
 
 for(var i=0;i<ma.length;i++)
  {mc[i][ncol]= vb[i];}
 
  return mc;  
}

function appendRow(ma,vb)
{
 var nrow = nrows(ma);
 var mc = new Array(ma.length+1);
 for(var i=0;i<mc.length;i++)
   {mc[i]= new Array(ma[0].length);}
 
    
 for(var i=0; i<ma.length; i++)
  for(var j=0; j<ma[0].length;j++)
   {mc[i][j]= ma[i][j];}
 
 for(var j=0;j<vb.length;j++)
  {mc[nrow][j]= vb[j];}
 
  return mc;  
}


function transpose(ma)
{
 var nrow = nrows(ma);
 var ncol = ncols(ma);
 var mc = new Array(ncol);
 for(var i=0;i<mc.length;i++)
   {mc[i]= new Array(nrow);}
 
 for(var i=0; i<nrow; i++)
  for(var j=0; j<ncol;j++)
   {mc[j][i]= ma[i][j];}
  return mc;  
}

function column(ma, n)
{
  var nrow = nrows(ma);
  var mc = new Array(nrow); 
  for(var i=0;i<nrow;i++)
    mc[i]=ma[i][n];
 return mc;
}

function row(ma, n)
{
  var ncol = ncols(ma);
  var mc = new Array(ncol); 
  for(var i=0;i<ncol;i++)
    mc[i]=ma[n][i];
 return mc;
}

function subtract(ma, mb)
{
    var mc=copyMatrix(ma);
     for(var i=0;i<ma.length;i++)   
    {
        mc[i]=ma[i]-mb[i];
    }
    return mc;
}

function add(ma, mb)
{
    var mc=copyMatrix(ma);
     for(var i=0;i<ma.length;i++)   
    {
        mc[i]=ma[i]+mb[i];
    }
    return mc;
}

function magnitude(ma)
{
  var sum=0.0;
  for(var i=0;i<ma.length;i++)
      {sum += ma[i]*ma[i];}
  
    return Math.sqrt(sum);
}

function scale(ma, sx)
{
 var mc=new Array(ma.length);  
 for(var i=0;i<mc.length;i++){mc[i]=ma[i]*sx;}  
 return mc;
}

function unit(ma)
{
 var sx=magnitude(ma);
 return scale(ma,1.0/sx); 
}

function cross(va,vb)
{
 return [ va[1]*vb[2]-va[2]*vb[1],  -va[0]*vb[2]+va[2]*vb[0],   va[0]*vb[1]-va[1]*vb[0] ];
}


function setColumn(ma,n,vb)
{
    //matrix a, column n replace wioth vector b
    var mc = copyMatrix(ma);
    for(var i=0;i<ma.length;i++)
     {mc[i][n]=vb[i];}
    return mc;
}

function setRow(ma,n,vb)
{
    //matrix a, row n replace wioth vector b
    var mc = copyMatrix(ma);
    for(var j=0;j<ma[0].length;j++)
     {mc[n][j]=vb[j];}
    return mc;
}

function identity(n)
{
   var mc=new Array(n); 
   for(var i=0;i<n;i++)
   {mc[i]= new Array(n);}
   
 for(var i=0; i<n; i++)
  for(var j=0; j<n;j++)
   {if(i==j)mc[i][j]= 1;else mc[i][j]=0;}  
   return mc;
}

function translationMatrix(r)
{
  var cc0 = [1,0,0,0];
  var c1 =  [0,1,0,0];
  var c2 =  [0,0,1,0];
  var c3 =  [-r[0],-r[1],-r[2],1];
  var A1 = identity(4);
  A1=setColumn(A1,0,cc0);
  A1=setColumn(A1,1,c1);
  A1=setColumn(A1,2,c2);
  A1=setColumn(A1,3,c3);
  return A1;
    
}

function orthographicTransformMatrix()
{
  return identity(4); 
}

function perspectiveTransformMatrix(d)
{
  var A1 = identity(4);
  A1[3][2]=1.0/d;
  A1[3][3]=0;
  return A1;
}

function cameraTransformMatrix(eye,lookat,up0)
{
  var n = subtract(lookat,eye);
  var n = unit(n);
  var up = unit(up0);
  var u=cross(n,up);
  var v = cross(u,n);
  var cc0 = [u[0],v[0],n[0],0];
  var c1 = [u[1],v[1],n[1],0];
  var c2 = [u[2],v[2],n[2],0];
  var c3 = [0,0,0,1];
  var A1 = identity(4);
  A1=setColumn(A1,0,cc0);
  A1=setColumn(A1,1,c1);
  A1=setColumn(A1,2,c2);
  A1=setColumn(A1,3,c3);
  return A1;
}

function projectionMatrix(eye,lookat,up,d,persflag)
{
  var P;
  if(persflag==1)
       P=perspectiveTransformMatrix(d);
  else
       P = orthographicTransformMatrix();
  var C = cameraTransformMatrix(eye,lookat,up);
  var T = translationMatrix(eye);
  var M1=multiply(C,T);
  var M=multiply(P,M1);
  return M;
}

function projection(proj0, A1)
{
//projection 2d coordinates for 3d dvector
    var P0=[A1[0],A1[1],A1[2],1.0];
    var P1= multiply1(proj0,P0);
    return [P1[0]/P1[3],P1[1]/P1[3]];
}

function locate(datavec,a)
{
 var i=0;
 for(i=0;i<datavec.length;i++)
	 if(datavec[i]>a)break;
 return i-1;
}

function locate_c0(datamatrix,a)
{
 var i=0;
 for(i=0;i<datamatrix.length;i++)
	 if(datamatrix[i][0]>a)break;
 return i-1;
}

function interpolate1D(data, x0) 
{
//only for two column data_matrix [xdata,ydata] 
    var t;
	var y1;
	var y0;
    var i = locate_c0(data,x0);
	var m=data.length;
    if(i==-1)return data[0][1];
    else if(i==m-1)return data[m-1][1];
    y0 = data[i][1];
    y1 = data[i+1][1];
    t = (x0 - data[i][0])/(data[i+1][0]-data[i][0]);
    return (1-t)*y0 + t*y1;
}

function interpolate2D(data,col, row,x0, y0)
{
    var j=0;
	var k=0;
    var t=0;
	var u;
	var z1;
	var z2;
	var z3;
	var z4;

    j = locate(row,y0);
    k = locate(col,x0);
    z1 = data[j][k];
    z2 = data[j+1][k];
    z3 = data[j+1][k+1];
    z4 = data[j][k+1];
    u = (x0 - col[k])/(col[k+1]-col[k]);
    t = (y0 - row[j])/(row[j+1]-row[j]);
    return ((1-t)*(1-u)*z1 + t*(1-u)*z2 + t*u*z3 + (1-t)*u*z4);
}