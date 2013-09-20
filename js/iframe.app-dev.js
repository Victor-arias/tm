"use strict";
jQuery(function($) {
    window.c = 0;
    window.slider;
    //Modelos
	window.Album = Backbone.Model.extend({
		urlRoot: '/tm/api/fotoalbum',
        defaults: {
		    id: '', 
            nombre : '',
		    url : '',
            thumb: ''
		}
	});

    window.Foto = Backbone.Model.extend({
       urlRoot: '/tm/api/foto',
        defaults: {
            id: '', 
            nombre : '', 
            album_foto : '',
            url:'',
            src : '',
            thumb: '',
            ancho: '',
            alto: ''
        } 
    });

    window.VideoAlbum = Backbone.Model.extend({
        urlRoot: '/tm/api/videoalbum',
        defaults: {
            id: '', 
            nombre : '',
            url : '',
            //thumb: ''
        }
    });

    window.Micrositio = Backbone.Model.extend({
       urlRoot: '/tm/api/micrositio',
        defaults: {
            id: '', 
            nombre : ''
        } 
    });

    //Colecciones
	window.AlbumCollection = Backbone.Collection.extend({
	    model : Album,
        url: '/tm/api/fotoalbum'
	});

    window.FotoCollection = Backbone.Collection.extend({
        model : Foto,
        url: '/tm/api/foto'
    });

    window.VideoAlbumCollection = Backbone.Collection.extend({
        model : VideoAlbum,
        url: '/tm/api/videoalbum'
    });

    //Helpers
    var template = function(id){
        return _.template( $('#' + id).html() );
    };

    //Vistas
    window.AlbumListView = Backbone.View.extend({
        template: template('albumListViewTemplate'),
        initialize:function () {
            this.collection.bind("reset", this.render, this);
            this.collection.bind("add", this.render, this);
            /*var self = this;
            this.collection.bind("add", function (album) {
                $(self.el).append(new AlbumListItemView({model:album}).render().el);
            }, this);*/
            this.render;
        },
        render:function (eventName) {
            $(this.el).html( this.template( this.model.toJSON() ) );
            _.each(this.collection.models, function (album) {
                $('.albumes').append(new AlbumListItemView({model:album}).render().el).fadeIn('slow');
            }, this);
            return this;
        }
    });

    window.AlbumListItemView = Backbone.View.extend({
        tagName:"li",
        className: 'album', 
        template: template('albumListItemViewTemplate'),
        render:function (eventName) {
            console.log(this.model);
            $(this.el).html( this.template( this.model.toJSON() ) );
            return this;
        },
        close:function () {
            $(this.el).unbind();
            $(this.el).remove();
        }
    });

    window.FotoListView = Backbone.View.extend({
        //tagName: 'ul',
        className: 'galeria',
        template: template('fotoListViewTemplate'),
        initialize:function () {
            this.collection.bind("reset", this.render, this);
            var self = this;
            this.render();
            this.collection.bind("add", this.add, this);
        },
        render:function (eventName) {
            $(this.el).html( this.template( this.model ) );
            window.c = 0;
            return this;
        },
        add: function(foto){
            var fliv = new FotoListItemView({model:foto});
            $('.fotos').append(fliv.render().el).fadeIn('slow');
            if(foto.attributes.url == '#'+Backbone.history.fragment){
                $('.foto a.' + foto.attributes.id).trigger('click');
            }
            
            if(window.c <= 0){
                console.log(window.c);
                window.slider = $('.fotos').bxSlider({
                    pager: false,
                    minSlides: 10,
                    maxSlides: 20,
                    slideWidth: 100,
                    slideMargin: 8,
                    viewportWidth: '100%'
                });
                $('.foto a.' + foto.attributes.id).trigger('click');
            }
            window.c += 1;
        }
    });

    window.FotoListItemView = Backbone.View.extend({
        tagName:"li",
        className:'foto',
        template: template('fotoListItemViewTemplate'),
        render:function (eventName) {
            $(this.el).html(this.template(this.model.toJSON()));
            return this;
        },
        events:{
            "click a": "ver"
        },
        close:function () {
            $(this.el).unbind();
            $(this.el).remove();
        },
        ver: function (e) {
            var src = e.currentTarget.dataset.src;
            $('.full').html('<img src="' + src + '" /><h2>'+e.currentTarget.dataset.nombre+'</h2>').fadeIn('slow');
            modificar_url(e.currentTarget.href, e.currentTarget.dataset.nombre);
            $('<div class="expander"></div>').appendTo('.full').fadeIn('slow').click(function() {
                if (screenfull.enabled) {
                    screenfull.toggle( $('.fancybox-outer')[0] );
                    $('.fancybox-outer').toggleClass('fullscreen');
                }
            });
            e.preventDefault();
        }
    });

    window.VideoAlbumListView = Backbone.View.extend({
        template: template('videoalbumListViewTemplate'),
        initialize:function () {
            this.collection.bind("reset", this.render, this);
            this.collection.bind("add", this.render, this);
            this.render;
        },
        render:function (eventName) {
            $(this.el).html( this.template( this.model.toJSON() ) );
            _.each(this.collection.models, function (album) {
                $('.videoalbumes').append(new VideoAlbumListItemView({model:album}).render().el).fadeIn('slow');
            }, this);
            return this;
        }
    });

    window.VideoAlbumListItemView = Backbone.View.extend({
        tagName:"li",
        className: 'videoalbum', 
        template: template('videoalbumListItemViewTemplate'),
        render:function (eventName) {
            $(this.el).html( this.template( this.model.toJSON() ) );
            return this;
        },
        close:function () {
            $(this.el).unbind();
            $(this.el).remove();
        }
    });

    //Rutas
    var AppRouter = Backbone.Router.extend({
        routes: {
            "imagenes":                 "listarAlbumes",
            "imagenes/:album(/:foto)":  "listarFotos",
            "videos":                   "listarVideoAlbumes"
            //"search/:query":        "search",  // #search/kiwis
            //"search/:query/p:page": "search"   // #search/kiwis/p7
        },
        initialize: function(){
            this.micrositio = new Micrositio();
            this.micrositio_id = $('#micrositio').data('micrositio-id');
            this.micrositio.fetch({data: {id: this.micrositio_id} });
        },
        listarAlbumes: function() {
            this.albumList = new AlbumCollection();
            this.albumList.fetch({data: {micrositio_id: this.micrositio_id} });
            this.albumListView = new AlbumListView({collection:this.albumList, model: this.micrositio});
            $('#icontainer').html(this.albumListView.render().el);
        },
        listarFotos: function (album, foto) {
            this.fotoList = new FotoCollection();
            this.fotoList.fetch( {data: {nombre: album, micrositio: this.micrositio_id} } );
            var fl = album.charAt(0).toUpperCase();
            album = fl + album.substring(1);
            console.log(album);
            console.dir(this.fotoList);
            this.fotoListView = new FotoListView({collection:this.fotoList, model: {nombre: album, foto_activa: foto} });
            $('#icontainer').html(this.fotoListView.render().el);
        },
        listarVideoAlbumes: function() {
            this.videoalbumList = new VideoAlbumCollection();
            this.videoalbumList.fetch({data: {micrositio_id: this.micrositio_id} });
            this.videoalbumListView = new VideoAlbumListView({collection:this.videoalbumList, model: this.micrositio});
            $('#icontainer').html(this.videoalbumListView.render().el);
        }
    });
    var app = new AppRouter();
    Backbone.history.start();
});
function modificar_url(pagina, nombre){
	if(!nombre) nombre = null;
	if(Modernizr.history){
		var stateObj = { pagina: nombre };
		window.history.pushState( stateObj, null, pagina );
	}
}