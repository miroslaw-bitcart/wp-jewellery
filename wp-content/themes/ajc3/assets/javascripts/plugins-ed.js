//AJC plugins

//1.spin.js (line 7)
//2.simplemodal.js (line 364)
//3.history.js (line 1083)

//jQuery Plugins

//Waypoints


//1.spin.js
//fgnass.github.com/spin.js#v1.3.2

/**
 * Copyright (c) 2011-2013 Felix Gnass
 * Licensed under the MIT license
 */
(function(root, factory) {

  /* CommonJS */
  if (typeof exports == 'object')  module.exports = factory()

  /* AMD module */
  else if (typeof define == 'function' && define.amd) define(factory)

  /* Browser global */
  else root.Spinner = factory()
}
(this, function() {
  "use strict";

  var prefixes = ['webkit', 'Moz', 'ms', 'O'] /* Vendor prefixes */
    , animations = {} /* Animation rules keyed by their name */
    , useCssAnimations /* Whether to use CSS animations or setTimeout */

  /**
   * Utility function to create elements. If no tag name is given,
   * a DIV is created. Optionally properties can be passed.
   */
  function createEl(tag, prop) {
    var el = document.createElement(tag || 'div')
      , n

    for(n in prop) el[n] = prop[n]
    return el
  }

  /**
   * Appends children and returns the parent.
   */
  function ins(parent /* child1, child2, ...*/) {
    for (var i=1, n=arguments.length; i<n; i++)
      parent.appendChild(arguments[i])

    return parent
  }

  /**
   * Insert a new stylesheet to hold the @keyframe or VML rules.
   */
  var sheet = (function() {
    var el = createEl('style', {type : 'text/css'})
    ins(document.getElementsByTagName('head')[0], el)
    return el.sheet || el.styleSheet
  }())

  /**
   * Creates an opacity keyframe animation rule and returns its name.
   * Since most mobile Webkits have timing issues with animation-delay,
   * we create separate rules for each line/segment.
   */
  function addAnimation(alpha, trail, i, lines) {
    var name = ['opacity', trail, ~~(alpha*100), i, lines].join('-')
      , start = 0.01 + i/lines * 100
      , z = Math.max(1 - (1-alpha) / trail * (100-start), alpha)
      , prefix = useCssAnimations.substring(0, useCssAnimations.indexOf('Animation')).toLowerCase()
      , pre = prefix && '-' + prefix + '-' || ''

    if (!animations[name]) {
      sheet.insertRule(
        '@' + pre + 'keyframes ' + name + '{' +
        '0%{opacity:' + z + '}' +
        start + '%{opacity:' + alpha + '}' +
        (start+0.01) + '%{opacity:1}' +
        (start+trail) % 100 + '%{opacity:' + alpha + '}' +
        '100%{opacity:' + z + '}' +
        '}', sheet.cssRules.length)

      animations[name] = 1
    }

    return name
  }

  /**
   * Tries various vendor prefixes and returns the first supported property.
   */
  function vendor(el, prop) {
    var s = el.style
      , pp
      , i

    prop = prop.charAt(0).toUpperCase() + prop.slice(1)
    for(i=0; i<prefixes.length; i++) {
      pp = prefixes[i]+prop
      if(s[pp] !== undefined) return pp
    }
    if(s[prop] !== undefined) return prop
  }

  /**
   * Sets multiple style properties at once.
   */
  function css(el, prop) {
    for (var n in prop)
      el.style[vendor(el, n)||n] = prop[n]

    return el
  }

  /**
   * Fills in default values.
   */
  function merge(obj) {
    for (var i=1; i < arguments.length; i++) {
      var def = arguments[i]
      for (var n in def)
        if (obj[n] === undefined) obj[n] = def[n]
    }
    return obj
  }

  /**
   * Returns the absolute page-offset of the given element.
   */
  function pos(el) {
    var o = { x:el.offsetLeft, y:el.offsetTop }
    while((el = el.offsetParent))
      o.x+=el.offsetLeft, o.y+=el.offsetTop

    return o
  }

  /**
   * Returns the line color from the given string or array.
   */
  function getColor(color, idx) {
    return typeof color == 'string' ? color : color[idx % color.length]
  }

  // Built-in defaults

  var defaults = {
    lines: 12,            // The number of lines to draw
    length: 7,            // The length of each line
    width: 5,             // The line thickness
    radius: 10,           // The radius of the inner circle
    rotate: 0,            // Rotation offset
    corners: 1,           // Roundness (0..1)
    color: '#000',        // #rgb or #rrggbb
    direction: 1,         // 1: clockwise, -1: counterclockwise
    speed: 1,             // Rounds per second
    trail: 100,           // Afterglow percentage
    opacity: 1/4,         // Opacity of the lines
    fps: 20,              // Frames per second when using setTimeout()
    zIndex: 2e9,          // Use a high z-index by default
    className: 'spinner', // CSS class to assign to the element
    top: 'auto',          // center vertically
    left: 'auto',         // center horizontally
    position: 'relative'  // element position
  }

  /** The constructor */
  function Spinner(o) {
    if (typeof this == 'undefined') return new Spinner(o)
    this.opts = merge(o || {}, Spinner.defaults, defaults)
  }

  // Global defaults that override the built-ins:
  Spinner.defaults = {}

  merge(Spinner.prototype, {

    /**
     * Adds the spinner to the given target element. If this instance is already
     * spinning, it is automatically removed from its previous target b calling
     * stop() internally.
     */
    spin: function(target) {
      this.stop()

      var self = this
        , o = self.opts
        , el = self.el = css(createEl(0, {className: o.className}), {position: o.position, width: 0, zIndex: o.zIndex})
        , mid = o.radius+o.length+o.width
        , ep // element position
        , tp // target position

      if (target) {
        target.insertBefore(el, target.firstChild||null)
        tp = pos(target)
        ep = pos(el)
        css(el, {
          left: (o.left == 'auto' ? tp.x-ep.x + (target.offsetWidth >> 1) : parseInt(o.left, 10) + mid) + 'px',
          top: (o.top == 'auto' ? tp.y-ep.y + (target.offsetHeight >> 1) : parseInt(o.top, 10) + mid)  + 'px'
        })
      }

      el.setAttribute('role', 'progressbar')
      self.lines(el, self.opts)

      if (!useCssAnimations) {
        // No CSS animation support, use setTimeout() instead
        var i = 0
          , start = (o.lines - 1) * (1 - o.direction) / 2
          , alpha
          , fps = o.fps
          , f = fps/o.speed
          , ostep = (1-o.opacity) / (f*o.trail / 100)
          , astep = f/o.lines

        ;(function anim() {
          i++;
          for (var j = 0; j < o.lines; j++) {
            alpha = Math.max(1 - (i + (o.lines - j) * astep) % f * ostep, o.opacity)

            self.opacity(el, j * o.direction + start, alpha, o)
          }
          self.timeout = self.el && setTimeout(anim, ~~(1000/fps))
        })()
      }
      return self
    },

    /**
     * Stops and removes the Spinner.
     */
    stop: function() {
      var el = this.el
      if (el) {
        clearTimeout(this.timeout)
        if (el.parentNode) el.parentNode.removeChild(el)
        this.el = undefined
      }
      return this
    },

    /**
     * Internal method that draws the individual lines. Will be overwritten
     * in VML fallback mode below.
     */
    lines: function(el, o) {
      var i = 0
        , start = (o.lines - 1) * (1 - o.direction) / 2
        , seg

      function fill(color, shadow) {
        return css(createEl(), {
          position: 'absolute',
          width: (o.length+o.width) + 'px',
          height: o.width + 'px',
          background: color,
          boxShadow: shadow,
          transformOrigin: 'left',
          transform: 'rotate(' + ~~(360/o.lines*i+o.rotate) + 'deg) translate(' + o.radius+'px' +',0)',
          borderRadius: (o.corners * o.width>>1) + 'px'
        })
      }

      for (; i < o.lines; i++) {
        seg = css(createEl(), {
          position: 'absolute',
          top: 1+~(o.width/2) + 'px',
          transform: o.hwaccel ? 'translate3d(0,0,0)' : '',
          opacity: o.opacity,
          animation: useCssAnimations && addAnimation(o.opacity, o.trail, start + i * o.direction, o.lines) + ' ' + 1/o.speed + 's linear infinite'
        })

        if (o.shadow) ins(seg, css(fill('#000', '0 0 4px ' + '#000'), {top: 2+'px'}))
        ins(el, ins(seg, fill(getColor(o.color, i), '0 0 1px rgba(0,0,0,.1)')))
      }
      return el
    },

    /**
     * Internal method that adjusts the opacity of a single line.
     * Will be overwritten in VML fallback mode below.
     */
    opacity: function(el, i, val) {
      if (i < el.childNodes.length) el.childNodes[i].style.opacity = val
    }

  })


  function initVML() {

    /* Utility function to create a VML tag */
    function vml(tag, attr) {
      return createEl('<' + tag + ' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">', attr)
    }

    // No CSS transforms but VML support, add a CSS rule for VML elements:
    sheet.addRule('.spin-vml', 'behavior:url(#default#VML)')

    Spinner.prototype.lines = function(el, o) {
      var r = o.length+o.width
        , s = 2*r

      function grp() {
        return css(
          vml('group', {
            coordsize: s + ' ' + s,
            coordorigin: -r + ' ' + -r
          }),
          { width: s, height: s }
        )
      }

      var margin = -(o.width+o.length)*2 + 'px'
        , g = css(grp(), {position: 'absolute', top: margin, left: margin})
        , i

      function seg(i, dx, filter) {
        ins(g,
          ins(css(grp(), {rotation: 360 / o.lines * i + 'deg', left: ~~dx}),
            ins(css(vml('roundrect', {arcsize: o.corners}), {
                width: r,
                height: o.width,
                left: o.radius,
                top: -o.width>>1,
                filter: filter
              }),
              vml('fill', {color: getColor(o.color, i), opacity: o.opacity}),
              vml('stroke', {opacity: 0}) // transparent stroke to fix color bleeding upon opacity change
            )
          )
        )
      }

      if (o.shadow)
        for (i = 1; i <= o.lines; i++)
          seg(i, -2, 'progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)')

      for (i = 1; i <= o.lines; i++) seg(i)
      return ins(el, g)
    }

    Spinner.prototype.opacity = function(el, i, val, o) {
      var c = el.firstChild
      o = o.shadow && o.lines || 0
      if (c && i+o < c.childNodes.length) {
        c = c.childNodes[i+o]; c = c && c.firstChild; c = c && c.firstChild
        if (c) c.opacity = val
      }
    }
  }

  var probe = css(createEl('group'), {behavior: 'url(#default#VML)'})

  if (!vendor(probe, 'transform') && probe.adj) initVML()
  else useCssAnimations = vendor(probe, 'animation')

  return Spinner

}));

