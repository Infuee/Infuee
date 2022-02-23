<?php

namespace app;

class Pjax
{
    public $options = [];
    public $linkSelector;
    public $formSelector;
    public $submitEvent = 'submit';
    public $enablePushState = true;
    public $enableReplaceState = false;
    public $timeout = 1000;
    public $scrollTo = false;
    public $clientOptions;
    public static $counter = 0;
    public static $autoIdPrefix = 'p';

    public static function begin(String $selector, Array $options = []){

        $script = '!function(t){function e(e,a,r){return r=m(a,r),this.on("click.pjax",e,function(e){var a=r;a.container||(a=t.extend({},r),a.container=t(this).attr("data-pjax")),n(e,a)})}function n(e,n,a){a=m(n,a);var i=e.currentTarget,o=t(i);if("A"!==i.tagName.toUpperCase())throw"$.fn.pjax or $.pjax.click requires an anchor element";if(!(e.which>1||e.metaKey||e.ctrlKey||e.shiftKey||e.altKey||location.protocol!==i.protocol||location.hostname!==i.hostname||i.href.indexOf("#")>-1&&h(i)==h(location)||e.isDefaultPrevented())){var c={url:i.href,container:o.attr("data-pjax"),target:i},s=t.extend({},c,a),u=t.Event("pjax:click");o.trigger(u,[s]),u.isDefaultPrevented()||(r(s),e.preventDefault(),o.trigger("pjax:clicked",[s]))}}function a(e,n,a){a=m(n,a);var i=e.currentTarget,o=t(i);if("FORM"!==i.tagName.toUpperCase())throw"$.pjax.submit requires a form element";var c={type:(o.attr("method")||"GET").toUpperCase(),url:o.attr("action"),container:o.attr("data-pjax"),target:i};if("GET"!==c.type&&void 0!==window.FormData)c.data=new FormData(i),c.processData=!1,c.contentType=!1;else{if(o.find(":file").length)return;c.data=o.serializeArray()}r(t.extend({},c,a)),e.preventDefault()}function r(e){function n(n,a,r){r||(r={}),r.relatedTarget=e.target;var i=t.Event(n,r);return c.trigger(i,a),!i.isDefaultPrevented()}e=t.extend(!0,{},t.ajaxSettings,r.defaults,e),t.isFunction(e.url)&&(e.url=e.url());var a=f(e.url).hash,i=t.type(e.container);if("string"!==i)throw"expected string value for 'container' option; got "+i;var c=e.context=t(e.container);if(!c.length)throw"the container selector '"+e.container+"' did not match anything";e.data||(e.data={}),t.isArray(e.data)?e.data.push({name:"_pjax",value:e.container}):e.data._pjax=e.container;var s;e.beforeSend=function(t,r){if("GET"!==r.type&&(r.timeout=0),t.setRequestHeader("X-PJAX","true"),t.setRequestHeader("X-PJAX-Container",e.container),!n("pjax:beforeSend",[t,r]))return!1;r.timeout>0&&(s=setTimeout(function(){n("pjax:timeout",[t,e])&&t.abort("timeout")},r.timeout),r.timeout=0);var i=f(r.url);a&&(i.hash=a),e.requestUrl=d(i)},e.complete=function(t,a){s&&clearTimeout(s),n("pjax:complete",[t,a,e]),n("pjax:end",[t,e])},e.error=function(t,a,r){var i=g("",t,e),c=n("pjax:error",[t,a,r,e]);"GET"==e.type&&"abort"!==a&&c&&o(i.url)},e.success=function(i,s,u){var p=r.state,d="function"==typeof t.pjax.defaults.version?t.pjax.defaults.version():t.pjax.defaults.version,h=u.getResponseHeader("X-PJAX-Version"),m=g(i,u,e),v=f(m.url);if(a&&(v.hash=a,m.url=v.href),d&&h&&d!==h)return void o(m.url);if(!m.contents)return void o(m.url);r.state={id:e.id||l(),url:m.url,title:m.title,container:e.container,fragment:e.fragment,timeout:e.timeout},(e.push||e.replace)&&window.history.replaceState(r.state,m.title,m.url);var x=t.contains(c,document.activeElement);if(x)try{document.activeElement.blur()}catch(t){}m.title&&(document.title=m.title),n("pjax:beforeReplace",[m.contents,e],{state:r.state,previousState:p}),c.html(m.contents);var j=c.find("input[autofocus], textarea[autofocus]").last()[0];j&&document.activeElement!==j&&j.focus(),y(m.scripts);var w=e.scrollTo;if(a){var b=decodeURIComponent(a.slice(1)),T=document.getElementById(b)||document.getElementsByName(b)[0];T&&(w=t(T).offset().top)}"number"==typeof w&&t(window).scrollTop(w),n("pjax:success",[i,s,u,e])},r.state||(r.state={id:l(),url:window.location.href,title:document.title,container:e.container,fragment:e.fragment,timeout:e.timeout},window.history.replaceState(r.state,document.title)),u(r.xhr),r.options=e;var h=r.xhr=t.ajax(e);return h.readyState>0&&(e.push&&!e.replace&&(j(r.state.id,[e.container,p(c)]),window.history.pushState(null,"",e.requestUrl)),n("pjax:start",[h,e]),n("pjax:send",[h,e])),r.xhr}function i(e,n){var a={url:window.location.href,push:!1,replace:!0,scrollTo:!1};return r(t.extend(a,m(e,n)))}function o(t){window.history.replaceState(null,"",r.state.url),window.location.replace(t)}function c(e){P||u(r.xhr);var n,a=r.state,i=e.state;if(i&&i.container){if(P&&C==i.url)return;if(a){if(a.id===i.id)return;n=a.id<i.id?"forward":"back"}var c=D[i.id]||[],s=c[0]||i.container,l=t(s),d=c[1];if(l.length){a&&w(n,a.id,[s,p(l)]);var f=t.Event("pjax:popstate",{state:i,direction:n});l.trigger(f);var h={id:i.id,url:i.url,container:s,push:!1,fragment:i.fragment,timeout:i.timeout,scrollTo:!1};if(d){l.trigger("pjax:start",[null,h]),r.state=i,i.title&&(document.title=i.title);var m=t.Event("pjax:beforeReplace",{state:i,previousState:a});l.trigger(m,[d,h]),l.html(d),l.trigger("pjax:end",[null,h])}else r(h);l[0].offsetHeight}else o(location.href)}P=!1}function s(e){var n=t.isFunction(e.url)?e.url():e.url,a=e.type?e.type.toUpperCase():"GET",r=t("<form>",{method:"GET"===a?"GET":"POST",action:n,style:"display:none"});"GET"!==a&&"POST"!==a&&r.append(t("<input>",{type:"hidden",name:"_method",value:a.toLowerCase()}));var i=e.data;if("string"==typeof i)t.each(i.split("&"),function(e,n){var a=n.split("=");r.append(t("<input>",{type:"hidden",name:a[0],value:a[1]}))});else if(t.isArray(i))t.each(i,function(e,n){r.append(t("<input>",{type:"hidden",name:n.name,value:n.value}))});else if("object"==typeof i){var o;for(o in i)r.append(t("<input>",{type:"hidden",name:o,value:i[o]}))}t(document.body).append(r),r.submit()}function u(e){e&&e.readyState<4&&(e.onreadystatechange=t.noop,e.abort())}function l(){return(new Date).getTime()}function p(e){var n=e.clone();return n.find("script").each(function(){this.src||t._data(this,"globalEval",!1)}),n.contents()}function d(t){return t.search=t.search.replace(/([?&])(_pjax|_)=[^&]*/g,"").replace(/^&/,""),t.href.replace(/\?($|#)/,"$1")}function f(t){var e=document.createElement("a");return e.href=t,e}function h(t){return t.href.replace(/#.*/,"")}function m(e,n){return e&&n?(n=t.extend({},n),n.container=e,n):t.isPlainObject(e)?e:{container:e}}function v(t,e){return t.filter(e).add(t.find(e))}function x(e){return t.parseHTML(e,document,!0)}function g(e,n,a){var r={},i=/<html/i.test(e),o=n.getResponseHeader("X-PJAX-URL");r.url=o?d(f(o)):a.requestUrl;var c,s;if(i){s=t(x(e.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]));var u=e.match(/<head[^>]*>([\s\S.]*)<\/head>/i);c=null!=u?t(x(u[0])):s}else c=s=t(x(e));if(0===s.length)return r;if(r.title=v(c,"title").last().text(),a.fragment){var l=s;"body"!==a.fragment&&(l=v(l,a.fragment).first()),l.length&&(r.contents="body"===a.fragment?l:l.contents(),r.title||(r.title=l.attr("title")||l.data("title")))}else i||(r.contents=s);return r.contents&&(r.contents=r.contents.not(function(){return t(this).is("title")}),r.contents.find("title").remove(),r.scripts=v(r.contents,"script[src]").remove(),r.contents=r.contents.not(r.scripts)),r.title&&(r.title=t.trim(r.title)),r}function y(e){if(e){var n=t("script[src]");e.each(function(){var e=this.src,a=n.filter(function(){return this.src===e});if(!a.length){var r=document.createElement("script"),i=t(this).attr("type");i&&(r.type=i),r.src=t(this).attr("src"),document.head.appendChild(r)}})}}function j(t,e){D[t]=e,U.push(t),b(R,0),b(U,r.defaults.maxCacheLength)}function w(t,e,n){var a,i;D[e]=n,"forward"===t?(a=U,i=R):(a=R,i=U),a.push(e),e=i.pop(),e&&delete D[e],b(a,r.defaults.maxCacheLength)}function b(t,e){for(;t.length>e;)delete D[t.shift()]}function T(){return t("meta").filter(function(){var e=t(this).attr("http-equiv");return e&&"X-PJAX-VERSION"===e.toUpperCase()}).attr("content")}function E(){t.fn.pjax=e,t.pjax=r,t.pjax.enable=t.noop,t.pjax.disable=S,t.pjax.click=n,t.pjax.submit=a,t.pjax.reload=i,t.pjax.defaults={timeout:650,push:!0,replace:!1,type:"GET",dataType:"html",scrollTo:0,maxCacheLength:20,version:T},t(window).on("popstate.pjax",c)}function S(){t.fn.pjax=function(){return this},t.pjax=s,t.pjax.enable=E,t.pjax.disable=t.noop,t.pjax.click=t.noop,t.pjax.submit=t.noop,t.pjax.reload=function(){window.location.reload()},t(window).off("popstate.pjax",c)}var P=!0,C=window.location.href,A=window.history.state;A&&A.container&&(r.state=A),"state"in window.history&&(P=!1);var D={},R=[],U=[];t.event.props&&t.inArray("state",t.event.props)<0?t.event.props.push("state"):"state"in t.Event.prototype||t.event.addProp("state"),t.support.pjax=window.history&&window.history.pushState&&window.history.replaceState&&!navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/),t.support.pjax?E():S()}(jQuery);$(document).pjax("a", "#pjax-container");';

    }


    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if ($this->requiresPjax()) {
            ob_start();
            ob_implicit_flush(false);
            $view = $this->getView();
            $view->clear();
            $view->beginPage();
            $view->head();
            $view->beginBody();
            if ($view->title !== null) {
                echo Html::tag('title', Html::encode($view->title));
            }
        } else {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            echo Html::beginTag($tag, array_merge([
                'data-pjax-container' => '',
                'data-pjax-push-state' => $this->enablePushState,
                'data-pjax-replace-state' => $this->enableReplaceState,
                'data-pjax-timeout' => $this->timeout,
                'data-pjax-scrollto' => $this->scrollTo,
            ], $options));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (!$this->requiresPjax()) {
            echo Html::endTag(ArrayHelper::remove($this->options, 'tag', 'div'));
            $this->registerClientScript();

            return;
        }

        $view = $this->getView();
        $view->endBody();

        $view->endPage(true);

        $content = ob_get_clean();

        // only need the content enclosed within this widget
        $response = Yii::$app->getResponse();
        $response->clearOutputBuffers();
        $response->setStatusCode(200);
        $response->format = Response::FORMAT_HTML;
        $response->content = $content;
        $response->headers->setDefault('X-Pjax-Url', Yii::$app->request->url);

        Yii::$app->end();
    }

