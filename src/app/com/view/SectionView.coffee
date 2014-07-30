AbstractView = require "../abstract/AbstractView.coffee"

class SectionView extends AbstractView

    constructor: (opts) ->
        super(opts)



    initialize: (opts) ->
        super(opts)

        @model.on "dataLoaded" , @onDataLoaded
        @model.on "assetsLoaded" , @onAssetsLoaded


    onDataLoaded: =>
        @trigger "dataLoaded" , @
        @model.set "dataLoaded" , true
        @model.set "needsRender" , false
        @model.loadAssets()


    onAssetsLoaded: =>
        @trigger "assetsLoaded" , @
        @model.set "assetsLoaded" , true

    getTransitionInTimeline: (callback) ->
        @tlIn = new TimelineMax
            onComplete: @transitionInComplete
            onCompleteParams: [callback]

        fade = TweenMax.to @$el , .5 ,
            autoAlpha:1

        @tlIn.add fade
        return @tlIn

    transitionInBefore: =>
        if @model.get("needsRender")
            @render()
            @model.set "needsRender" , false

    transitionIn: (callback) =>
        @transitionInBefore()
        @getTransitionInTimeline(callback)


    transitionInComplete: (callback) =>
        if callback?
            callback()

    getTransitionOutTimeline: (callback) ->
        @tlOut = new TimelineMax
            onComplete: @transitionOutComplete
            onCompleteParams: [callback]

        fade = TweenMax.to @$el , .5 ,
            autoAlpha:0

        @tlOut.add fade
        return @tlOut

    transitionOutBefore: =>
        #TO DO
        #If something must be done before transition out

    transitionOut: (callback) =>
        @transitionOutBefore()
        @getTransitionOutTimeline(callback)

    transitionOutComplete: (callback) =>
        if callback?
            callback()














module.exports = SectionView