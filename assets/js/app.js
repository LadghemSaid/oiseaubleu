/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/scss/imports.scss');
require('../css/scss/helpers/typo/all.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

Vue.config.devtools = true;

Vue.component('card', {
    template: `
    <div class="card-wrap"
      
      @mousemove="handleMouseMove"
      @mouseenter="handleMouseEnter"
      @mouseleave="handleMouseLeave"
      ref="card">
      
     
      <div class="card"
      
        :style="cardStyle">
        <div class="likes">
         <slot name="likes"></slot>
        </div>
       <a :href="dataArticle" class="btn__seeMore"></a>
        <div class="card-bg lazyload" :style="[cardBgTransform, cardBgImage]">
       
</div>
        <div class="card-info">
          <slot name="header"></slot>
          <slot name="content"></slot>
          <div class="post-meta">
              <div class="post-module__timestamp">
                <slot name="post-module__timestamp" ></slot>
              </div>
              <div class="post-module__comments">
                <slot name="post-module__comments" ></slot>  
              </div>
          </div>
        </div>
      </div>
    </div>`,
    mounted() {
        this.width = this.$refs.card.offsetWidth;
        this.height = this.$refs.card.offsetHeight;
    },
    props: ['dataImage','dataArticle'],
    data: () => ({
        width: 0,
        height: 0,
        mouseX: 0,
        mouseY: 0,
        mouseLeaveDelay: null
    }),
    computed: {
        mousePX() {
            //console.log("this.width",this.width);

            //console.log(" this.mouseX",this.mouseX);
            return this.mouseX /this.width;
        },
        mousePY() {
            //console.log("this.height",this.height);
            //console.log("this.height",this.mouseY);
            //console.log(" this.mouseY",this.mouseY);
            return this.mouseY / this.height;
        },
        cardStyle() {
            const rX = this.mousePX * 30;
            const rY = this.mousePY * -30;
            return {
                transform: `rotateY(${rX}deg) rotateX(${rY}deg)`
            };
        },
        cardBgTransform() {
            const tX = this.mousePX * -20;
            const tY = this.mousePY * -20;
            return {
                transform: `translateX(${tX}px) translateY(${tY}px)`
            }
        },
        cardBgImage() {
           // console.log(this.dataImage,this)
            return {
                backgroundImage: `url(${this.dataImage})`
            }
        },
        hrefLink(){
           return console.log("ok click")
        }
    },
    methods: {
        handleMouseMove(e) {
            //console.log(e.pageY/100);

        /*    var objLeft = e.target.offsetWidth;
            var objTop = e.target.offsetHeight;

            var objCenterX = objLeft / 2;
            var objCenterY = objTop / 2;

            console.log("Left:" + (e.layerX-objCenterX) + ", Top:" + (e.layerY-objCenterY));*/
             var rect = e.target.getBoundingClientRect();
             var x = e.clientX - rect.left; //x position within the element.
             var y = e.clientY - rect.top;  //y position within the element.

            //this.mouseX = e.pageX - this.$refs.card.offsetLeft - this.width/2;
            //this.mouseY = e.pageY - this.$refs.card.offsetTop - this.height/2;
            this.mouseX = x - this.width/2;
            this.mouseY = y  - this.height/2;
        },
        handleMouseEnter() {
            clearTimeout(this.mouseLeaveDelay);
        },
        handleMouseLeave() {
            this.mouseLeaveDelay = setTimeout(()=>{
                this.mouseX = 0;
                this.mouseY = 0;
            }, 1000);
        }
    }
});


var x = document.getElementsByClassName("app");
x.forEach(x=>{
    const app = new Vue({
        el: x
    });
});

