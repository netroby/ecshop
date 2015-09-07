;(function ($) {
  var rSelector = /^[#.]([\w-]+)/, //判断是否是id和class选择器，假如是就获取选择器内容
        
      /*数组和对象的原型方法，避免对象多次调用和方法多次查找*/
      Arr = Array,
      Obj = Object,
      slice = Arr.prototype.slice, 
      concat = Arr.prototype.concat,
      hasOwnProperty = Obj.prototype.hasOwnProperty,
      toString = Obj.prototype.toString;
   
  /*
   * @params target {object} 扩展的目标对象
   * @params source {object} 扩展的源对象
   * */
  function merge (target, source) {
    var args = slice.call(arguments),
        ride = typeof args[args.length - 1] === 'boolean' ? args.pop() : true,
        i = 1,
        prop;

    if (args.length === 1) {
      source = target;
      target = !root.window ? {} : root;
    }
    while((source = args[i++])) {
      for (prop in source) {
        if (hasOwnProperty.call(source, prop)) {
          (ride || !target[prop]) && (target[prop] = source[prop]);
        }
      }
    }
      return target;
  };

  /*判断是否是函数*/
  function isFunction (fn) {
    return !!(fn && toString.call(fn).slice(8, -1).toLowerCase() === 'function')
  }

  /*
   * @params tmplStr {string} 模板HTML代码
   * @params data {obj} 映射模板的数据
  */
  function getTemplate (tmplStr, data) {
    if (rSelector.test(tmplStr)) {
      tmplStr = $(tmplStr).html();
    }
    return tmplStr && tmplStr.replace(/\{([a-z]+)\}/i, function (val, v1) {
      return data[v1] || '';
    });
  };

  merge($.fn, {
    readyValid: function (config) {
      var fields = [],
          pauseMessage = false,
          item;

      /*输出错误信息(html格式)*/
      function defaultErrMsg (classMsg) {
        var msgErrClass = classMsg || 'un-message';
        return '<span class="' + msgErrClass + '" role="page">请输入必要的信息</span>';
      };

      /*生成错误信息(txt|html)*/
      function generateError(err) {
        if (err) {
          return getTemplate(err.template, err.data);
        }
        return defaultErrMsg();
      };

      /*提交处理*/
      function handleSubmit () {
        var validErr = false, //提交表单的时候是否有错误
            i, l;

        
        for (i = 0, l = fields.length; i < l; i += 1) {
          if(!(fields[i].startValid()) && (validErr = true)) {
            break;
          } 
          //!(fields[i].startValid()) && (validErr = true);
        //  break;
        }

        if (validErr) {
          //isFunction(config.failure) && (config.failure());
          return false;
        }
        return isFunction(config.success) && (config.success());
      };

      /*blur处理*/
      function handleBlur (handleBlurEl) {
        handleBlurEl.startValid(); 
      };

      /*表单字段验证处理*/
      function validateField (opts, selector) {
        var field = $(selector),
            errorEl = null,
            fErrorEl = null;

            field.startValid = function () {
              var el = $(this),
                  error = false, //默认非空验证成立,
                  fError = false, //默认格式化成立，
                  required = opts.required,
                  format = opts.format || [],
                  val = el.val(),
                  goFn, goExec, rErrClass,fErrClass, fData, template, fTemplate, sign, data, exec, i, l;


              (errorEl && errorEl.size() > 0) && (errorEl.remove());
              (fErrorEl && fErrorEl.size() > 0) && (fErrorEl.remove());
              //检查在handler是否有错误产生
              if (required.isSure && val.length === 0) {
                  error = true;
                  template = required.errTemplate;
                  rErrClass = required.errClass;
                  data = required.data;
                  exec = required.exec;
              }

              for (i = 0, l = format.length; i < l; i += 1) {
                sign = format[i].sign;
                goFn = isFunction(format[i].test) && format[i].test;
                
                if ((fError = !goFn(sign, val))) {
                  fErrClass = format[i].errClass;
                  fTemplate = format[i].errTemplate;

                  fData = format[i].data;
                  goExec = format[i].exec;
                  break;   
                }
              }
              //如果检测到有错误的时候
              if (error) {             
                errorEl = $(generateError(template ? {template: template, data: data || {}} : null));
                exec(errorEl);
                return false;
              }

              else if (fError) {      
                fErrorEl = $(generateError({template: fTemplate, data: fData || {}}));
                goExec && goExec(fErrorEl);
                return false;
              }
              return true;     
            };
            
            field.bind(opts.then || 'blur', (function () {handleBlur(field)}));
            fields.push(field);
      };

      for (item in config.fields) {
        (hasOwnProperty.call(config.fields, item)) && (validateField(config.fields[item], item));
      }

      if (config.submitBtn) {
        $(config.submitBtn).click(handleSubmit);
      } else {
        this.bind('submit', handleSubmit);
      }

      return this;
    }
  });

})(this.jQuery || this.Zepto);