
<div>
    @{{ location.name }}
    @{{ location.asciiname }}
    @{{ location.alternatenames }}
    @{{ location.latitude }}
    @{{ location.longitude }}

    @{{ map }}

    <google-map center="map.center" zoom="map.zoom"></google-map>
</div>
