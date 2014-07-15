AbstractViewMediator = require "../abstract/AbstractViewMediator.coffee"
AbstractModel = require "../abstract/AbstractModel.coffee"
NavigationView = require "./NavigationView.coffee"
SectionView = require "./SectionView.coffee"

class AppMediator extends AbstractViewMediator

    constructor: (opts) ->
        super(opts)
        @sections = []

    start: ->
        @generateNav()
        @generateSections()


    generateNav: ->
        @navigation = new NavigationView
            el: "nav"
            model: @model

    generateSections: ->

        for route in @model.get "routes"

            section = new SectionView
                el: "#content"
                template: @model.preloader.getResult(route.id)
                model: new AbstractModel
                    url: route.path
            section.model.on "dataLoaded" , @handleSectionEvent
            section.model.fetch()

            @sections.push section


    onSectionLoaded: (e) =>
        console.log "dataLoaded"

    onSectionData: (e) =>
        console.log "assetsLoaded"


    gotoView: (view) ->






module.exports = AppMediator