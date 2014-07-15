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
            section.model.attributes.path = route.path
            section.model.attributes.view = route.view

            @sections.push section

    getSectionByPath: (path) ->
        for section in @sections
            if path is section.model.get "path"
                return section

        return false


    onSectionLoaded: (section) =>
        if @currentSection?
            section.model.set("needsRender", true)
            @currentSection.transitionOut(section.transitionIn)
            @currentSection = section;
        else
            @currentSection = section
            @currentSection.transitionIn()

    onSectionData: (section) =>
        #console.log "dataLoaded"




    gotoView: (view) ->

        views = view.split("/")

        ###
        if views.length > 1
            views.shift()
            views = views.join("/")

            #TO DO
            #make another app mediateor to handle anything above this route

        ###

        section = @getSectionByPath "/"+view
        if section.model.get("assetsLoaded")
            @onSectionLoaded(section)
        else
            section.on "assetsLoaded" , @onSectionLoaded
            section.on "dataLoaded" , @onSectionData
            section.model.fetch()





module.exports = AppMediator