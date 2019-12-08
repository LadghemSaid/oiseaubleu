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
        <div class="likes"  :style="[]">
         <slot name="likes"></slot>
        </div>
          <div>
         <slot name="admin"></slot>
        </div>
       <a :href="dataArticle" class="btn__seeMore"></a>
        <div class="card-bg lazyload" :style="[ cardBgImage]">
       
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
          /*  mousePX() {
                return this.mouseX /this.width;
            },
            mousePY() {
                return this.mouseY / this.height;
            },
            */
            cardStyle() {
                const rX = this.mousePX * 30;
                const rY = this.mousePY * -30;
                return {
                    transform: `rotateY(${rX}deg) rotateX(${rY}deg)`
                };
            },
/*
            cardBgTransform() {
                const tX = this.mousePX * -20;
                const tY = this.mousePY * -20;
                return {
                  transform: `translateX(${tX}px) translateY(${tY}px)`
                }
            },
            likesTransform() {
                const tX = this.mousePX * 5;
                const tY = this.mousePY * 5;
                return {
                   transform: `translateX(${tX}px) translateY(${tY}px)`
                }
            },
*/
            cardBgImage() {

                return {
                    backgroundImage: `url(${this.dataImage})`
                }
            },

        },
        methods: {

            handleMouseMove(e) {
                var rect = e.target.getBoundingClientRect();
                this.mouseX = e.clientX - rect.left - this.width/2;
                this.mouseY = e.clientY - rect.top  - this.height/2;
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