//2.simplemodal.js

/*
 * SimpleModal 1.4.4 - jQuery Plugin
 * http://simplemodal.com/
 * Copyright (c) 2013 Eric Martin
 * Licensed under MIT and GPL
 * Date: Sun, Jan 20 2013 15:58:56 -0800
 */

//3.history.js

/**
 * History.js Core
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 * @copyright 2010-2011 Benjamin Arthur Lupton <contact@balupton.com>
 * @license New BSD License <http://creativecommons.org/licenses/BSD/>
 */

(function(window,undefined){
  "use strict";

  // ========================================================================
  // Initialise

  // Localise Globals
  var
    console = window.console||undefined, // Prevent a JSLint complain
    document = window.document, // Make sure we are using the correct document
    navigator = window.navigator, // Make sure we are using the correct navigator
    sessionStorage = window.sessionStorage||false, // sessionStorage
    setTimeout = window.setTimeout,
    clearTimeout = window.clearTimeout,
    setInterval = window.setInterval,
    clearInterval = window.clearInterval,
    JSON = window.JSON,
    alert = window.alert,
    History = window.History = window.History||{}, // Public History Object
    history = window.history; // Old History Object

  // MooTools Compatibility
  JSON.stringify = JSON.stringify||JSON.encode;
  JSON.parse = JSON.parse||JSON.decode;

  // Check Existence
  if ( typeof History.init !== 'undefined' ) {
    throw new Error('History.js Core has already been loaded...');
  }

  // Initialise History
  History.init = function(){
    // Check Load Status of Adapter
    if ( typeof History.Adapter === 'undefined' ) {
      return false;
    }

    // Check Load Status of Core
    if ( typeof History.initCore !== 'undefined' ) {
      History.initCore();
    }

    // Check Load Status of HTML4 Support
    if ( typeof History.initHtml4 !== 'undefined' ) {
      History.initHtml4();
    }

    // Return true
    return true;
  };


  // ========================================================================
  // Initialise Core

  // Initialise Core
  History.initCore = function(){
    // Initialise
    if ( typeof History.initCore.initialized !== 'undefined' ) {
      // Already Loaded
      return false;
    }
    else {
      History.initCore.initialized = true;
    }


    // ====================================================================
    // Options

    /**
     * History.options
     * Configurable options
     */
    History.options = History.options||{};

    /**
     * History.options.hashChangeInterval
     * How long should the interval be before hashchange checks
     */
    History.options.hashChangeInterval = History.options.hashChangeInterval || 100;

    /**
     * History.options.safariPollInterval
     * How long should the interval be before safari poll checks
     */
    History.options.safariPollInterval = History.options.safariPollInterval || 500;

    /**
     * History.options.doubleCheckInterval
     * How long should the interval be before we perform a double check
     */
    History.options.doubleCheckInterval = History.options.doubleCheckInterval || 500;

    /**
     * History.options.storeInterval
     * How long should we wait between store calls
     */
    History.options.storeInterval = History.options.storeInterval || 1000;

    /**
     * History.options.busyDelay
     * How long should we wait between busy events
     */
    History.options.busyDelay = History.options.busyDelay || 250;

    /**
     * History.options.debug
     * If true will enable debug messages to be logged
     */
    History.options.debug = History.options.debug || false;

    /**
     * History.options.initialTitle
     * What is the title of the initial state
     */
    History.options.initialTitle = History.options.initialTitle || document.title;


    // ====================================================================
    // Interval record

    /**
     * History.intervalList
     * List of intervals set, to be cleared when document is unloaded.
     */
    History.intervalList = [];

    /**
     * History.clearAllIntervals
     * Clears all setInterval instances.
     */
    History.clearAllIntervals = function(){
      var i, il = History.intervalList;
      if (typeof il !== "undefined" && il !== null) {
        for (i = 0; i < il.length; i++) {
          clearInterval(il[i]);
        }
        History.intervalList = null;
      }
    };


    // ====================================================================
    // Debug

    /**
     * History.debug(message,...)
     * Logs the passed arguments if debug enabled
     */
    History.debug = function(){
      if ( (History.options.debug||false) ) {
        History.log.apply(History,arguments);
      }
    };

    /**
     * History.log(message,...)
     * Logs the passed arguments
     */
    History.log = function(){
      // Prepare
      var
        consoleExists = !(typeof console === 'undefined' || typeof console.log === 'undefined' || typeof console.log.apply === 'undefined'),
        textarea = document.getElementById('log'),
        message,
        i,n,
        args,arg
        ;

      // Write to Console
      if ( consoleExists ) {
        args = Array.prototype.slice.call(arguments);
        message = args.shift();
        if ( typeof console.debug !== 'undefined' ) {
          console.debug.apply(console,[message,args]);
        }
        else {
          console.log.apply(console,[message,args]);
        }
      }
      else {
        message = ("\n"+arguments[0]+"\n");
      }

      // Write to log
      for ( i=1,n=arguments.length; i<n; ++i ) {
        arg = arguments[i];
        if ( typeof arg === 'object' && typeof JSON !== 'undefined' ) {
          try {
            arg = JSON.stringify(arg);
          }
          catch ( Exception ) {
            // Recursive Object
          }
        }
        message += "\n"+arg+"\n";
      }

      // Textarea
      if ( textarea ) {
        textarea.value += message+"\n-----\n";
        textarea.scrollTop = textarea.scrollHeight - textarea.clientHeight;
      }
      // No Textarea, No Console
      else if ( !consoleExists ) {
        alert(message);
      }

      // Return true
      return true;
    };


    // ====================================================================
    // Emulated Status

    /**
     * History.getInternetExplorerMajorVersion()
     * Get's the major version of Internet Explorer
     * @return {integer}
     * @license Public Domain
     * @author Benjamin Arthur Lupton <contact@balupton.com>
     * @author James Padolsey <https://gist.github.com/527683>
     */
    History.getInternetExplorerMajorVersion = function(){
      var result = History.getInternetExplorerMajorVersion.cached =
          (typeof History.getInternetExplorerMajorVersion.cached !== 'undefined')
        ? History.getInternetExplorerMajorVersion.cached
        : (function(){
            var v = 3,
                div = document.createElement('div'),
                all = div.getElementsByTagName('i');
            while ( (div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->') && all[0] ) {}
            return (v > 4) ? v : false;
          })()
        ;
      return result;
    };

    /**
     * History.isInternetExplorer()
     * Are we using Internet Explorer?
     * @return {boolean}
     * @license Public Domain
     * @author Benjamin Arthur Lupton <contact@balupton.com>
     */
    History.isInternetExplorer = function(){
      var result =
        History.isInternetExplorer.cached =
        (typeof History.isInternetExplorer.cached !== 'undefined')
          ? History.isInternetExplorer.cached
          : Boolean(History.getInternetExplorerMajorVersion())
        ;
      return result;
    };

    /**
     * History.emulated
     * Which features require emulating?
     */
    History.emulated = {
      pushState: !Boolean(
        window.history && window.history.pushState && window.history.replaceState
        && !(
          (/ Mobile\/([1-7][a-z]|(8([abcde]|f(1[0-8]))))/i).test(navigator.userAgent) /* disable for versions of iOS before version 4.3 (8F190) */
          || (/AppleWebKit\/5([0-2]|3[0-2])/i).test(navigator.userAgent) /* disable for the mercury iOS browser, or at least older versions of the webkit engine */
        )
      ),
      hashChange: Boolean(
        !(('onhashchange' in window) || ('onhashchange' in document))
        ||
        (History.isInternetExplorer() && History.getInternetExplorerMajorVersion() < 8)
      )
    };

    /**
     * History.enabled
     * Is History enabled?
     */
    History.enabled = !History.emulated.pushState;

    /**
     * History.bugs
     * Which bugs are present
     */
    History.bugs = {
      /**
       * Safari 5 and Safari iOS 4 fail to return to the correct state once a hash is replaced by a `replaceState` call
       * https://bugs.webkit.org/show_bug.cgi?id=56249
       */
      setHash: Boolean(!History.emulated.pushState && navigator.vendor === 'Apple Computer, Inc.' && /AppleWebKit\/5([0-2]|3[0-3])/.test(navigator.userAgent)),

      /**
       * Safari 5 and Safari iOS 4 sometimes fail to apply the state change under busy conditions
       * https://bugs.webkit.org/show_bug.cgi?id=42940
       */
      safariPoll: Boolean(!History.emulated.pushState && navigator.vendor === 'Apple Computer, Inc.' && /AppleWebKit\/5([0-2]|3[0-3])/.test(navigator.userAgent)),

      /**
       * MSIE 6 and 7 sometimes do not apply a hash even it was told to (requiring a second call to the apply function)
       */
      ieDoubleCheck: Boolean(History.isInternetExplorer() && History.getInternetExplorerMajorVersion() < 8),

      /**
       * MSIE 6 requires the entire hash to be encoded for the hashes to trigger the onHashChange event
       */
      hashEscape: Boolean(History.isInternetExplorer() && History.getInternetExplorerMajorVersion() < 7)
    };

    /**
     * History.isEmptyObject(obj)
     * Checks to see if the Object is Empty
     * @param {Object} obj
     * @return {boolean}
     */
    History.isEmptyObject = function(obj) {
      for ( var name in obj ) {
        return false;
      }
      return true;
    };

    /**
     * History.cloneObject(obj)
     * Clones a object and eliminate all references to the original contexts
     * @param {Object} obj
     * @return {Object}
     */
    History.cloneObject = function(obj) {
      var hash,newObj;
      if ( obj ) {
        hash = JSON.stringify(obj);
        newObj = JSON.parse(hash);
      }
      else {
        newObj = {};
      }
      return newObj;
    };


    // ====================================================================
    // URL Helpers

    /**
     * History.getRootUrl()
     * Turns "http://mysite.com/dir/page.html?asd" into "http://mysite.com"
     * @return {String} rootUrl
     */
    History.getRootUrl = function(){
      // Create
      var rootUrl = document.location.protocol+'//'+(document.location.hostname||document.location.host);
      if ( document.location.port||false ) {
        rootUrl += ':'+document.location.port;
      }
      rootUrl += '/';

      // Return
      return rootUrl;
    };

    /**
     * History.getBaseHref()
     * Fetches the `href` attribute of the `<base href="...">` element if it exists
     * @return {String} baseHref
     */
    History.getBaseHref = function(){
      // Create
      var
        baseElements = document.getElementsByTagName('base'),
        baseElement = null,
        baseHref = '';

      // Test for Base Element
      if ( baseElements.length === 1 ) {
        // Prepare for Base Element
        baseElement = baseElements[0];
        baseHref = baseElement.href.replace(/[^\/]+$/,'');
      }

      // Adjust trailing slash
      baseHref = baseHref.replace(/\/+$/,'');
      if ( baseHref ) baseHref += '/';

      // Return
      return baseHref;
    };

    /**
     * History.getBaseUrl()
     * Fetches the baseHref or basePageUrl or rootUrl (whichever one exists first)
     * @return {String} baseUrl
     */
    History.getBaseUrl = function(){
      // Create
      var baseUrl = History.getBaseHref()||History.getBasePageUrl()||History.getRootUrl();

      // Return
      return baseUrl;
    };

    /**
     * History.getPageUrl()
     * Fetches the URL of the current page
     * @return {String} pageUrl
     */
    History.getPageUrl = function(){
      // Fetch
      var
        State = History.getState(false,false),
        stateUrl = (State||{}).url||document.location.href,
        pageUrl;

      // Create
      pageUrl = stateUrl.replace(/\/+$/,'').replace(/[^\/]+$/,function(part,index,string){
        return (/\./).test(part) ? part : part+'/';
      });

      // Return
      return pageUrl;
    };

    /**
     * History.getBasePageUrl()
     * Fetches the Url of the directory of the current page
     * @return {String} basePageUrl
     */
    History.getBasePageUrl = function(){
      // Create
      var basePageUrl = document.location.href.replace(/[#\?].*/,'').replace(/[^\/]+$/,function(part,index,string){
        return (/[^\/]$/).test(part) ? '' : part;
      }).replace(/\/+$/,'')+'/';

      // Return
      return basePageUrl;
    };

    /**
     * History.getFullUrl(url)
     * Ensures that we have an absolute URL and not a relative URL
     * @param {string} url
     * @param {Boolean} allowBaseHref
     * @return {string} fullUrl
     */
    History.getFullUrl = function(url,allowBaseHref){
      // Prepare
      var fullUrl = url, firstChar = url.substring(0,1);
      allowBaseHref = (typeof allowBaseHref === 'undefined') ? true : allowBaseHref;

      // Check
      if ( /[a-z]+\:\/\//.test(url) ) {
        // Full URL
      }
      else if ( firstChar === '/' ) {
        // Root URL
        fullUrl = History.getRootUrl()+url.replace(/^\/+/,'');
      }
      else if ( firstChar === '#' ) {
        // Anchor URL
        fullUrl = History.getPageUrl().replace(/#.*/,'')+url;
      }
      else if ( firstChar === '?' ) {
        // Query URL
        fullUrl = History.getPageUrl().replace(/[\?#].*/,'')+url;
      }
      else {
        // Relative URL
        if ( allowBaseHref ) {
          fullUrl = History.getBaseUrl()+url.replace(/^(\.\/)+/,'');
        } else {
          fullUrl = History.getBasePageUrl()+url.replace(/^(\.\/)+/,'');
        }
        // We have an if condition above as we do not want hashes
        // which are relative to the baseHref in our URLs
        // as if the baseHref changes, then all our bookmarks
        // would now point to different locations
        // whereas the basePageUrl will always stay the same
      }

      // Return
      return fullUrl.replace(/\#$/,'');
    };

    /**
     * History.getShortUrl(url)
     * Ensures that we have a relative URL and not a absolute URL
     * @param {string} url
     * @return {string} url
     */
    History.getShortUrl = function(url){
      // Prepare
      var shortUrl = url, baseUrl = History.getBaseUrl(), rootUrl = History.getRootUrl();

      // Trim baseUrl
      if ( History.emulated.pushState ) {
        // We are in a if statement as when pushState is not emulated
        // The actual url these short urls are relative to can change
        // So within the same session, we the url may end up somewhere different
        shortUrl = shortUrl.replace(baseUrl,'');
      }

      // Trim rootUrl
      shortUrl = shortUrl.replace(rootUrl,'/');

      // Ensure we can still detect it as a state
      if ( History.isTraditionalAnchor(shortUrl) ) {
        shortUrl = './'+shortUrl;
      }

      // Clean It
      shortUrl = shortUrl.replace(/^(\.\/)+/g,'./').replace(/\#$/,'');

      // Return
      return shortUrl;
    };


    // ====================================================================
    // State Storage

    /**
     * History.store
     * The store for all session specific data
     */
    History.store = {};

    /**
     * History.idToState
     * 1-1: State ID to State Object
     */
    History.idToState = History.idToState||{};

    /**
     * History.stateToId
     * 1-1: State String to State ID
     */
    History.stateToId = History.stateToId||{};

    /**
     * History.urlToId
     * 1-1: State URL to State ID
     */
    History.urlToId = History.urlToId||{};

    /**
     * History.storedStates
     * Store the states in an array
     */
    History.storedStates = History.storedStates||[];

    /**
     * History.savedStates
     * Saved the states in an array
     */
    History.savedStates = History.savedStates||[];

    /**
     * History.noramlizeStore()
     * Noramlize the store by adding necessary values
     */
    History.normalizeStore = function(){
      History.store.idToState = History.store.idToState||{};
      History.store.urlToId = History.store.urlToId||{};
      History.store.stateToId = History.store.stateToId||{};
    };

    /**
     * History.getState()
     * Get an object containing the data, title and url of the current state
     * @param {Boolean} friendly
     * @param {Boolean} create
     * @return {Object} State
     */
    History.getState = function(friendly,create){
      // Prepare
      if ( typeof friendly === 'undefined' ) { friendly = true; }
      if ( typeof create === 'undefined' ) { create = true; }

      // Fetch
      var State = History.getLastSavedState();

      // Create
      if ( !State && create ) {
        State = History.createStateObject();
      }

      // Adjust
      if ( friendly ) {
        State = History.cloneObject(State);
        State.url = State.cleanUrl||State.url;
      }

      // Return
      return State;
    };

    /**
     * History.getIdByState(State)
     * Gets a ID for a State
     * @param {State} newState
     * @return {String} id
     */
    History.getIdByState = function(newState){

      // Fetch ID
      var id = History.extractId(newState.url),
        str;
      
      if ( !id ) {
        // Find ID via State String
        str = History.getStateString(newState);
        if ( typeof History.stateToId[str] !== 'undefined' ) {
          id = History.stateToId[str];
        }
        else if ( typeof History.store.stateToId[str] !== 'undefined' ) {
          id = History.store.stateToId[str];
        }
        else {
          // Generate a new ID
          while ( true ) {
            id = (new Date()).getTime() + String(Math.random()).replace(/\D/g,'');
            if ( typeof History.idToState[id] === 'undefined' && typeof History.store.idToState[id] === 'undefined' ) {
              break;
            }
          }

          // Apply the new State to the ID
          History.stateToId[str] = id;
          History.idToState[id] = newState;
        }
      }

      // Return ID
      return id;
    };

    /**
     * History.normalizeState(State)
     * Expands a State Object
     * @param {object} State
     * @return {object}
     */
    History.normalizeState = function(oldState){
      // Variables
      var newState, dataNotEmpty;

      // Prepare
      if ( !oldState || (typeof oldState !== 'object') ) {
        oldState = {};
      }

      // Check
      if ( typeof oldState.normalized !== 'undefined' ) {
        return oldState;
      }

      // Adjust
      if ( !oldState.data || (typeof oldState.data !== 'object') ) {
        oldState.data = {};
      }

      // ----------------------------------------------------------------

      // Create
      newState = {};
      newState.normalized = true;
      newState.title = oldState.title||'';
      newState.url = History.getFullUrl(History.unescapeString(oldState.url||document.location.href));
      newState.hash = History.getShortUrl(newState.url);
      newState.data = History.cloneObject(oldState.data);

      // Fetch ID
      newState.id = History.getIdByState(newState);

      // ----------------------------------------------------------------

      // Clean the URL
      newState.cleanUrl = newState.url.replace(/\??\&_suid.*/,'');
      newState.url = newState.cleanUrl;

      // Check to see if we have more than just a url
      dataNotEmpty = !History.isEmptyObject(newState.data);

      // Apply
      if ( newState.title || dataNotEmpty ) {
        // Add ID to Hash
        newState.hash = History.getShortUrl(newState.url).replace(/\??\&_suid.*/,'');
        if ( !/\?/.test(newState.hash) ) {
          newState.hash += '?';
        }
        newState.hash += '&_suid='+newState.id;
      }

      // Create the Hashed URL
      newState.hashedUrl = History.getFullUrl(newState.hash);

      // ----------------------------------------------------------------

      // Update the URL if we have a duplicate
      if ( (History.emulated.pushState || History.bugs.safariPoll) && History.hasUrlDuplicate(newState) ) {
        newState.url = newState.hashedUrl;
      }

      // ----------------------------------------------------------------

      // Return
      return newState;
    };

    /**
     * History.createStateObject(data,title,url)
     * Creates a object based on the data, title and url state params
     * @param {object} data
     * @param {string} title
     * @param {string} url
     * @return {object}
     */
    History.createStateObject = function(data,title,url){
      // Hashify
      var State = {
        'data': data,
        'title': title,
        'url': url
      };

      // Expand the State
      State = History.normalizeState(State);

      // Return object
      return State;
    };

    /**
     * History.getStateById(id)
     * Get a state by it's UID
     * @param {String} id
     */
    History.getStateById = function(id){
      // Prepare
      id = String(id);

      // Retrieve
      var State = History.idToState[id] || History.store.idToState[id] || undefined;

      // Return State
      return State;
    };

    /**
     * Get a State's String
     * @param {State} passedState
     */
    History.getStateString = function(passedState){
      // Prepare
      var State, cleanedState, str;

      // Fetch
      State = History.normalizeState(passedState);

      // Clean
      cleanedState = {
        data: State.data,
        title: passedState.title,
        url: passedState.url
      };

      // Fetch
      str = JSON.stringify(cleanedState);

      // Return
      return str;
    };

    /**
     * Get a State's ID
     * @param {State} passedState
     * @return {String} id
     */
    History.getStateId = function(passedState){
      // Prepare
      var State, id;
      
      // Fetch
      State = History.normalizeState(passedState);

      // Fetch
      id = State.id;

      // Return
      return id;
    };

    /**
     * History.getHashByState(State)
     * Creates a Hash for the State Object
     * @param {State} passedState
     * @return {String} hash
     */
    History.getHashByState = function(passedState){
      // Prepare
      var State, hash;
      
      // Fetch
      State = History.normalizeState(passedState);

      // Hash
      hash = State.hash;

      // Return
      return hash;
    };

    /**
     * History.extractId(url_or_hash)
     * Get a State ID by it's URL or Hash
     * @param {string} url_or_hash
     * @return {string} id
     */
    History.extractId = function ( url_or_hash ) {
      // Prepare
      var id,parts,url;

      // Extract
      parts = /(.*)\&_suid=([0-9]+)$/.exec(url_or_hash);
      url = parts ? (parts[1]||url_or_hash) : url_or_hash;
      id = parts ? String(parts[2]||'') : '';

      // Return
      return id||false;
    };

    /**
     * History.isTraditionalAnchor
     * Checks to see if the url is a traditional anchor or not
     * @param {String} url_or_hash
     * @return {Boolean}
     */
    History.isTraditionalAnchor = function(url_or_hash){
      // Check
      var isTraditional = !(/[\/\?\.]/.test(url_or_hash));

      // Return
      return isTraditional;
    };

    /**
     * History.extractState
     * Get a State by it's URL or Hash
     * @param {String} url_or_hash
     * @return {State|null}
     */
    History.extractState = function(url_or_hash,create){
      // Prepare
      var State = null, id, url;
      create = create||false;

      // Fetch SUID
      id = History.extractId(url_or_hash);
      if ( id ) {
        State = History.getStateById(id);
      }

      // Fetch SUID returned no State
      if ( !State ) {
        // Fetch URL
        url = History.getFullUrl(url_or_hash);

        // Check URL
        id = History.getIdByUrl(url)||false;
        if ( id ) {
          State = History.getStateById(id);
        }

        // Create State
        if ( !State && create && !History.isTraditionalAnchor(url_or_hash) ) {
          State = History.createStateObject(null,null,url);
        }
      }

      // Return
      return State;
    };

    /**
     * History.getIdByUrl()
     * Get a State ID by a State URL
     */
    History.getIdByUrl = function(url){
      // Fetch
      var id = History.urlToId[url] || History.store.urlToId[url] || undefined;

      // Return
      return id;
    };

    /**
     * History.getLastSavedState()
     * Get an object containing the data, title and url of the current state
     * @return {Object} State
     */
    History.getLastSavedState = function(){
      return History.savedStates[History.savedStates.length-1]||undefined;
    };

    /**
     * History.getLastStoredState()
     * Get an object containing the data, title and url of the current state
     * @return {Object} State
     */
    History.getLastStoredState = function(){
      return History.storedStates[History.storedStates.length-1]||undefined;
    };

    /**
     * History.hasUrlDuplicate
     * Checks if a Url will have a url conflict
     * @param {Object} newState
     * @return {Boolean} hasDuplicate
     */
    History.hasUrlDuplicate = function(newState) {
      // Prepare
      var hasDuplicate = false,
        oldState;

      // Fetch
      oldState = History.extractState(newState.url);

      // Check
      hasDuplicate = oldState && oldState.id !== newState.id;

      // Return
      return hasDuplicate;
    };

    /**
     * History.storeState
     * Store a State
     * @param {Object} newState
     * @return {Object} newState
     */
    History.storeState = function(newState){
      // Store the State
      History.urlToId[newState.url] = newState.id;

      // Push the State
      History.storedStates.push(History.cloneObject(newState));

      // Return newState
      return newState;
    };

    /**
     * History.isLastSavedState(newState)
     * Tests to see if the state is the last state
     * @param {Object} newState
     * @return {boolean} isLast
     */
    History.isLastSavedState = function(newState){
      // Prepare
      var isLast = false,
        newId, oldState, oldId;

      // Check
      if ( History.savedStates.length ) {
        newId = newState.id;
        oldState = History.getLastSavedState();
        oldId = oldState.id;

        // Check
        isLast = (newId === oldId);
      }

      // Return
      return isLast;
    };

    /**
     * History.saveState
     * Push a State
     * @param {Object} newState
     * @return {boolean} changed
     */
    History.saveState = function(newState){
      // Check Hash
      if ( History.isLastSavedState(newState) ) {
        return false;
      }

      // Push the State
      History.savedStates.push(History.cloneObject(newState));

      // Return true
      return true;
    };

    /**
     * History.getStateByIndex()
     * Gets a state by the index
     * @param {integer} index
     * @return {Object}
     */
    History.getStateByIndex = function(index){
      // Prepare
      var State = null;

      // Handle
      if ( typeof index === 'undefined' ) {
        // Get the last inserted
        State = History.savedStates[History.savedStates.length-1];
      }
      else if ( index < 0 ) {
        // Get from the end
        State = History.savedStates[History.savedStates.length+index];
      }
      else {
        // Get from the beginning
        State = History.savedStates[index];
      }

      // Return State
      return State;
    };


    // ====================================================================
    // Hash Helpers

    /**
     * History.getHash()
     * Gets the current document hash
     * @return {string}
     */
    History.getHash = function(){
      var hash = History.unescapeHash(document.location.hash);
      return hash;
    };

    /**
     * History.unescapeString()
     * Unescape a string
     * @param {String} str
     * @return {string}
     */
    History.unescapeString = function(str){
      // Prepare
      var result = str,
        tmp;

      // Unescape hash
      while ( true ) {
        tmp = window.unescape(result);
        if ( tmp === result ) {
          break;
        }
        result = tmp;
      }

      // Return result
      return result;
    };

    /**
     * History.unescapeHash()
     * normalize and Unescape a Hash
     * @param {String} hash
     * @return {string}
     */
    History.unescapeHash = function(hash){
      // Prepare
      var result = History.normalizeHash(hash);

      // Unescape hash
      result = History.unescapeString(result);

      // Return result
      return result;
    };

    /**
     * History.normalizeHash()
     * normalize a hash across browsers
     * @return {string}
     */
    History.normalizeHash = function(hash){
      // Prepare
      var result = hash.replace(/[^#]*#/,'').replace(/#.*/, '');

      // Return result
      return result;
    };

    /**
     * History.setHash(hash)
     * Sets the document hash
     * @param {string} hash
     * @return {History}
     */
    History.setHash = function(hash,queue){
      // Prepare
      var adjustedHash, State, pageUrl;

      // Handle Queueing
      if ( queue !== false && History.busy() ) {
        // Wait + Push to Queue
        //History.debug('History.setHash: we must wait', arguments);
        History.pushQueue({
          scope: History,
          callback: History.setHash,
          args: arguments,
          queue: queue
        });
        return false;
      }

      // Log
      //History.debug('History.setHash: called',hash);

      // Prepare
      adjustedHash = History.escapeHash(hash);

      // Make Busy + Continue
      History.busy(true);

      // Check if hash is a state
      State = History.extractState(hash,true);
      if ( State && !History.emulated.pushState ) {
        // Hash is a state so skip the setHash
        //History.debug('History.setHash: Hash is a state so skipping the hash set with a direct pushState call',arguments);

        // PushState
        History.pushState(State.data,State.title,State.url,false);
      }
      else if ( document.location.hash !== adjustedHash ) {
        // Hash is a proper hash, so apply it

        // Handle browser bugs
        if ( History.bugs.setHash ) {
          // Fix Safari Bug https://bugs.webkit.org/show_bug.cgi?id=56249

          // Fetch the base page
          pageUrl = History.getPageUrl();

          // Safari hash apply
          History.pushState(null,null,pageUrl+'#'+adjustedHash,false);
        }
        else {
          // Normal hash apply
          document.location.hash = adjustedHash;
        }
      }

      // Chain
      return History;
    };

    /**
     * History.escape()
     * normalize and Escape a Hash
     * @return {string}
     */
    History.escapeHash = function(hash){
      // Prepare
      var result = History.normalizeHash(hash);

      // Escape hash
      result = window.escape(result);

      // IE6 Escape Bug
      if ( !History.bugs.hashEscape ) {
        // Restore common parts
        result = result
          .replace(/\%21/g,'!')
          .replace(/\%26/g,'&')
          .replace(/\%3D/g,'=')
          .replace(/\%3F/g,'?');
      }

      // Return result
      return result;
    };

    /**
     * History.getHashByUrl(url)
     * Extracts the Hash from a URL
     * @param {string} url
     * @return {string} url
     */
    History.getHashByUrl = function(url){
      // Extract the hash
      var hash = String(url)
        .replace(/([^#]*)#?([^#]*)#?(.*)/, '$2')
        ;

      // Unescape hash
      hash = History.unescapeHash(hash);

      // Return hash
      return hash;
    };

    /**
     * History.setTitle(title)
     * Applies the title to the document
     * @param {State} newState
     * @return {Boolean}
     */
    History.setTitle = function(newState){
      // Prepare
      var title = newState.title,
        firstState;

      // Initial
      if ( !title ) {
        firstState = History.getStateByIndex(0);
        if ( firstState && firstState.url === newState.url ) {
          title = firstState.title||History.options.initialTitle;
        }
      }

      // Apply
      try {
        document.getElementsByTagName('title')[0].innerHTML = title.replace('<','&lt;').replace('>','&gt;').replace(' & ',' &amp; ');
      }
      catch ( Exception ) { }
      document.title = title;

      // Chain
      return History;
    };


    // ====================================================================
    // Queueing

    /**
     * History.queues
     * The list of queues to use
     * First In, First Out
     */
    History.queues = [];

    /**
     * History.busy(value)
     * @param {boolean} value [optional]
     * @return {boolean} busy
     */
    History.busy = function(value){
      // Apply
      if ( typeof value !== 'undefined' ) {
        //History.debug('History.busy: changing ['+(History.busy.flag||false)+'] to ['+(value||false)+']', History.queues.length);
        History.busy.flag = value;
      }
      // Default
      else if ( typeof History.busy.flag === 'undefined' ) {
        History.busy.flag = false;
      }

      // Queue
      if ( !History.busy.flag ) {
        // Execute the next item in the queue
        clearTimeout(History.busy.timeout);
        var fireNext = function(){
          var i, queue, item;
          if ( History.busy.flag ) return;
          for ( i=History.queues.length-1; i >= 0; --i ) {
            queue = History.queues[i];
            if ( queue.length === 0 ) continue;
            item = queue.shift();
            History.fireQueueItem(item);
            History.busy.timeout = setTimeout(fireNext,History.options.busyDelay);
          }
        };
        History.busy.timeout = setTimeout(fireNext,History.options.busyDelay);
      }

      // Return
      return History.busy.flag;
    };

    /**
     * History.busy.flag
     */
    History.busy.flag = false;

    /**
     * History.fireQueueItem(item)
     * Fire a Queue Item
     * @param {Object} item
     * @return {Mixed} result
     */
    History.fireQueueItem = function(item){
      return item.callback.apply(item.scope||History,item.args||[]);
    };

    /**
     * History.pushQueue(callback,args)
     * Add an item to the queue
     * @param {Object} item [scope,callback,args,queue]
     */
    History.pushQueue = function(item){
      // Prepare the queue
      History.queues[item.queue||0] = History.queues[item.queue||0]||[];

      // Add to the queue
      History.queues[item.queue||0].push(item);

      // Chain
      return History;
    };

    /**
     * History.queue (item,queue), (func,queue), (func), (item)
     * Either firs the item now if not busy, or adds it to the queue
     */
    History.queue = function(item,queue){
      // Prepare
      if ( typeof item === 'function' ) {
        item = {
          callback: item
        };
      }
      if ( typeof queue !== 'undefined' ) {
        item.queue = queue;
      }

      // Handle
      if ( History.busy() ) {
        History.pushQueue(item);
      } else {
        History.fireQueueItem(item);
      }

      // Chain
      return History;
    };

    /**
     * History.clearQueue()
     * Clears the Queue
     */
    History.clearQueue = function(){
      History.busy.flag = false;
      History.queues = [];
      return History;
    };


    // ====================================================================
    // IE Bug Fix

    /**
     * History.stateChanged
     * States whether or not the state has changed since the last double check was initialised
     */
    History.stateChanged = false;

    /**
     * History.doubleChecker
     * Contains the timeout used for the double checks
     */
    History.doubleChecker = false;

    /**
     * History.doubleCheckComplete()
     * Complete a double check
     * @return {History}
     */
    History.doubleCheckComplete = function(){
      // Update
      History.stateChanged = true;

      // Clear
      History.doubleCheckClear();

      // Chain
      return History;
    };

    /**
     * History.doubleCheckClear()
     * Clear a double check
     * @return {History}
     */
    History.doubleCheckClear = function(){
      // Clear
      if ( History.doubleChecker ) {
        clearTimeout(History.doubleChecker);
        History.doubleChecker = false;
      }

      // Chain
      return History;
    };

    /**
     * History.doubleCheck()
     * Create a double check
     * @return {History}
     */
    History.doubleCheck = function(tryAgain){
      // Reset
      History.stateChanged = false;
      History.doubleCheckClear();

      // Fix IE6,IE7 bug where calling history.back or history.forward does not actually change the hash (whereas doing it manually does)
      // Fix Safari 5 bug where sometimes the state does not change: https://bugs.webkit.org/show_bug.cgi?id=42940
      if ( History.bugs.ieDoubleCheck ) {
        // Apply Check
        History.doubleChecker = setTimeout(
          function(){
            History.doubleCheckClear();
            if ( !History.stateChanged ) {
              //History.debug('History.doubleCheck: State has not yet changed, trying again', arguments);
              // Re-Attempt
              tryAgain();
            }
            return true;
          },
          History.options.doubleCheckInterval
        );
      }

      // Chain
      return History;
    };


    // ====================================================================
    // Safari Bug Fix

    /**
     * History.safariStatePoll()
     * Poll the current state
     * @return {History}
     */
    History.safariStatePoll = function(){
      // Poll the URL

      // Get the Last State which has the new URL
      var
        urlState = History.extractState(document.location.href),
        newState;

      // Check for a difference
      if ( !History.isLastSavedState(urlState) ) {
        newState = urlState;
      }
      else {
        return;
      }

      // Check if we have a state with that url
      // If not create it
      if ( !newState ) {
        //History.debug('History.safariStatePoll: new');
        newState = History.createStateObject();
      }

      // Apply the New State
      //History.debug('History.safariStatePoll: trigger');
      History.Adapter.trigger(window,'popstate');

      // Chain
      return History;
    };


    // ====================================================================
    // State Aliases

    /**
     * History.back(queue)
     * Send the browser history back one item
     * @param {Integer} queue [optional]
     */
    History.back = function(queue){
      //History.debug('History.back: called', arguments);

      // Handle Queueing
      if ( queue !== false && History.busy() ) {
        // Wait + Push to Queue
        //History.debug('History.back: we must wait', arguments);
        History.pushQueue({
          scope: History,
          callback: History.back,
          args: arguments,
          queue: queue
        });
        return false;
      }

      // Make Busy + Continue
      History.busy(true);

      // Fix certain browser bugs that prevent the state from changing
      History.doubleCheck(function(){
        History.back(false);
      });

      // Go back
      history.go(-1);

      // End back closure
      return true;
    };

    /**
     * History.forward(queue)
     * Send the browser history forward one item
     * @param {Integer} queue [optional]
     */
    History.forward = function(queue){
      //History.debug('History.forward: called', arguments);

      // Handle Queueing
      if ( queue !== false && History.busy() ) {
        // Wait + Push to Queue
        //History.debug('History.forward: we must wait', arguments);
        History.pushQueue({
          scope: History,
          callback: History.forward,
          args: arguments,
          queue: queue
        });
        return false;
      }

      // Make Busy + Continue
      History.busy(true);

      // Fix certain browser bugs that prevent the state from changing
      History.doubleCheck(function(){
        History.forward(false);
      });

      // Go forward
      history.go(1);

      // End forward closure
      return true;
    };

    /**
     * History.go(index,queue)
     * Send the browser history back or forward index times
     * @param {Integer} queue [optional]
     */
    History.go = function(index,queue){
      //History.debug('History.go: called', arguments);

      // Prepare
      var i;

      // Handle
      if ( index > 0 ) {
        // Forward
        for ( i=1; i<=index; ++i ) {
          History.forward(queue);
        }
      }
      else if ( index < 0 ) {
        // Backward
        for ( i=-1; i>=index; --i ) {
          History.back(queue);
        }
      }
      else {
        throw new Error('History.go: History.go requires a positive or negative integer passed.');
      }

      // Chain
      return History;
    };


    // ====================================================================
    // HTML5 State Support

    // Non-Native pushState Implementation
    if ( History.emulated.pushState ) {
      /*
       * Provide Skeleton for HTML4 Browsers
       */

      // Prepare
      var emptyFunction = function(){};
      History.pushState = History.pushState||emptyFunction;
      History.replaceState = History.replaceState||emptyFunction;
    } // History.emulated.pushState

    // Native pushState Implementation
    else {
      /*
       * Use native HTML5 History API Implementation
       */

      /**
       * History.onPopState(event,extra)
       * Refresh the Current State
       */
      History.onPopState = function(event,extra){
        // Prepare
        var stateId = false, newState = false, currentHash, currentState;

        // Reset the double check
        History.doubleCheckComplete();

        // Check for a Hash, and handle apporiatly
        currentHash = History.getHash();
        if ( currentHash ) {
          // Expand Hash
          currentState = History.extractState(currentHash||document.location.href,true);
          if ( currentState ) {
            // We were able to parse it, it must be a State!
            // Let's forward to replaceState
            //History.debug('History.onPopState: state anchor', currentHash, currentState);
            History.replaceState(currentState.data, currentState.title, currentState.url, false);
          }
          else {
            // Traditional Anchor
            //History.debug('History.onPopState: traditional anchor', currentHash);
            History.Adapter.trigger(window,'anchorchange');
            History.busy(false);
          }

          // We don't care for hashes
          History.expectedStateId = false;
          return false;
        }

        // Ensure
        stateId = History.Adapter.extractEventData('state',event,extra) || false;

        // Fetch State
        if ( stateId ) {
          // Vanilla: Back/forward button was used
          newState = History.getStateById(stateId);
        }
        else if ( History.expectedStateId ) {
          // Vanilla: A new state was pushed, and popstate was called manually
          newState = History.getStateById(History.expectedStateId);
        }
        else {
          // Initial State
          newState = History.extractState(document.location.href);
        }

        // The State did not exist in our store
        if ( !newState ) {
          // Regenerate the State
          newState = History.createStateObject(null,null,document.location.href);
        }

        // Clean
        History.expectedStateId = false;

        // Check if we are the same state
        if ( History.isLastSavedState(newState) ) {
          // There has been no change (just the page's hash has finally propagated)
          //History.debug('History.onPopState: no change', newState, History.savedStates);
          History.busy(false);
          return false;
        }

        // Store the State
        History.storeState(newState);
        History.saveState(newState);

        // Force update of the title
        History.setTitle(newState);

        // Fire Our Event
        History.Adapter.trigger(window,'statechange');
        History.busy(false);

        // Return true
        return true;
      };
      History.Adapter.bind(window,'popstate',History.onPopState);

      /**
       * History.pushState(data,title,url)
       * Add a new State to the history object, become it, and trigger onpopstate
       * We have to trigger for HTML4 compatibility
       * @param {object} data
       * @param {string} title
       * @param {string} url
       * @return {true}
       */
      History.pushState = function(data,title,url,queue){
        //History.debug('History.pushState: called', arguments);

        // Check the State
        if ( History.getHashByUrl(url) && History.emulated.pushState ) {
          throw new Error('History.js does not support states with fragement-identifiers (hashes/anchors).');
        }

        // Handle Queueing
        if ( queue !== false && History.busy() ) {
          // Wait + Push to Queue
          //History.debug('History.pushState: we must wait', arguments);
          History.pushQueue({
            scope: History,
            callback: History.pushState,
            args: arguments,
            queue: queue
          });
          return false;
        }

        // Make Busy + Continue
        History.busy(true);

        // Create the newState
        var newState = History.createStateObject(data,title,url);

        // Check it
        if ( History.isLastSavedState(newState) ) {
          // Won't be a change
          History.busy(false);
        }
        else {
          // Store the newState
          History.storeState(newState);
          History.expectedStateId = newState.id;

          // Push the newState
          history.pushState(newState.id,newState.title,newState.url);

          // Fire HTML5 Event
          History.Adapter.trigger(window,'popstate');
        }

        // End pushState closure
        return true;
      };

      /**
       * History.replaceState(data,title,url)
       * Replace the State and trigger onpopstate
       * We have to trigger for HTML4 compatibility
       * @param {object} data
       * @param {string} title
       * @param {string} url
       * @return {true}
       */
      History.replaceState = function(data,title,url,queue){
        //History.debug('History.replaceState: called', arguments);

        // Check the State
        if ( History.getHashByUrl(url) && History.emulated.pushState ) {
          throw new Error('History.js does not support states with fragement-identifiers (hashes/anchors).');
        }

        // Handle Queueing
        if ( queue !== false && History.busy() ) {
          // Wait + Push to Queue
          //History.debug('History.replaceState: we must wait', arguments);
          History.pushQueue({
            scope: History,
            callback: History.replaceState,
            args: arguments,
            queue: queue
          });
          return false;
        }

        // Make Busy + Continue
        History.busy(true);

        // Create the newState
        var newState = History.createStateObject(data,title,url);

        // Check it
        if ( History.isLastSavedState(newState) ) {
          // Won't be a change
          History.busy(false);
        }
        else {
          // Store the newState
          History.storeState(newState);
          History.expectedStateId = newState.id;

          // Push the newState
          history.replaceState(newState.id,newState.title,newState.url);

          // Fire HTML5 Event
          History.Adapter.trigger(window,'popstate');
        }

        // End replaceState closure
        return true;
      };

    } // !History.emulated.pushState


    // ====================================================================
    // Initialise

    /**
     * Load the Store
     */
    if ( sessionStorage ) {
      // Fetch
      try {
        History.store = JSON.parse(sessionStorage.getItem('History.store'))||{};
      }
      catch ( err ) {
        History.store = {};
      }

      // Normalize
      History.normalizeStore();
    }
    else {
      // Default Load
      History.store = {};
      History.normalizeStore();
    }

    /**
     * Clear Intervals on exit to prevent memory leaks
     */
    History.Adapter.bind(window,"beforeunload",History.clearAllIntervals);
    History.Adapter.bind(window,"unload",History.clearAllIntervals);

    /**
     * Create the initial State
     */
    History.saveState(History.storeState(History.extractState(document.location.href,true)));

    /**
     * Bind for Saving Store
     */
    if ( sessionStorage ) {
      // When the page is closed
      History.onUnload = function(){
        // Prepare
        var currentStore, item;

        // Fetch
        try {
          currentStore = JSON.parse(sessionStorage.getItem('History.store'))||{};
        }
        catch ( err ) {
          currentStore = {};
        }

        // Ensure
        currentStore.idToState = currentStore.idToState || {};
        currentStore.urlToId = currentStore.urlToId || {};
        currentStore.stateToId = currentStore.stateToId || {};

        // Sync
        for ( item in History.idToState ) {
          if ( !History.idToState.hasOwnProperty(item) ) {
            continue;
          }
          currentStore.idToState[item] = History.idToState[item];
        }
        for ( item in History.urlToId ) {
          if ( !History.urlToId.hasOwnProperty(item) ) {
            continue;
          }
          currentStore.urlToId[item] = History.urlToId[item];
        }
        for ( item in History.stateToId ) {
          if ( !History.stateToId.hasOwnProperty(item) ) {
            continue;
          }
          currentStore.stateToId[item] = History.stateToId[item];
        }

        // Update
        History.store = currentStore;
        History.normalizeStore();

        // Store
        sessionStorage.setItem('History.store',JSON.stringify(currentStore));
      };

      // For Internet Explorer
      History.intervalList.push(setInterval(History.onUnload,History.options.storeInterval));
      
      // For Other Browsers
      History.Adapter.bind(window,'beforeunload',History.onUnload);
      History.Adapter.bind(window,'unload',History.onUnload);
      
      // Both are enabled for consistency
    }

    // Non-Native pushState Implementation
    if ( !History.emulated.pushState ) {
      // Be aware, the following is only for native pushState implementations
      // If you are wanting to include something for all browsers
      // Then include it above this if block

      /**
       * Setup Safari Fix
       */
      if ( History.bugs.safariPoll ) {
        History.intervalList.push(setInterval(History.safariStatePoll, History.options.safariPollInterval));
      }

      /**
       * Ensure Cross Browser Compatibility
       */
      if ( navigator.vendor === 'Apple Computer, Inc.' || (navigator.appCodeName||'') === 'Mozilla' ) {
        /**
         * Fix Safari HashChange Issue
         */

        // Setup Alias
        History.Adapter.bind(window,'hashchange',function(){
          History.Adapter.trigger(window,'popstate');
        });

        // Initialise Alias
        if ( History.getHash() ) {
          History.Adapter.onDomLoad(function(){
            History.Adapter.trigger(window,'hashchange');
          });
        }
      }

    } // !History.emulated.pushState


  }; // History.initCore

  // Try and Initialise History
  History.init();

})(window);





// JQuery Scripts

// Generated by CoffeeScript 1.6.2
/*
jQuery Waypoints - v2.0.3
Copyright (c) 2011-2013 Caleb Troughton
Dual licensed under the MIT license and GPL license.
https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
*/


(function() {
  var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
    __slice = [].slice;

  (function(root, factory) {
    if (typeof define === 'function' && define.amd) {
      return define('waypoints', ['jquery'], function($) {
        return factory($, root);
      });
    } else {
      return factory(root.jQuery, root);
    }
  })(this, function($, window) {
    var $w, Context, Waypoint, allWaypoints, contextCounter, contextKey, contexts, isTouch, jQMethods, methods, resizeEvent, scrollEvent, waypointCounter, waypointKey, wp, wps;

    $w = $(window);
    isTouch = __indexOf.call(window, 'ontouchstart') >= 0;
    allWaypoints = {
      horizontal: {},
      vertical: {}
    };
    contextCounter = 1;
    contexts = {};
    contextKey = 'waypoints-context-id';
    resizeEvent = 'resize.waypoints';
    scrollEvent = 'scroll.waypoints';
    waypointCounter = 1;
    waypointKey = 'waypoints-waypoint-ids';
    wp = 'waypoint';
    wps = 'waypoints';
    Context = (function() {
      function Context($element) {
        var _this = this;

        this.$element = $element;
        this.element = $element[0];
        this.didResize = false;
        this.didScroll = false;
        this.id = 'context' + contextCounter++;
        this.oldScroll = {
          x: $element.scrollLeft(),
          y: $element.scrollTop()
        };
        this.waypoints = {
          horizontal: {},
          vertical: {}
        };
        $element.data(contextKey, this.id);
        contexts[this.id] = this;
        $element.bind(scrollEvent, function() {
          var scrollHandler;

          if (!(_this.didScroll || isTouch)) {
            _this.didScroll = true;
            scrollHandler = function() {
              _this.doScroll();
              return _this.didScroll = false;
            };
            return window.setTimeout(scrollHandler, $[wps].settings.scrollThrottle);
          }
        });
        $element.bind(resizeEvent, function() {
          var resizeHandler;

          if (!_this.didResize) {
            _this.didResize = true;
            resizeHandler = function() {
              $[wps]('refresh');
              return _this.didResize = false;
            };
            return window.setTimeout(resizeHandler, $[wps].settings.resizeThrottle);
          }
        });
      }

      Context.prototype.doScroll = function() {
        var axes,
          _this = this;

        axes = {
          horizontal: {
            newScroll: this.$element.scrollLeft(),
            oldScroll: this.oldScroll.x,
            forward: 'right',
            backward: 'left'
          },
          vertical: {
            newScroll: this.$element.scrollTop(),
            oldScroll: this.oldScroll.y,
            forward: 'down',
            backward: 'up'
          }
        };
        if (isTouch && (!axes.vertical.oldScroll || !axes.vertical.newScroll)) {
          $[wps]('refresh');
        }
        $.each(axes, function(aKey, axis) {
          var direction, isForward, triggered;

          triggered = [];
          isForward = axis.newScroll > axis.oldScroll;
          direction = isForward ? axis.forward : axis.backward;
          $.each(_this.waypoints[aKey], function(wKey, waypoint) {
            var _ref, _ref1;

            if ((axis.oldScroll < (_ref = waypoint.offset) && _ref <= axis.newScroll)) {
              return triggered.push(waypoint);
            } else if ((axis.newScroll < (_ref1 = waypoint.offset) && _ref1 <= axis.oldScroll)) {
              return triggered.push(waypoint);
            }
          });
          triggered.sort(function(a, b) {
            return a.offset - b.offset;
          });
          if (!isForward) {
            triggered.reverse();
          }
          return $.each(triggered, function(i, waypoint) {
            if (waypoint.options.continuous || i === triggered.length - 1) {
              return waypoint.trigger([direction]);
            }
          });
        });
        return this.oldScroll = {
          x: axes.horizontal.newScroll,
          y: axes.vertical.newScroll
        };
      };

      Context.prototype.refresh = function() {
        var axes, cOffset, isWin,
          _this = this;

        isWin = $.isWindow(this.element);
        cOffset = this.$element.offset();
        this.doScroll();
        axes = {
          horizontal: {
            contextOffset: isWin ? 0 : cOffset.left,
            contextScroll: isWin ? 0 : this.oldScroll.x,
            contextDimension: this.$element.width(),
            oldScroll: this.oldScroll.x,
            forward: 'right',
            backward: 'left',
            offsetProp: 'left'
          },
          vertical: {
            contextOffset: isWin ? 0 : cOffset.top,
            contextScroll: isWin ? 0 : this.oldScroll.y,
            contextDimension: isWin ? $[wps]('viewportHeight') : this.$element.height(),
            oldScroll: this.oldScroll.y,
            forward: 'down',
            backward: 'up',
            offsetProp: 'top'
          }
        };
        return $.each(axes, function(aKey, axis) {
          return $.each(_this.waypoints[aKey], function(i, waypoint) {
            var adjustment, elementOffset, oldOffset, _ref, _ref1;

            adjustment = waypoint.options.offset;
            oldOffset = waypoint.offset;
            elementOffset = $.isWindow(waypoint.element) ? 0 : waypoint.$element.offset()[axis.offsetProp];
            if ($.isFunction(adjustment)) {
              adjustment = adjustment.apply(waypoint.element);
            } else if (typeof adjustment === 'string') {
              adjustment = parseFloat(adjustment);
              if (waypoint.options.offset.indexOf('%') > -1) {
                adjustment = Math.ceil(axis.contextDimension * adjustment / 100);
              }
            }
            waypoint.offset = elementOffset - axis.contextOffset + axis.contextScroll - adjustment;
            if ((waypoint.options.onlyOnScroll && (oldOffset != null)) || !waypoint.enabled) {
              return;
            }
            if (oldOffset !== null && (oldOffset < (_ref = axis.oldScroll) && _ref <= waypoint.offset)) {
              return waypoint.trigger([axis.backward]);
            } else if (oldOffset !== null && (oldOffset > (_ref1 = axis.oldScroll) && _ref1 >= waypoint.offset)) {
              return waypoint.trigger([axis.forward]);
            } else if (oldOffset === null && axis.oldScroll >= waypoint.offset) {
              return waypoint.trigger([axis.forward]);
            }
          });
        });
      };

      Context.prototype.checkEmpty = function() {
        if ($.isEmptyObject(this.waypoints.horizontal) && $.isEmptyObject(this.waypoints.vertical)) {
          this.$element.unbind([resizeEvent, scrollEvent].join(' '));
          return delete contexts[this.id];
        }
      };

      return Context;

    })();
    Waypoint = (function() {
      function Waypoint($element, context, options) {
        var idList, _ref;

        options = $.extend({}, $.fn[wp].defaults, options);
        if (options.offset === 'bottom-in-view') {
          options.offset = function() {
            var contextHeight;

            contextHeight = $[wps]('viewportHeight');
            if (!$.isWindow(context.element)) {
              contextHeight = context.$element.height();
            }
            return contextHeight - $(this).outerHeight();
          };
        }
        this.$element = $element;
        this.element = $element[0];
        this.axis = options.horizontal ? 'horizontal' : 'vertical';
        this.callback = options.handler;
        this.context = context;
        this.enabled = options.enabled;
        this.id = 'waypoints' + waypointCounter++;
        this.offset = null;
        this.options = options;
        context.waypoints[this.axis][this.id] = this;
        allWaypoints[this.axis][this.id] = this;
        idList = (_ref = $element.data(waypointKey)) != null ? _ref : [];
        idList.push(this.id);
        $element.data(waypointKey, idList);
      }

      Waypoint.prototype.trigger = function(args) {
        if (!this.enabled) {
          return;
        }
        if (this.callback != null) {
          this.callback.apply(this.element, args);
        }
        if (this.options.triggerOnce) {
          return this.destroy();
        }
      };

      Waypoint.prototype.disable = function() {
        return this.enabled = false;
      };

      Waypoint.prototype.enable = function() {
        this.context.refresh();
        return this.enabled = true;
      };

      Waypoint.prototype.destroy = function() {
        delete allWaypoints[this.axis][this.id];
        delete this.context.waypoints[this.axis][this.id];
        return this.context.checkEmpty();
      };

      Waypoint.getWaypointsByElement = function(element) {
        var all, ids;

        ids = $(element).data(waypointKey);
        if (!ids) {
          return [];
        }
        all = $.extend({}, allWaypoints.horizontal, allWaypoints.vertical);
        return $.map(ids, function(id) {
          return all[id];
        });
      };

      return Waypoint;

    })();
    methods = {
      init: function(f, options) {
        var _ref;

        if (options == null) {
          options = {};
        }
        if ((_ref = options.handler) == null) {
          options.handler = f;
        }
        this.each(function() {
          var $this, context, contextElement, _ref1;

          $this = $(this);
          contextElement = (_ref1 = options.context) != null ? _ref1 : $.fn[wp].defaults.context;
          if (!$.isWindow(contextElement)) {
            contextElement = $this.closest(contextElement);
          }
          contextElement = $(contextElement);
          context = contexts[contextElement.data(contextKey)];
          if (!context) {
            context = new Context(contextElement);
          }
          return new Waypoint($this, context, options);
        });
        $[wps]('refresh');
        return this;
      },
      disable: function() {
        return methods._invoke(this, 'disable');
      },
      enable: function() {
        return methods._invoke(this, 'enable');
      },
      destroy: function() {
        return methods._invoke(this, 'destroy');
      },
      prev: function(axis, selector) {
        return methods._traverse.call(this, axis, selector, function(stack, index, waypoints) {
          if (index > 0) {
            return stack.push(waypoints[index - 1]);
          }
        });
      },
      next: function(axis, selector) {
        return methods._traverse.call(this, axis, selector, function(stack, index, waypoints) {
          if (index < waypoints.length - 1) {
            return stack.push(waypoints[index + 1]);
          }
        });
      },
      _traverse: function(axis, selector, push) {
        var stack, waypoints;

        if (axis == null) {
          axis = 'vertical';
        }
        if (selector == null) {
          selector = window;
        }
        waypoints = jQMethods.aggregate(selector);
        stack = [];
        this.each(function() {
          var index;

          index = $.inArray(this, waypoints[axis]);
          return push(stack, index, waypoints[axis]);
        });
        return this.pushStack(stack);
      },
      _invoke: function($elements, method) {
        $elements.each(function() {
          var waypoints;

          waypoints = Waypoint.getWaypointsByElement(this);
          return $.each(waypoints, function(i, waypoint) {
            waypoint[method]();
            return true;
          });
        });
        return this;
      }
    };
    $.fn[wp] = function() {
      var args, method;

      method = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (methods[method]) {
        return methods[method].apply(this, args);
      } else if ($.isFunction(method)) {
        return methods.init.apply(this, arguments);
      } else if ($.isPlainObject(method)) {
        return methods.init.apply(this, [null, method]);
      } else if (!method) {
        return $.error("jQuery Waypoints needs a callback function or handler option.");
      } else {
        return $.error("The " + method + " method does not exist in jQuery Waypoints.");
      }
    };
    $.fn[wp].defaults = {
      context: window,
      continuous: true,
      enabled: true,
      horizontal: false,
      offset: 0,
      triggerOnce: false
    };
    jQMethods = {
      refresh: function() {
        return $.each(contexts, function(i, context) {
          return context.refresh();
        });
      },
      viewportHeight: function() {
        var _ref;

        return (_ref = window.innerHeight) != null ? _ref : $w.height();
      },
      aggregate: function(contextSelector) {
        var collection, waypoints, _ref;

        collection = allWaypoints;
        if (contextSelector) {
          collection = (_ref = contexts[$(contextSelector).data(contextKey)]) != null ? _ref.waypoints : void 0;
        }
        if (!collection) {
          return [];
        }
        waypoints = {
          horizontal: [],
          vertical: []
        };
        $.each(waypoints, function(axis, arr) {
          $.each(collection[axis], function(key, waypoint) {
            return arr.push(waypoint);
          });
          arr.sort(function(a, b) {
            return a.offset - b.offset;
          });
          waypoints[axis] = $.map(arr, function(waypoint) {
            return waypoint.element;
          });
          return waypoints[axis] = $.unique(waypoints[axis]);
        });
        return waypoints;
      },
      above: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'vertical', function(context, waypoint) {
          return waypoint.offset <= context.oldScroll.y;
        });
      },
      below: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'vertical', function(context, waypoint) {
          return waypoint.offset > context.oldScroll.y;
        });
      },
      left: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'horizontal', function(context, waypoint) {
          return waypoint.offset <= context.oldScroll.x;
        });
      },
      right: function(contextSelector) {
        if (contextSelector == null) {
          contextSelector = window;
        }
        return jQMethods._filter(contextSelector, 'horizontal', function(context, waypoint) {
          return waypoint.offset > context.oldScroll.x;
        });
      },
      enable: function() {
        return jQMethods._invoke('enable');
      },
      disable: function() {
        return jQMethods._invoke('disable');
      },
      destroy: function() {
        return jQMethods._invoke('destroy');
      },
      extendFn: function(methodName, f) {
        return methods[methodName] = f;
      },
      _invoke: function(method) {
        var waypoints;

        waypoints = $.extend({}, allWaypoints.vertical, allWaypoints.horizontal);
        return $.each(waypoints, function(key, waypoint) {
          waypoint[method]();
          return true;
        });
      },
      _filter: function(selector, axis, test) {
        var context, waypoints;

        context = contexts[$(selector).data(contextKey)];
        if (!context) {
          return [];
        }
        waypoints = [];
        $.each(context.waypoints[axis], function(i, waypoint) {
          if (test(context, waypoint)) {
            return waypoints.push(waypoint);
          }
        });
        waypoints.sort(function(a, b) {
          return a.offset - b.offset;
        });
        return $.map(waypoints, function(waypoint) {
          return waypoint.element;
        });
      }
    };
    $[wps] = function() {
      var args, method;

      method = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (jQMethods[method]) {
        return jQMethods[method].apply(null, args);
      } else {
        return jQMethods.aggregate.call(null, method);
      }
    };
    $[wps].settings = {
      resizeThrottle: 100,
      scrollThrottle: 30
    };
    return $w.load(function() {
      return $[wps]('refresh');
    });
  });

}).call(this);