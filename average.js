function runningRowAverage3(A)
{  //assume first column is the legend, 3 point running average
    var nrows=A.length;
	var ncols=A[0].length;
	var data=[];	
	for(var i=0;i<nrows;i++)
	{
	 data[i]=[];
	}
for(var i=1; i< A.length-1; i++)
	for(var j=1;j<ncols;j++)
    {
	 data[i][j] = parseFloat(A[i-1][j]) + parseFloat(A[i][j]) + parseFloat(A[i+1][j]);
	 data[i][j]*=0.333333;
    }

for(var i=0; i< A.length; i++)
{
	data[i][0]=A[i][0];
}
	for(var j=0;j<ncols;j++)
    {
	 data[0][j] = A[0][j];
	 data[nrows-1][j] = A[nrows-1][j];
    }

return data;
}
