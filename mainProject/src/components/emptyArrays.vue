<template>
    <div>
        <div class="emptyContainer mt-0">
        <img :src="source" alt="Image">
        <h1 class="text">{{ message }}</h1>
      </div>
    </div>
  

      
</template>

<script>
export default {
    data(){
        return{
        }
    },
    props:['source','message']
}
</script>

<style scoped>
.emptyContainer{
    top: 0;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 800px;
  height:500px;
  margin:50px auto ;
}
.emptyContainer img{
  position: absolute;
  top: 0;
  width: 400px;
  height: 400px;
}
.text{
    bottom: 30px;
    position: absolute;
    margin: 10px auto 0;
    font-family: Outfit;
    font-size: 55px;
    color: #120030;
}
</style>