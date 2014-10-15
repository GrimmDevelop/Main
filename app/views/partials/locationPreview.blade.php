<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-map-marker"></span> @{{ location.name }}</h3>
</div>
<div class="modal-body">
    @{{ location.latitude }} @{{ location.longitude }}

    <div ng-if="showMap">
        <ui-gmap-google-map center="center" zoom="zoom">
            <ui-gmap-marker idKey="location.id" coords="location"></ui-gmap-marker>
        </ui-gmap-google-map>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
</div>