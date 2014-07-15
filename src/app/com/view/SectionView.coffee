AbstractView = require "../abstract/AbstractView.coffee"

class SectionView extends AbstractView


    initialize: (opts) ->
        super(opts)


        @model.on "dataLoaded" , @onDataLoaded
        @model.on "assetsLoaded" , @onAssetsLoaded


    onDataLoaded: =>
        @trigger "dataLoaded" , @
        @model.set "dataLoaded" , true
        @model.loadAssets()


    onAssetsLoaded: =>
        @trigger "assetsLoaded" , @
        @model.set "assetsLoaded" , true















module.exports = SectionView