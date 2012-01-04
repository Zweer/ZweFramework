/*
---

name: ZweBox

authors:
  - Niccolò Olivieri (flicofloc@gmail.com)

license:
  - MIT-style license

requires: []

provides:
  - ZweBox
  - Element.zwebox
  - Element.zweboxGallery

changelog:
  - v 1.1:
    - ported to Mootools 1.4.2
    - minor coding refactors
    - added the possibility to open zwebox from divs
  - v 1.0:
    - first implementation of everything

known issues:
  - ajax gallery -> if scrollbar the close button disapears

improvements:
  - play/pause button in gallery
  - description disapearing on mouseover
  - next image in gallery by clicking the image
  - moving the image by dragging (not for gallery)
  - title above the image
...
*/

var ZweBox = new Class({
    Implements: [Options, Events],

    options: {
        name: 'zwebox',         // Must be a valid class name
        inject: null,           // default to document.body

        zIndex: 7000,
        width: 480,
        height: 270,
        background: '#FFF',

        hideOnClick: true,
        overlay: {
            opacity: 0.7
        },

        showDuration: 400,
        showTransition: Fx.Transitions.Back.easeOut,

        closeDuration: 200,
        closeTransition: Fx.Transitions.Sine.easeOut,

        moveDuration: 1000,
        moveTransition: Fx.Transitions.Back.easeOut,

        resizeDuration: 1000,
        resizeTransition: Fx.Transitions.Back.easeOut,

        shake: {
            distance: 10,
            duration: 100,
            transition: Fx.Transitions.Bounce.easeOut,
            loops: 2
        },

        flash: {
            width: 640,
            height: 360
        },

        emergeFrom: 'top',      // The origin of the animation. It can be 'top' or 'bottom'
        mode: null,             // Forces a particular type of data. It can be 'image', 'iframe', 'inline', 'ajax' or 'flash'
        autoResize: true,
        force: null,            // Forces the size of the box
        cufon: false,           // Whether you use Cufon or not

        onOpen: function(){},
        onClose: function(){}
    },

    animations: {
        move: null,
        ajax: null
    },

    gallery: null,

    current: {
        width: 0,
        height: 0,
        src: '',
        options: {}
    },

    elements: {
        overlay: {},
        box: {},
        buttons: {
            close: {},
            div: {},
            prev: {},
            max: {},
            custom: {},
            next: {}
        },
        background: {},
        html: {},
        move: {}
    },

    visible: false,
    maximized: false,

    mode: 'image',

    videoRegs: {
        swf: {
            reg: /[^\.]\.(swf)\s*$/i
        },
        youtube: {
            reg: /youtube\.com\/watch/i,
            split: '=',
            index: 1,
            iframe: 1,
            url: "http://www.youtube.com/embed/%id%?autoplay=1&fs=1&rel=0"
        },
        metacafe: {
            reg: /metacafe\.com\/watch/i,
            split: '/',
            index: 4,
            url: "http://www.metacafe.com/fplayer/%id%/.swf?playerVars=autoPlay=yes"
        },
        dailymotion: {
            reg: /dailymotion\.com\/video/i,
            split: '/',
            index: 4,
            url: "http://www.dailymotion.com/swf/video/%id%?additionalInfos=0&autoStart=1"
        },
        google: {
            reg: /google\.com\/videoplay/i,
            split: '=',
            index: 1,
            url: "http://video.google.com/googleplayer.swf?autoplay=1&hl=en&docId=%id%"
        },
        vimeo: {
            reg: /vimeo\.com/i,
            split: '/',
            index: 3,
            iframe: 1,
            url: "http://player.vimeo.com/video/%id%?hd=1&autoplay=1&show_title=1&show_byline=1&show_portrait=0&color=&fullscreen=1"
        },
        megavideo: {
            reg: /megavideo.com/i,
            split: '=',
            index: 1,
            url: "http://www.megavideo.com/v/%id%"
        },
        gametrailers: {
            reg: /gametrailers.com/i,
            split: '/',
            index: 5,
            url: "http://www.gametrailers.com/remote_wrap.php?mid=%id%"
        },
        collegehumornew: {
            reg: /collegehumor.com\/video\//i,
            split: 'video/',
            index: 1,
            url: "http://www.collegehumor.com/moogaloop/moogaloop.jukebox.swf?autostart=true&fullscreen=1&use_node_id=true&clip_id=%id%"
        },
        collegehumor: {
            reg: /collegehumor.com\/video:/i,
            split: 'video:',
            index: 1,
            url: "http://www.collegehumor.com/moogaloop/moogaloop.swf?autoplay=true&fullscreen=1&clip_id=%id%"
        },
        ustream: {
            reg: /ustream.tv/i,
            split: '/',
            index: 4,
            url: "http://www.ustream.tv/flash/video/%id%?loc=%2F&autoplay=true&vid=%id%&disabledComment=true&beginPercent=0.5331&endPercent=0.6292&locale=en_US"
        },
        twitvid: {
            reg: /twitvid.com/i,
            split: '/',
            index: 3,
            url: "http://www.twitvid.com/player/%id%"
        },
        wordpress: {
            reg: /v.wordpress.com/i,
            split: '/',
            index: 3,
            url: "http://s0.videopress.com/player.swf?guid=%id%&v=1.01"
        },
        vzaar: {
            reg: /vzaar.com\/videos/i,
            split: '/',
            index: 4,
            url: "http://view.vzaar.com/%id%.flashplayer?autoplay=true&border=none"
        }
    },
    mapsReg: {
        bing: {
            reg: /bing.(.*)\/maps/i,
            split: '?',
            index: 1,
            url: "http://www.bing.com/maps/embed/?emid=3ede2bc8-227d-8fec-d84a-00b6ff19b1cb&w=%width%&h=%height%&%id%"
        },
        streetview: {
            reg: /maps.google.(.*)layer=c/i,
            split: '?',
            index: 1,
            url: "http://maps.google.com/?output=svembed&%id%"
        },
        google: {
            reg: /maps.google/i,
            split: '?',
            index: 1,
            url: "http://maps.google.com/?output=embed&%id%"
        }
    },
    imgsReg: /\.(jpg|jpeg|gif|png|bmp|tiff)(.*)?$/i,

    initialize: function(options) {
        this.setOptions(options);
        this.options.inject = this.options.inject || document.body;

        this._createOverlay();
        this._createElements();
        this._addEvents();

        this.refreshLinks();
        this.close();
    },

    _createOverlay: function() {
        this.elements.overlay = new Overlay(this.options.inject, {
            id: this.options.name + '-overlay-' + String.uniqueID(),
            duration: this.options.showDuration,
            opacity: this.options.overlay.opacity,
            zIndex: this.options.zIndex - 1,

            onClick: function(e) {
                if(this.current.options.hideOnClick)
                    this.close(e);
            }.bind(this)
        });
    }.protect(),

    _createElements: function() {
        this.elements.box = new Element('div', { 'class': this.options.name + '-box ' + this.options.name + '-mode-image' }).adopt(
            new Element('div', { 'class': this.options.name + '-border-top-left' }),
            new Element('div', { 'class': this.options.name + '-border-top-middle' }),
            new Element('div', { 'class': this.options.name + '-border-top-right' }),

            this.elements.buttons.close = new Element('a', { 'class': this.options.name + '-button-close', href: '#' }).adopt(
                new Element('span', { text: Locale.get('ZweBox.close') })
            ),

            this.elements.buttons.div = new Element('div', { 'class': this.options.name + '-buttons' }).adopt(
                new Element('div', { 'class': this.options.name + '-buttons-init' }),

                this.elements.buttons.prev = new Element('a', { 'class': this.options.name + '-button-left', href: '#' }).adopt(
                    new Element('span', { text: Locale.get('ZweBox.prev') })
                ),
                this.elements.buttons.max = new Element('a', { 'class': this.options.name + '-button-max', href: '#' }).adopt(
                    new Element('span', { text: Locale.get('ZweBox.max') })
                ),

                this.elements.buttons.custom = new Element('div', { 'class': this.options.name + '-buttons-custom' }),

                this.elements.buttons.next = new Element('a', { 'class': this.options.name + '-button-right', href: '#' }).adopt(
                    new Element('span', { text: Locale.get('ZweBox.next') })
                ),

                new Element('div', { 'class': this.options.name + '-buttons-end' })
            ),

            this.elements.background = new Element('div', { 'class': this.options.name + '-background' }),
            this.elements.html = new Element('div', { 'class': this.options.name + '-html' }),

            new Element('div', { 'class': this.options.name + '-border-bottom-left' }),
            new Element('div', { 'class': this.options.name + '-border-bottom-middle' }),
            new Element('div', { 'class': this.options.name + '-border-bottom-right' })
        );

        this.elements.move = new Element('div', {
            'class': this.options.name + '-move ' + ZweBox.PlaceHolder,
            styles: {
                position: 'absolute',
                zIndex: this.options.zIndex,
                top: -999,
                left: -999
            }
        }).inject(this.options.inject).adopt(this.elements.box);

        this.elements.move.store('ZweBox', this);
    }.protect(),

    _addEvents: function() {
        this.elements.buttons.close.addEvent('click', function(e) {
            e.preventDefault();
            this.close(e);
        }.bind(this));

        this.elements.buttons.max.addEvent('click', function(e) {
            e.preventDefault();
            this.maxiMinimize();
        }.bind(this));

        var WindowEvent = function() {
            if(this.visible) {
                this.maxiMinimize(true);
            }
        }.bind(this);

        window.addEvents({
            resize: WindowEvent,
            scroll: WindowEvent
        });

        document.addEvent('keydown', function(event) {
            if(this.visible) {
                if(event.code == 27 && this.current.options.hideOnClick) { // esc
                    this.close();
                }

                if(this.gallery && this.gallery.total > 1) {
                    if(event.code == 37) { // left arrow
                        this.elements.buttons.prev.fireEvent('click', event);
                    } else if(event.code == 39) { // right arrow
                        this.elements.buttons.next.fireEvent('click', event);
                    }
                }
            }
        }.bind(this));
    }.protect(),

    refreshLinks: function() {
        $$('a.' + this.options.name + ', area.' + this.options.name).each(function(element) {
            element.removeEvents('click').addEvent('click', function(e){
                e.preventDefault();
                element.blur();

                this.show(element);
            }.bind(this));
        }.bind(this));
    },

    maxiMinimize: function(forceMinimize) {
        if(this.maximized || forceMinimize) {
            this.maximized = false;
            this.elements.buttons.max.removeClass(this.options.name + '-button-min').addClass(this.options.name + '-button-max');

            if(!forceMinimize)
                this._loading();

            var objSize = this._calculate(this.current.width, this.current.height);

            if(this.current.options.force)
                this._resize(this.current.options.width, this.current.options.height);
            else if(this.current.options.autoResize) {
                if(this.mode == 'image' && !this.elements.buttons.custom.match(':empty'))
                    this.elements.buttons.div.show();
                this._resize(objSize.x, objSize.y);
            } else
                this._resize(this.current.width, this.current.height);
        } else {
            this.maximized = true;
            this.elements.buttons.max.removeClass(this.options.name + '-button-max').addClass(this.options.name + '-button-min');

            this._loading();

            if(this.mode == 'image')
                this.elements.buttons.div.show();
            this._resize(this.current.width, this.current.height);
        }
    },

    _loading: function() {
        this._changeMode('image');

        this.elements.html.empty();
        this.elements.background.empty();
        this.elements.background.addClass(this.options.name + '-loading');

        this.elements.buttons.div.hide();

        if(!this.visible) {
            this._moveBox(this.options.width, this.options.height);
            this._resize(this.options.width, this.options.height);
        }
    },

    _changeMode: function(mode) {
        if(mode != this.mode) {
            this.elements.box.removeClass(this.options.name + '-mode-' + this.mode);
            this.mode = mode;
            this.elements.box.addClass(this.options.name + '-mode-' + this.mode);
        }

        this.elements.move.setStyle('overflow', 'visible');
    },

    createGallery: function(currentElement) {
        var galleryElements = null;
        if(typeOf(currentElement) == 'array') {
            galleryElements = currentElement;
            currentElement = galleryElements[0];
        }

        if(this.gallery == null) {
            this.gallery = {
                images: [],
                current: 0,
                total: 0
            };

            var rel = (currentElement.get('rel') || '').trim();

            if(rel && rel != '' && rel != 'nofollow') {
                var elementsToIterate = null;

                if(galleryElements)
                    elementsToIterate = galleryElements;
                else
                    elementsToIterate = $$('a.' + this.options.name + '[rel=' + rel + '], area.' + this.options.name + '[rel=' + rel + ']');

                elementsToIterate.each(function(sibling, index) {
                    this.gallery.images.push(sibling);

                    if(sibling == currentElement)
                        this.gallery.current = index;
                }.bind(this));

                this.gallery.total = this.gallery.images.length;

                this.elements.buttons.prev.removeEvents('click').addEvent('click', function(e) {
                    e.preventDefault();

                    if(this.gallery.current <= 0)
                        this.gallery.current = this.gallery.total - 1;
                    else
                        this.gallery.current--;

                    this.show(this.gallery.images[this.gallery.current]);
                }.bind(this));

                this.elements.buttons.next.removeEvents('click').addEvent('click', function(e) {
                    e.preventDefault();

                    if(this.gallery.current >= this.gallery.total - 1)
                        this.gallery.current = 0;
                    else
                        this.gallery.current++;

                    this.show(this.gallery.images[this.gallery.current]);
                }.bind(this));
            }
        }
        
        if(this.gallery.total > 1) {
            this.elements.buttons.div.show();
            this.elements.buttons.prev.show();
            this.elements.buttons.next.show();
        } else {
            this.elements.buttons.prev.hide();
            this.elements.buttons.next.hide();
        }
    },

    _unserialize: function(currentElement) {
        var regex = new RegExp(this.options.name + "\\[(.*)?\\]$", "i"),
            href = currentElement.get('href'),
            data = [],
            baseUrl = '',
            serialized = {
                width: currentElement.retrieve('ZweBox.Options', {}).width,
                height: currentElement.retrieve('ZweBox.Options', {}).height,
                title: currentElement.get('title')
            };

        if(href.match(/#/))
            href = href.slice(0, href.indexOf('#'));

        if(href.indexOf('?') >= 0) {
            baseUrl = href.substr(0, href.indexOf('?') + 1);
            data = href.substr(href.indexOf('?') + 1).split('&');
            data.each(function(propertyString){
                var property = propertyString.split('='),
                    key = property[0],
                    value = property[1];

                if(key.match(regex)) {
                    if(isFinite(value))
                        value = parseInt(value);
                    else if(value.toLowerCase() == 'true')
                        value = true;
                    else if(value.toLowerCase() == 'false')
                        value = false;

                    serialized[key.match(regex)[1]] = value;
                } else {
                    baseUrl += propertyString + '&';
                }
            });

            currentElement.store('ZweBox.href', baseUrl.substr(0, baseUrl.length - 1));
        }

        return serialized;
    },

    _calculate: function(x, y) {
        var maxX = window.getSize().x - 50,
            maxY = window.getSize().y - 50;

        if(x > maxX) {
            y *= (maxX / x);
            x = maxX;

            if(y > maxY)
            {
                x *= (maxY / y);
                y = maxY;
            }
        } else if(y > maxY) {
            x *= (maxY / y);
            y = maxY;
        }

        return {
            x: parseInt(x),
            y: parseInt(y)
        };
    },

    _customButtons: function(buttons, element) {
        this.elements.buttons.custom.empty();

        buttons.each(function(button, index) {
            this.elements.buttons.custom.adopt(
                new Element('a', {
                    'class': button['class'],
                    href: '#',
                    html: button.html,

                    events: {
                        click: function(e){
                            e.preventDefault();
                            if(button.callback && typeOf(button.callback) == 'function')
                                button.callback(element);
                        }.bind(this)
                    }
                })
            );
        }, this);

        this.elements.buttons.div.show();
    },

    _moveBox: function(width, height) {
        var size = window.getSize(),
            scroll = window.getScroll(),
            x = 0,
            y = 0;

        width = width ? width : this.elements.box.getSize().x;
        height = height ? height : this.elements.box.getSize().y;

        x = scroll.x + (size.x - width) / 2;

        if(this.visible) {
            y = scroll.y + (size.y - height) / 2;
        } else if(this.options.emergeFrom == 'bottom') {
            y = scroll.y + size.y + 14;
        } else { // this.options.emergeFrom == 'top'
            y = scroll.y - height - 14;
        }

        if(this.visible) {
            if(!this.animations.move) {
                this._morph(this.elements.move, {
                    left: x
                }, 'move');
            }

            this._morph(this.elements.move, {
                top: y
            }, 'move');
        } else {
            this.elements.move.setStyles({
                left: x,
                top: y
            });
        }
    },

    _resize: function(x, y) {
        if(this.visible) {
            var size = window.getSize(),
                scroll = window.getScroll(),
                left = scroll.x + (size.x - x - 14) / 2,
                top = scroll.y + (size.y - y - 14) / 2;

            if(Browser.ie || (Browser.firefox && parseFloat(Browser.version) < 1.9))
                y += 4;

            this.animations.move = true;

            this._morph(this.elements.move, {
                left: (this.maximized && left < 0) ? 0 : left,
                top: (this.maximized && (y + 14) > size.y) ? scroll.y : top
            }, 'move', function(){ this.animations.move = false; });

            this._morph(this.elements.html, { height: y - 20 }, 'resize');
            this._morph(this.elements.box, { width: x + 14, height: y - 20 }, 'resize', function(){});
            this._morph(this.elements.buttons.div, { width: x, height: y }, 'resize');
            this._morph(this.elements.background, { width: x, height: y }, 'resize', function(){ this.elements.background.fireEvent('complete'); })
        } else {
            this.elements.html.setStyle('height', 20);
            this.elements.box.setStyles({ width: x + 14, height: y - 20 });
            this.elements.buttons.div.setStyles({ width: x, height: y });
            this.elements.background.setStyles({ width: x, height: y });
        }
    },

    _morph: function(element, properties, mode, callback, queue) {
        if(queue) {
            element.get('morph').chain(function(){
                this._morph(element, properties, mode, callback);
            }.bind(this));
        } else {
            element.set('morph', {
                duration: this.options[mode + 'Duration'],
                transition: this.options[mode + 'Transition']
            });
            element.get('morph').chain(typeOf(callback) == 'function' ? callback.bind(this) : function(){});
            element.morph(properties);
        }
    },

    _appendHtml: function(element, mode) {
        if(mode && typeOf(mode) != null)
            this._changeMode(mode);

        this.current.width += 30;
        this.current.height += 30;

        this._resize(this.current.width, this.current.height);

        this.elements.background.addEvent('complete', function() {
            this.elements.background.removeClass(this.options.name + '-loading');
            this.elements.html.adopt(element);
            this.elements.background.removeEvents('complete');

            if(this.current.options.cufon && Cufon && typeOf(Cufon) != null)
                Cufon.refresh();
        }.bind(this));
    },

    _swf2html: function(href) {
        if(this.current.options.flashVars && this.current.options.flashVars == '')
            this.current.options.flashVars = 'autostart=1&autoplay=1&fullscreenbutton=1';

        var str  = '<object width="' + this.current.options.width + '" height="' + this.current.options.height + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">';
            str += '<param name="movie" value="' + href + '" style="margin:0; padding:0;" />';
            str += '<param name="allowFullScreen" value="true" />';
            str += '<param name="allowscriptaccess" value="always" />';
            str += '<param name="wmode" value="transparent" />';
            str += '<param name="autostart" value="true" />';
            str += '<param name="autoplay" value="true" />';
            str += '<param name="flashvars" value="' + this.current.options.flashVars + '" />';
            str += '<param name="width" value="' + this.current.options.width + '" />';
            str += '<param name="height" value="' + this.current.options.height + '" />';
            str += '<embed src="' + href + '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" autostart="true" autoplay="true" flashvars="' + this.current.options.flashVars + '" wmode="transparent" width="' + this.current.options.width + '" height="' + this.current.options.height + '" style="margin:0; padding:0;"></embed>';
            str += '</object>';
        return str;
    },

    open: function() {
        this.visible = true;

        if(Browser.ie) {
            this.elements.move.setStyle('filter', '');
            this.elements.buttons.div.setStyle('position', 'static').setStyle('position', 'absolute');
        }

        this.elements.move.setStyles({ display: 'block', overflow: 'visible' }).show();
        this.elements.overlay.open();
    },

    close: function() {
        this.visible = false;
        this.gallery = null;

        this.fireEvent('close');
        this.removeEvents('close');
        this.elements.overlay.close();

        if(Browser.ie) {
            this.elements.background.empty();
            this.elements.html.empty();
            this.elements.buttons.custom.empty();
            this.elements.move.setStyle('display', 'none');
            this._moveBox();
        } else {
            this._morph(this.elements.move, { opacity: 0, top: this.elements.move.getPosition().y - 40 }, 'close', function(){
                this.elements.background.empty();
                this.elements.html.empty();
                this.elements.buttons.custom.empty();
                this._moveBox();
                this.elements.move.setStyles({
                    display: 'none',
                    opacity: 1,
                    overflow: 'visible'
                });
            });
        }

        this.elements.background.removeEvents('complete');
    },

    show: function(element) {
        var type = '',
            beforeOpen = this.visible,
            options = Object.merge({}, this.options, element.retrieve('ZweBox.Options', {}), this._unserialize(element)),
            size = window.getSize(),
            href = element.retrieve('ZweBox.href', null) || element.get('href');

        if(options.onOpen && typeOf(options.onOpen) == 'function')
            this.addEvent('open', options.onOpen.bind(this, element));
        if(options.onClose && typeOf(options.onClose) == 'function')
            this.addEvent('close', options.onClose.bind(this, element));

        if(typeOf(element) != 'element')
            return false;

        if(this.animations.ajax)
            this.animations.ajax.cancel();

        this._loading();

        this.open();

        if(!beforeOpen)
            this._moveBox();

        this.createGallery(element, options);

        if(options.width && String.from(options.width).indexOf('p') > 0)
            options.width = (size.x - 20) * options.width.substring(0, String.from(options.width).indexOf('p')) / 100;
        if(options.height && String.from(options.height).indexOf('p') > 0)
            options.height = (size.y - 20) * options.height.substring(0, String.from(options.height).indexOf('p')) / 100;

        this.elements.background.removeEvents('complete');
        this.elements.buttons.max.removeClass(this.options.name + '-button-min').addClass(this.options.name + '-button-max');
        this.maximized = !(options.move > 0 || (options.move == -1 && options.autoResize));

        if(typeOf(options.buttons) == 'array')
            this._customButtons(options.buttons, element);

        if(!this.elements.buttons.custom.match(':empty'))
            this.elements.buttons.div.show();

        if(options.type && options.type != '')
            type = options.type;
        else if(options.iframe)
            type = 'iframe';
        else if(href.match(this.imgsReg))
            type = 'image';
        else {
            Object.each(this.videoRegs, function(reg) {
                if(href.split('?')[0].match(reg.reg)) {
                    if(reg.split) {
                        var videoID = href.split(reg.split)[reg.index].split('?')[0].split('&')[0];
                        href = reg.url.replace('%id%', videoID);
                    }

                    type = reg.iframe ? 'iframe' : 'flash';
                    options.width = options.width ? options.width : options.flash.width;
                    options.height = options.height ? options.height : options.flash.height;

                    return false;
                }
            }, this);

            Object.each(this.mapsReg, function(reg) {
                if(href.match(reg.reg)) {
                    type = 'iframe';
                    if(reg.split) {
                        var ID = href.split(reg.split)[reg.index];
                        href = reg.url.replace('%id%', ID).replace('%width%', options.width).replace('%height%', options.height);
                    }
                }
            }, this);

            if(type == '') {
                if(href.match(/#/)) {
                    var obj = href.substr(href.indexOf('#') + 1);
                    if(obj.length > 0) {
                        type = 'inline';
                        href = obj;
                    } else
                        type = 'ajax';
                } else
                    type = 'ajax';
            }
        }

        switch(type) {
            case 'image':
                this.elements.buttons.max.hide();

                var image = new Image(),
                    width, height;

                image.onload = function() {
                    image.onload = function(){};

                    if(!this.visible)
                        return false;

                    this.current = {
                        width: image.width,
                        height: image.height,
                        src: image.src,
                        options: options
                    };

                    if(options.width && options.height) {
                        options.force = true;
                        width = options.width;
                        height = options.height;
                    } else {
                        if(options.autoResize) {
                            var objSize = this._calculate(image.width, image.height);
                            width = objSize.x;
                            height = objSize.y;

                            if(image.width != width || image.height != height) {
                                this.maximized = false;
                                this.elements.buttons.div.show();
                                this.elements.buttons.max.show();
                            }
                        } else {
                            width = image.width;
                            height = image.height;
                        }
                    }

                    this._resize(width, height);

                    this.elements.background.addEvent('complete', function() {
                        if(!this.visible)
                            return false;

                        this._changeMode('image');

                        this.elements.background.empty();
                        this.elements.html.empty();

                        if(options.title && options.title != '')
                            this.elements.background.adopt(
                                new Element('div', {
                                    'class': this.options.name + '-title',
                                    text: options.title
                                })
                            );

                        document.id(image).hide();
                        this.elements.background.adopt(image);
                        document.id(image).get('tween').cancel();
                        document.id(image).set('tween', {
                            duration: this.options.showDuration,

                            onComplete: function(){
                                this.elements.background.removeClass(this.options.name + '-loading');
                            }.bind(this)
                        }).show();
                    }.bind(this));
                }.bind(this);

                image.onError = function() {
                    this.error(Locale.get('ZweBox.error_loading_image'));
                }.bind(this);

                image.src = href;
            break;

            case 'inline':
                var inlineElement = document.id(href);

                if(options.width || options.height ) {
                    if(options.width)
                        inlineElement.setStyle('width', options.width);
                    if(options.height)
                        inlineElement.setStyle('height', options.height);
                }

                this.current = {
                    width: inlineElement.getDimensions().x,
                    height: inlineElement.getDimensions().y,
                    src: href,
                    options: options
                };

                this.elements.buttons.max.hide();
                this._appendHtml(inlineElement.clone().show(), 'html');
            break;

            case 'ajax':
                if(!options.width || !options.height) {
                    options.width = this.options.width;
                    options.height = this.options.height;
                }

                this.current = {
                    width: options.width,
                    height: options.height,
                    src: href,
                    options: options
                };

                this.elements.buttons.max.hide();
                this.animations.ajax = new Request({
                    url: href,
                    noCache: true,
                    evalScripts: true,

                    onFailure: function() {
                        this.error(Locale.get('ZweBox.error_loading_ajax'));
                    }.bind(this),
                    onSuccess: function(html) {
                        this._appendHtml(new Element('div', { html: html }), 'html');
                    }.bind(this)
                }).send();
            break;

            case 'flash':
                this.current = {
                    width: options.width || options.flash.width,
                    height: options.height || options.flash.height,
                    src: href,
                    options: options
                };

                var flash = this._swf2html(href);
                this._appendHtml(new Element('div', { html: flash }), 'html');
            break;

            case 'iframe':
                if(!options.width || !options.height) {
                    options.width = this.options.width;
                    options.height = this.options.height;
                }

                this.current = {
                    width: options.width,
                    height: options.height,
                    src: href,
                    options: options
                };

                this._appendHtml(new Element('iframe', {
                    id: this.options.name + '-iframe-' + String.uniqueID(),
                    frameborder: 0,
                    src: href,
                    styles: Object.append({
                        margin: 0,
                        padding: 0
                    }, options)
                }), 'html');
            break;
        }

        this.fireEvent('open');
        this.removeEvents('open');
    },

    shake: function(how, callback) {
        var distance = this.options.shake.distance,
            position = how && how == 'top' ? this.elements.move.getPosition().y : this.elements.move.getPosition().x,
            shakeTween = new Fx.Tween(this.elements.move, {
                property: how && how == 'top' ? 'top' : 'left',
                duration: this.options.shake.duration,
                transition: this.options.shake.transition,
                link: 'chain'
            });

        for(var i = 0; i < this.options.shake.loops; ++i) {
            shakeTween.start(position + distance);
            shakeTween.start(position - distance);
        }

        shakeTween.start(position + distance);
        shakeTween.start(position);

        if(callback && typeOf(callback) == 'function')
            shakeTween.chain(callback.bind(this));
    },

    error: function(message) {
        alert(message);
        this.close();
    }
});

ZweBox.PlaceHolder = String.uniqueID();

ZweBox.getInstance = function(instanceName) {
    var instance = null;

    $$('.' + ZweBox.PlaceHolder).each(function(element) {
        instance = element.retrieve('ZweBox');

        if(instanceName && instance.options.name == instanceName)
            return instance;

        if(element.isDisplayed() && element.isVisible())
            return instance;
    });

    if(instance)
        return instance;
    else
        return new ZweBox();
};

Element.implement({
    zwebox: function(options, element) {
        var instance = ZweBox.getInstance(options.name);
        element = element ? element : this;

        switch(typeOf(element)) {
            case 'string':
                element = new Element('a', { href: element, 'class': instance.options.name });
            break;

            case 'element':
                if(element.get('href') != null)
                    break;

                if(element.get('id') == null)
                    element.set('id', String.uniqueID());

                element.inject(instance.options.inject).setStyle('display', 'none');
                element = new Element('a', { href: '#' + element.get('id'), 'class': instance.options.name });
            break;
        }

        element.store('ZweBox.Options', options);

        this.removeEvents('click').addEvent('click', function(e) {
            e.preventDefault();
            this.blur();

            instance.show(element);
        });
    },

    zweboxGallery: function(options, elements) {
        if(!elements || typeOf(elements) != 'array')
            return this.zwebox(options, elements);

        var instance = ZweBox.getInstance(options.name),
            rel = String.uniqueID();
        elements = elements.map(function(element){
            if(typeOf(element) == 'string')
                element = new Element('a', { href: element, 'class': instance.options.name });

            element.store('ZweBox.Options', options);
            element.set('rel', rel);

            return element;
        });

        this.removeEvents('click').addEvent('click', function(e){
            e.preventDefault();
            this.blur();

            instance.createGallery(elements);
            instance.show(elements[0]);
        });
    }
});

Locale.define('en-US', 'ZweBox', {
    close: 'Close',
    prev: 'Previous',
    max: 'Maximize',
    next: 'Next',

    error_loading_image: 'The requested image cannot be loaded. Please try again later',
    error_loading_ajax: 'The requested content cannot be loaded. Please try again later',
    error_size: 'You need to specify the size of the box'
});

Locale.define('it-IT', 'ZweBox', {
    close: 'Chiudi',
    prev: 'Precedente',
    max: 'Massimizza',
    next: 'Successiva',

    error_loading_image: 'L\'immagine richiesta non può essere caricata. Si prega di riprovare più tardi',
    error_loading_ajax: 'Il contenuto richiesto non può essere caricato. Si prega di riprovare più tardi',
    error_size: 'Bisogna specificare le dimensioni della box'
});