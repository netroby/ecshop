
/*
 * 类似backbone.js的事件管理风格
*/

;(function (win) {

	var events = {};

	/*
	 * 一些工具方法
	*/
	var util = {
		isArray: function (arr) {
			return Object.prototype.toString
						 .call(arr).slice(8, -1)
						 .toLowerCase() === 'array';
		},

		isHTMLEle: function (ele) {
			return Object.prototype.toString
						 .call(ele).slice(8, -1)
						 .toLowerCase().indexOf('element') > 0;
		} 
	};
	
	/*
	 * 模拟jquery的选择器、DOM操作、事件绑定 (减少对jquery以及zepto的依赖)
	*/

	//选择单个DOM元素
	var $ = function (selector) {
		return document.querySelector 
					 ? document.querySelector.call(document, selector)
					 : document.getElementById(selector);
	};

	//选择多个DOM元素
	var $All = function (selector) {
		return document.querySelectorAll(selector);
	};

	//DOM元素属性操作
	var $attr = function (el, attr, val) {
		var hasOwnProperty = Object.prototype.hasOwnProperty,
				prop;

		if (!el || typeof el === 'string') throw new Error('请传入一个DOM对象');
		if (typeof attr === 'object') {
			for (prop in attr) {
				hasOwnPrototype.call(attr, prop) && ($attr(el, prop, attr[prop]));
			}
		}
		else if (val) {
			el.setAttribute(attr, val);
		}
		else {
			return el[attr] ? el[attr] : el.getAttribute(attr);
		}
	};

	//DOM元素行内样式操作
	var $css = function (el, type, val) {
		var hasOwnProperty = Object.prototype.hasOwnProperty,
				prop;

		if (!el || typeof el === 'string') throw new Error('请传入一个DOM对象');
		if (typeof type === 'object') {
			for (prop in type) {
				hasOwnPrototype.call(type, prop) && ($attr(el, prop, type[prop]));
			}
		}
		else if (val) {
			el.style[type] = val;
		}
		else {
			return el.style[type];
		}
	};

	var $classOption = function (el, className, flag) {
		var flag = flag || 'add';

		if (!el || typeof el === 'string') throw new Error('请传入一个DOM对象');
		el.classList[flag](className);
	};

	/*
	 * 事件管理类
	 * @params eventConfig{object} 
	 * @params return nothings
	*/
	function Events (eventObj) {
		if (!(this instanceof Events)) return new Events(eventObj);
		if (!events) throw new Error('传入一个event obj');
		this.injection(eventObj);
	}

	Events.prototype = {
		
		//注入事件	
		injection: function (eventObj) {
			var hasOwnProperty = Object.prototype.hasOwnProperty,
			  	eventName, selector, prop;
				
			for (prop in eventObj) {
				if (hasOwnProperty.call(eventObj, prop)) {
					eventName = prop.split(' ')[0];
					selector = /[.#]/.test(prop.split(' ')[1]) ? prop.split(' ')[1] : '.' + prop.split(' ')[1];
					(!events[eventName]) && (events[eventName] = []);
					events[eventName].push({
						'selectorName': selector,
						'selector': $(selector),
						'handle': eventObj[prop]
					});
				}
			}

			return this;	
		},

		//删除事件
		remove: function (eventName) {
			events[eventName] && (delete events[eventName]);
		},

		//触发事件
		fire: function (e, obj, handle) {
			handle(e, obj, Array.prototype.slice(arguments, 3));
		},

		//事件绑定相应的元素
		bind: function (eventName) {
			var eventArr = events[eventName],
				  self = this,
				  args = Array.prototype.slice.call(arguments, 1),
				  handle, i, len, el;

		  if (!eventArr) throw new Error('不存在的事件');
		
		  for (i = 0, len = eventArr.length; i < len, el = eventArr[i++];) {
		  	if (el) {
		  		(function (handle) {

			  			el['selector'].addEventListener(eventName, function (e) {
			  			self.fire.apply(null, [e, this, handle].concat(args));
		  			}, false);

		  		})(el['handle']);	
		  	}
		  }

		  return this;
		}
	}

	win.Events = Events;

})(window);