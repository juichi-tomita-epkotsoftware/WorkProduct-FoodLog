const images = [
"img/cat1.jpg",
"img/cat2.jpg",
"img/cat3.jpg"
]

let index = 0

setInterval(()=>{

index++

if(index >= images.length){
 index = 0
}

document.getElementById("hero-img").src = images[index]

},3000)