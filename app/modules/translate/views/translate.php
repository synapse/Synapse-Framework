<?php $uri = App::getURI() ?>
<!doctype html>
<html class="no-js" lang="" ng-app="Translate">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Synapse MVC - Translate Module</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= $uri->base() ?>app/modules/translate/resources/styles/selectric.css">
        <link rel="stylesheet" href="<?= $uri->base() ?>app/modules/translate/resources/styles/translate.css">

    </head>
    <body>

        <div class="container" id="content" ng-controller="MainCtrl as main">

            <div class="row">
                <div class="col-sm-2">
                    <button class="btn btn-primary btn-sm btn-block" ng-click="main.newFile()">New File</button>
                </div>
                <div class="col-sm-2 col-sm-offset-8">
                    <input class="form-control" ng-model="main.filter" placeholder="Search..." />
                </div>
            </div>

            <br>

            <div ng-repeat="file in main.Data.files">
                <h4 class="pull-left">{{ file.name }}</h4>
                <button type="button" class="btn btn-xs btn-success pull-right" ng-click="main.newTranslation(file)">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
                <div class="clearfix"></div>

                <ul class="list-group">
                    <li class="list-group-item" ng-repeat="translation in file.translations | filter:main.filter">
                        <div class="row">
                            <div class="col-xs-11">
                                <span class="original-text" ng-class="{'text-danger': !translation.hash}">{{ translation.original }}</span>
                                <span class="translations">
                                    <small class="text-muted tip" data-original-title="{{ t.lang }}" ng-repeat="t in translation.translations">
                                        <img ng-src="<?= $uri->base() ?>app/modules/translate/resources/images/{{ t.lang }}.gif" alt="" />
                                    </small>
                                </span>
                            </div>
                            <div class="col-xs-1 pad-left-none">
                                <button type="button" class="btn btn-xs btn-default btn-block" ng-click="main.edit(translation, file, $index)">...</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="modal" ng-if="main.translation" style="display: block">
                <div class="modal-dialog" style="width: 800px">
                    <div class="modal-content">
                        <div class="modal-body">
                            <ng-form name="translationForm" novalidate>
                                <div class="form-group" ng-class="{'has-error': main.duplicate}">
                                    <label>Original text</label>
                                    <textarea class="form-control" rows="2" ng-model="main.translation.original" ng-change="main.checkDouble()" required></textarea>
                                    <small ng-if="main.duplicate" class="help-block">This text is already present in another translation</small>
                                </div>

                                <hr>
                                <label>Translations</label>
                                <ul class="list-group">
                                    <li class="list-group-item" ng-repeat="translation in main.translation.translations">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <select class="form-control selectric" ng-model="translation.lang" required>
                                                    <option value="" disabled>-</option>
                                                    <?php foreach($languages as $i=>$language): ?>
                                                    <option value="<?= $language ?>"><?= $language ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-10 pad-none">
                                                <textarea class="form-control" rows="1" ng-model="translation.text" ng-disabled="!translation.lang" required></textarea>
                                            </div>
                                            <div class="col-sm-1">
                                                <button class="btn btn-danger btn-block btn-sm" ng-click="main.translation.removeTranslation($index)">&times;</button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <div class="row">
                                    <div class="col-sm-2 col-sm-offset-5">
                                        <button class="btn btn-default btn-block btn-xs" ng-click="main.translation.addTranslation()">Add translation</button>
                                    </div>
                                </div>
                            </ng-form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm pull-left" ng-click="main.delete()">Delete</button>
                            <button type="button" class="btn btn-default btn-sm" ng-click="main.cancel()">Close</button>
                            <button type="button" class="btn btn-success btn-sm" ng-click="main.save()" ng-disabled="translationForm.$invalid">Save</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <script>var modResourcesURL = '<?= $uri->base() ?>app/modules/translate/resources/';</script>
        <script>var modURL = '<?= $uri->base() ?>translate/';</script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <script src="<?= $uri->base() ?>app/modules/translate/resources/scripts/jquery.selectric.min.js"></script>
        <script src="<?= $uri->base() ?>app/modules/translate/resources/scripts/translate.js"></script>
    </body>
</html>
