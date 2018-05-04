/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


console.log('r/place');

var canvas = document.querySelector('canvas');
canvas.width = window.innerWidth;
canvas.heigth = window.innerHeight;
var c = canvas.getContext('2d');


var mouse = {
    x: undefined,
     y: undefined
};

var maxRadius = 40;
var minRadius = 2;


var colorArray = [
    '#092140',
    '#024959',
    '#F2C777',
    '#F24638',
    '#BF2A2A',
];
window.addEventListener('mousemove' , function(event){
    mouse.x = event.x;
    mouse.y = event.y;
    console.log(mouse);
});

function Circle(x,y,dx,dy,radius){
    this.x = x;
    this.y = y;
    this.dx = dx;
    this.dy = dy;
    this.radius = radius;
    this.minRadius = radius;
    this.color = colorArray[Math.floor(Math.random() * colorArray.length)];
    this.draw = function(){
       c.beginPath();
       c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
       c.fillStyle = this.color;
       c.fill();
    };
    this.update = function(){
          if(this.x + this.radius > innerWidth || this.x - this.radius < 0){
           this.dx = -this.dx;
       }
       if(this.y + this.radius > innerHeight || this.y - this.radius < 0){
           this.dy = - this.dy;
       }
       this.x += this.dx ;
       this.y += this.dy; 
       if(mouse.x - this.x < 50 
        && mouse.x - this.x > -50 
        && mouse.y - this.y < 50
        && mouse.y - this.y > -50
        ){
        if(this.radius < maxRadius){
            this.radius += 1;            
        }
       }else if(this.radius > this.minRadius){
           this.radius -= 1;
       }
       
       this.draw();
    };
}


var circleArray = [];

for(var i = 0;i < 500; i++){
   var x = Math.random() * innerWidth;
   var y =  Math.random() * innerHeight;
   var dx = (Math.random() - 0.5) * 4;
   var dy = (Math.random() - 0.5) * 4;
   var radius = Math.random() * 3 + 1;
  circleArray.push(new Circle(x,y,dx,dy,radius)); 
}
    
   function animate(){
       requestAnimationFrame(animate);
       c.clearRect(0,0, innerWidth, innerHeight);
       
       for(var i = 0; i < circleArray.length;i++){
           circleArray[i].update();
       }

   }
   
   animate();