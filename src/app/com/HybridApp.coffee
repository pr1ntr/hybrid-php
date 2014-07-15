AppMediator = require('./view/AppMediator.coffee')
AppModel = require('./model/AppModel.coffee')

class HybridApp extends Backbone.Router

    constructor: ->
        @initialize()



    initialize: ->
        @initModel()

    initModel: ->
        @appModel = new AppModel
            url:"/api/data"
        @appModel.on "assetsLoaded" , @onModelLoaded
        @appModel.fetch()

    onModelLoaded: =>

        @initView()
        @initRouter()

    initView: ->
        @viewMediator = new AppMediator
            el: "body"
            model: @appModel

        @viewMediator.start();


    initRouter: ->
        @route "*path" , @gotoSection
        Backbone.history.start
            pushState:true
            root: window.ROOT or "/"


    gotoSection: (path) =>

        if !path?
            path = "";




        @viewMediator.gotoView path




module.exports = HybridApp