    /**
     * @return bool whether the current request requires pjax response from this widget
     */
    protected function requiresPjax()
    {
        $headers = Yii::$app->getRequest()->getHeaders();

        return $headers->get('X-Pjax') && explode(' ', $headers->get('X-Pjax-Container'))[0] === '#' . $this->options['id'];
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;
        if (!isset($this->clientOptions['container'])) {
            $this->clientOptions['container'] = "#$id";
        }
        $options = Json::htmlEncode($this->clientOptions);
        $js = '';
        if ($this->linkSelector !== false) {
            $linkSelector = Json::htmlEncode($this->linkSelector !== null ? $this->linkSelector : '#' . $id . ' a');
            $js .= "jQuery(document).pjax($linkSelector, $options);";
        }
        if ($this->formSelector !== false) {
            $formSelector = Json::htmlEncode($this->formSelector !== null ? $this->formSelector : '#' . $id . ' form[data-pjax]');
            $submitEvent = Json::htmlEncode($this->submitEvent);
            $js .= "\njQuery(document).off($submitEvent, $formSelector).on($submitEvent, $formSelector, function (event) {jQuery.pjax.submit(event, $options);});";
        }
        $view = $this->getView();
        PjaxAsset::register($view);

        if ($js !== '') {
            $view->registerJs($js);
        }
    }
}