
(function(){
/*图片滚动*/
var adSlider = $('.shop-detail>.img');
var inner= adSlider.find('.slider-list');
var sl = inner.find('.slider-item').size();
var sw = inner.find('.slider-item').width();
var speed = 200;
var act = 0;
var x1,x2, direct;

alert(adSlider.size());

function transformBox(obj,value,time){
    var time=time?time:0;
    transl="translate3d("+value+"px,0,0)";
    obj.css({'-webkit-transform':transl,'-webkit-transition':time+'ms linear'});
}

inner.css({'width':sl * 100 + '%'});
adSlider.on('touchstart', function(e) {
    if(x2) x2 = undefined;
    x1 = e.touches[0].pageX;
})
.on('touchmove', function(e){
    e.preventDefault();
    x2 = e.touches[0].pageX;
    var offset= x2-x1+act*sw;
    transformBox(inner,offset,0);
})
.on('touchend', function(e) {
    if(x1 > x2) direct = 'left';
    else direct = 'right';
    if(Math.abs(x1 - x2) < 30) 
    transformBox(inner,act * sw,100);
})
.swipe(function(e){
    var offset;
    if(direct == 'left') {
        --act;
    }
    else {
        ++act;
    }
    if(act == 1){
        act = 0;
    }
    else if(act == -sl){
        act = - sl + 1;
    }
    offset=act*sw;
    transformBox(inner, offset,speed);
    $(this).next().children().eq(Math.abs(act)).addClass('act').siblings('.act').removeClass('act');
});
/*图片滚动end*/
})();
