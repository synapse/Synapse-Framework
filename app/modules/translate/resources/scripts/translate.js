'use strict';
var app = angular
    .module('Translate', [])
    .config(function(){

    })
    .run(function(Data){

        // should request all the files
        Data.init();

    })
    .controller('MainCtrl', function(Data){

        this.Data = Data;
        this.translation = null;
        this.file = null;
        this.index = null;

        this.edit = function(translation, file, index)
        {
            this.translation = translation;
            this.file = file;
            this.index = index;
        };

        this.save = function()
        {
            var self = this;
            if(self.translation)
                self.translation.save(function(){
                    self.cancel();
                });
        };

        this.newTranslation = function(file)
        {
            var self = this;
            file.newTranslation(function(translation){
                self.translation = translation;
            });
        };

        this.newFile = function()
        {
            var filename = prompt('Please enter file name', '');
            if(filename !== null){
                this.Data.newFile(filename, function(){

                });
            }
        };

        this.delete = function()
        {
            var self = this;
            this.translation.delete(function(){
                self.file.translations.splice(self.index, 1);
                self.cancel();
            });
        };

        this.cancel = function()
        {
            this.translation = null;
            this.file = null;
            this.index = null;
            this.duplicate = false;
        };

        this.checkDouble = function()
        {
            var self = this;
            self.duplicate = false;

            angular.forEach(this.Data.files, function(file){
                angular.forEach(file.translations, function(translation){
                    if(translation.original === self.translation.original && self.translation.hash !== translation.hash){
                        self.duplicate = true;
                    }

                    if(self.duplicate) return;
                    self.duplicate = false;
                });
            });
        };
    })
    .factory('Data', function($http, File){
        var self = {
            files: []
        };

        self.init = function()
        {
            $http.get(modURL + '/files').
                success(function(data, status, headers, config) {
                    var files = [];
                    angular.forEach(data, function(f){
                        files.push(new File(f));
                    });
                    self.files = files;
                }).
                error(console.log);
        };

        self.newFile = function(name, callback)
        {
            var file = new File({
                name: name
            });

            file.save(function(f){
                self.files.push(f);
            });
        };

        return self;
    })
    .service('File', function($http, Translation){

        var self = function(f)
        {
            this.name = f.name;
            var translations = [];
            angular.forEach(f.translations, function(t,h){
                translations.push(new Translation(t, h, f.name));
            });

            this.translations = translations;
        };

        self.prototype.newTranslation = function(callback)
        {
            var translation = new Translation({}, null, this.name);
            this.translations.push(translation);
            callback(translation);
        };

        self.prototype.save = function(callback)
        {
            var t = this;
            $http.post(modURL + '/files/new', this).
                success(function(data, status, headers, config) {
                    console.log("Data", data);
                    if(!data.success){
                        alert(data.error);
                        return;
                    }
                    callback(t);
                }).
                error(console.log);
        };

        return self;
    })
    .service('Translation', function($http){

        var self = function(t, h, f)
        {
            this.hash = h;
            this.original = t.original;
            this.file = f;
            this.translations = [];

            for(var lang in t.translations) {
                this.translations.push({
                    text: t.translations[lang],
                    lang: lang
                });
            }
        };

        self.prototype.addTranslation = function(text, lang)
        {
            this.translations.push({
                text: text || null,
                lang: lang || ''
            });
        };

        self.prototype.removeTranslation = function(index)
        {
            this.translations.splice(index, 1);
        };

        self.prototype.save = function(callback)
        {
            var t = this;
            $http.post(modURL + '/files', this).
                success(function(data, status, headers, config) {
                    if(!data.success){
                        alert(data.error);
                        return;
                    }
                    t.hash = data.hash;
                    callback();
                }).
                error(console.log);
        };

        self.prototype.delete = function(callback)
        {
            var t = this;
            $http.post(modURL + '/files/delete', this).
                success(function(data, status, headers, config) {
                    if(!data.success){
                        alert(data.error);
                        return;
                    }
                    callback();
                }).
                error(console.log);
        };

        return self;
    })
    .directive('selectric', function(){
        return {
            restrict: "AC",
            ngModel: '&',
            link: function(scope, element, attrs) {
                element.selectric({
                    optionsItemBuilder: function (itemData, element, index) {
                        var img = element.val();
                        img = (typeof img != 'undefined' && img.length) ? '<img src="' + modResourcesURL + 'images/' + img + '.gif" /> ' : '';
                        return img + itemData.text;
                    },
                    maxHeight: 200
                });

                scope.$watch('ngModel',function(n, o){
                    element.selectric('refresh');
                });
            }
        };
    })
    .directive('tip', function() {
        return {
            restrict: 'C',
            link: function (scope, element, attr) {
                var options = scope.$eval(attr.options);
                element.tooltip(options);
            }
        };
    })
    ;
