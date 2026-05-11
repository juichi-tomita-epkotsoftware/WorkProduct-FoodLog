const images = [
  "img/cookingman.png",
  "img/cooking/img_0018_720.jpg",
  "img/cooking/img_0054_720.jpg",
  "img/cooking/img_0160_720.jpg",
  "img/cooking/img_0194_720.jpg",
  "img/cooking/img_0268_720.jpg",
]

let index = 0

setInterval(()=>{

  index++

  if(index >= images.length){
    index = 0
  }

  document.getElementById("hero-img").src = images[index]

}, 3000)