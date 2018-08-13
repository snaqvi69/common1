

function plotLineGraph(ctx,M,col1,col2,sx,sy)
{
  
  var px0=0;
  var px1=0;
  var py0=0;
  var py1=1;
  
 for(i=0;i<M.length-1;i++)
 {
 if(col1<0){px0=i;px1=i+1;}
 py0=M[i][col2];
 py1=M[i+1][col2];
 ctx.beginPath();
  ctx.moveTo(px0*sx,py0*sy);
  ctx.lineTo(px1*sx,py1*sy);
  ctx.stroke();
 } 
}
function plotPointGraph(ctx,M,col1,col2, r0,sx,sy)
{
  var px0=0;
  var px1=0;
  var py0=0;
  var py1=0;
 for(i=0;i<M.length-1;i++)
 {
 if(col1<0){px0=i;px1=i+1;}
 py0=M[i][col2];
 py1=M[i+1][col2];
  ctx.beginPath();
  ctx.moveTo(px0*sx,py0*sy);
  ctx.arc(px1*sx,py1*sy, r0, 0, 2 * Math.PI, false);
  ctx.stroke();
 }  
}


function plotGrid(ctx,x0,x1,y0,y1,dx,dy,sx,sy)
{

for(x=x0;x<=x1;x+=dx)
{
 ctx.beginPath();
 ctx.moveTo(x*sx,y0*sy);
 ctx.lineTo(x*sx,y1*sy);
 ctx.stroke();
} 

for(y=y0;y<=y1;y+=dy)
{
 ctx.beginPath();
 ctx.moveTo(x0*sx,y*sy);
 ctx.lineTo(x1*sx,y*sy);
 ctx.stroke();
} 

	
	
}


